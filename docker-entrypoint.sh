#!/bin/sh
set -e

# Start PHP-FPM early so it's ready while we do config/DB wait
php-fpm &

# Ensure tmp and subdirs exist and are writable (Alpine: www-data from php-fpm image)
mkdir -p /var/www/html/tmp/cache /var/www/html/tmp/log /var/www/html/tmp/database_backups
chown -R www-data:www-data /var/www/html/tmp
chmod -R 775 /var/www/html/tmp

# For SQLite: ensure the database directory exists and is writable by the web server
if [ "${SI_DATABASE_ADAPTER:-pdo_mysql}" = "pdo_sqlite" ]; then
  mkdir -p /var/www/html/databases/sqlite
  chown -R www-data:www-data /var/www/html/databases/sqlite
  chmod 775 /var/www/html/databases/sqlite
fi

# When running in Docker Compose (or --env-file), override DB config so the app connects to the db.
# SQLite has no network host; skip the host-resolution wait for it.
if [ -n "${SI_DB_HOST}" ] && [ "${SI_DATABASE_ADAPTER:-pdo_mysql}" != "pdo_sqlite" ]; then
  # Single PHP process that waits for host resolution (avoids 30x php invocations)
  _port="${SI_DB_PORT:-3306}"
  _max="${SI_DB_WAIT_MAX:-30}"
  export SI_DB_WAIT_MAX="$_max"
  if ! php -r "
    \$h = getenv('SI_DB_HOST');
    if (!\$h) exit(1);
    \$max = (int) (getenv('SI_DB_WAIT_MAX') ?: 30);
    \$is_ip = filter_var(\$h, FILTER_VALIDATE_IP) !== false;
    for (\$n = 0; \$n < \$max; \$n++) {
      if (\$is_ip) exit(0);
      if (gethostbyname(\$h) !== \$h) exit(0);
      if (\$n < \$max - 1) sleep(1);
    }
    exit(1);
  " 2>/dev/null; then
    echo "Entrypoint: could not resolve host '${SI_DB_HOST}' after ${_max}s. Ensure the app container is on the same Docker network as the db service (e.g. docker compose up)." >&2
    exit 1
  fi
fi

cp -f /var/www/html/config/config.php /var/www/html/config/custom.config.php
php -r '
  $path = "/var/www/html/config/custom.config.php";
  $content = file_get_contents($path);
  if ($content === false) {
      fwrite(STDERR, "Entrypoint: unable to read custom.config.php template.\n");
      exit(1);
  }

  $map = [
      "SI_DATABASE_ADAPTER" => "database.adapter",
      "SI_DATABASE_UTF8" => "database.utf8",
      "SI_DB_HOST" => "database.params.host",
      "SI_DB_USER" => "database.params.username",
      "SI_DB_PASSWORD" => "database.params.password",
      "SI_DB_NAME" => "database.params.dbname",
      "SI_DB_PORT" => "database.params.port",
      "SI_AUTHENTICATION_ENABLED" => "authentication.enabled",
      "SI_AUTHENTICATION_HTTP" => "authentication.http",
      // Note: SI_EXPORT_SPREADSHEET, SI_EXPORT_WORDPROCESSOR, SI_EXPORT_PDF_*, SI_LOCAL_PRECISION,
      // and SI_CONFIRM_DELETE_LINE_ITEM are now managed via System Defaults (database).
      // Use the system_defaults UI (module=system_defaults&view=manage) to configure them.
      "SI_LOCAL_LOCALE" => "local.locale",
      "SI_EMAIL_HOST" => "email.host",
      "SI_EMAIL_SMTP_AUTH" => "email.smtp_auth",
      "SI_EMAIL_USERNAME" => "email.username",
      "SI_EMAIL_PASSWORD" => "email.password",
      "SI_EMAIL_SMTPPORT" => "email.smtpport",
      "SI_EMAIL_SECURE" => "email.secure",
      "SI_EMAIL_ACK" => "email.ack",
      "SI_EMAIL_USE_LOCAL_SENDMAIL" => "email.use_local_sendmail",
      "SI_ENCRYPTION_DEFAULT_KEY" => "encryption.default.key",
      "SI_NONCE_KEY" => "nonce.key",
      "SI_NONCE_TIMELIMIT" => "nonce.timelimit",
      "SI_APP_NAME" => "app.name",
      "SI_APP_LOGO" => "app.logo",
      "SI_APP_WEBSITE" => "app.website",
      "SI_APP_WEBSITE_LABEL" => "app.website_label",
      "SI_APP_FOOTER_LINK1_LABEL" => "app.footer_link1_label",
      "SI_APP_FOOTER_LINK1_URL" => "app.footer_link1_url",
      "SI_APP_FOOTER_LINK2_LABEL" => "app.footer_link2_label",
      "SI_APP_FOOTER_LINK2_URL" => "app.footer_link2_url",
      "SI_APP_FOOTER_LINK3_LABEL" => "app.footer_link3_label",
      "SI_APP_FOOTER_LINK3_URL" => "app.footer_link3_url",
      "SI_APP_FOOTER_LINK4_LABEL" => "app.footer_link4_label",
      "SI_APP_FOOTER_LINK4_URL" => "app.footer_link4_url",
      "SI_APP_FOOTER_TEXT" => "app.footer_text",
      "SI_DEBUG_LEVEL" => "debug.level",
      "SI_DEBUG_ERROR_REPORTING" => "debug.error_reporting",
      "SI_PHP_DATE_TIMEZONE" => "phpSettings.date.timezone",
      "SI_PHP_DISPLAY_STARTUP_ERRORS" => "phpSettings.display_startup_errors",
      "SI_PHP_DISPLAY_ERRORS" => "phpSettings.display_errors",
      "SI_PHP_LOG_ERRORS" => "phpSettings.log_errors",
      "SI_PHP_ERROR_LOG" => "phpSettings.error_log",
  ];

  $formatValue = static function (string $value): string {
      $trimmed = trim($value);
      if ($trimmed === "") {
          return "\"\"";
      }
      if (preg_match("/^(true|false|yes|no|null)$/i", $trimmed) || preg_match("/^-?[0-9]+(?:\\.[0-9]+)?$/", $trimmed)) {
          return $trimmed;
      }
      return "\"" . addcslashes($value, "\\\"") . "\"";
  };

  foreach ($map as $env => $key) {
      $value = getenv($env);
      if ($value === false) {
          continue;
      }

      $replacement = sprintf("%-35s = %s", $key, $formatValue($value));
      $pattern = "/^" . preg_quote($key, "/") . "[[:space:]]*=.*$/m";
      $content = preg_replace($pattern, $replacement, $content, 1);
  }

  if (file_put_contents($path, $content) === false) {
      fwrite(STDERR, "Entrypoint: unable to write custom.config.php.\n");
      exit(1);
  }
'

# Wait for PHP-FPM to be listening before starting nginx (avoids 502 on first request after cold start)
php -r "
  \$max = 30;
  for (\$n = 0; \$n < \$max; \$n++) {
    \$fp = @fsockopen('127.0.0.1', 9000, \$errno, \$errstr, 1);
    if (\$fp) { fclose(\$fp); exit(0); }
    if (\$n < \$max - 1) usleep(200000);
  }
  exit(1);
" || { echo "Entrypoint: PHP-FPM did not become ready on 127.0.0.1:9000." >&2; exit 1; }

# Run CMD (nginx -g "daemon off;"). Use full path so exec doesn't misinterpret args; skip leading "--".
case "${1:-nginx}" in
  --)
    shift; exec "$@" ;;
  nginx)
    exec /usr/sbin/nginx -g "daemon off;" ;;
  *)
    exec "$@" ;;
esac

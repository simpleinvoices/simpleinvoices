#!/bin/sh
set -e

# Start PHP-FPM early so it's ready while we do config/DB wait
php-fpm &

# Ensure tmp and subdirs exist and are writable (Alpine: www-data from php-fpm image)
mkdir -p /var/www/html/tmp/cache /var/www/html/tmp/log /var/www/html/tmp/database_backups
chown -R www-data:www-data /var/www/html/tmp
chmod -R 775 /var/www/html/tmp

# When running in Docker Compose (or --env-file), override DB config so the app connects to the db
if [ -n "${SI_DB_HOST}" ]; then
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

  cp -f /var/www/html/config/config.php /var/www/html/config/custom.config.php
  # Alpine/BusyBox sed -i requires backup extension; remove after
  sed -i.bak "s/^database\.params\.host[[:space:]]*=.*/database.params.host                = ${SI_DB_HOST}/" /var/www/html/config/custom.config.php
  sed -i.bak "s/^database\.params\.port[[:space:]]*=.*/database.params.port                = ${_port}/" /var/www/html/config/custom.config.php
  sed -i.bak "s/^database\.params\.username[[:space:]]*=.*/database.params.username            = ${SI_DB_USER}/" /var/www/html/config/custom.config.php
  sed -i.bak "s|^database\.params\.password[[:space:]]*=.*|database.params.password            = ${SI_DB_PASSWORD}|" /var/www/html/config/custom.config.php
  sed -i.bak "s/^database\.params\.dbname[[:space:]]*=.*/database.params.dbname              = ${SI_DB_NAME}/" /var/www/html/config/custom.config.php
  rm -f /var/www/html/config/custom.config.php.bak
fi

# Run CMD (nginx -g "daemon off;"). Use full path so exec doesn't misinterpret args; skip leading "--".
case "${1:-nginx}" in
  --)
    shift; exec "$@" ;;
  nginx)
    exec /usr/sbin/nginx -g "daemon off;" ;;
  *)
    exec "$@" ;;
esac

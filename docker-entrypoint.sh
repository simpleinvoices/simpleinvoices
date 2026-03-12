#!/bin/sh
set -e

# Ensure tmp and subdirs exist and are writable (Alpine: www-data from php-fpm image)
mkdir -p /var/www/html/tmp/cache /var/www/html/tmp/log /var/www/html/tmp/database_backups
chown -R www-data:www-data /var/www/html/tmp
chmod -R 775 /var/www/html/tmp

# Optional: run composer install at startup if vendor is missing
if [ -f "/var/www/html/composer.json" ] && [ ! -d "/var/www/html/vendor/autoload.php" ]; then
    echo "Running composer install in container..."
    (cd /var/www/html && composer install --no-interaction --no-dev --optimize-autoloader) || true
    chown -R www-data:www-data /var/www/html/vendor 2>/dev/null || true
fi

# When running in Docker Compose, override DB config so the app connects to the db service
if [ -n "${SI_DB_HOST}" ]; then
  # Wait until DB host resolves (Docker DNS) – same resolver PHP will use
  _port="${SI_DB_PORT:-3306}"
  _max=30
  _n=0
  while [ $_n -lt $_max ]; do
    if php -r "exit((gethostbyname('${SI_DB_HOST}') !== '${SI_DB_HOST}') ? 0 : 1);" 2>/dev/null; then
      break
    fi
    _n=$((_n + 1))
    [ $_n -lt $_max ] && sleep 1
  done
  if [ $_n -eq $_max ]; then
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

# Start PHP-FPM in background (listens on 127.0.0.1:9000 for nginx)
php-fpm &

# Run CMD (nginx -g "daemon off;")
exec "$@"

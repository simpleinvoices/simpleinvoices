#!/bin/bash
set -e

# Ensure tmp and subdirs exist and are writable by Apache (files are copied into image, no host mount)
mkdir -p /var/www/html/tmp/cache /var/www/html/tmp/log /var/www/html/tmp/database_backups
chown -R www-data:www-data /var/www/html/tmp
chmod -R 775 /var/www/html/tmp

# Optional: run composer install at startup if vendor is missing (e.g. custom image without build-step install)
if [ -f "/var/www/html/composer.json" ] && [ ! -d "/var/www/html/vendor/autoload.php" ]; then
    echo "Running composer install in container..."
    (cd /var/www/html && composer install --no-interaction --no-dev --optimize-autoloader) || true
    chown -R www-data:www-data /var/www/html/vendor 2>/dev/null || true
fi

# When running in Docker Compose, override DB config so the app connects to the mysql service
# (config uses custom.config.php if present; host must be service name "mysql", not localhost)
if [ -n "${SI_DB_HOST}" ]; then
  cp -f /var/www/html/config/config.php /var/www/html/config/custom.config.php
  sed -i "s/^database.params.host[[:space:]]*=.*/database.params.host                = ${SI_DB_HOST}/" /var/www/html/config/custom.config.php
  sed -i "s/^database.params.port[[:space:]]*=.*/database.params.port                = ${SI_DB_PORT:-3306}/" /var/www/html/config/custom.config.php
  sed -i "s/^database.params.username[[:space:]]*=.*/database.params.username            = ${SI_DB_USER}/" /var/www/html/config/custom.config.php
  sed -i "s|^database.params.password[[:space:]]*=.*|database.params.password            = ${SI_DB_PASSWORD}|" /var/www/html/config/custom.config.php
  sed -i "s/^database.params.dbname[[:space:]]*=.*/database.params.dbname              = ${SI_DB_NAME}/" /var/www/html/config/custom.config.php
fi

# Execute CMD
exec "$@"

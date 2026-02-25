#!/bin/bash
set -e

echo "Current user: $(id)"
echo "Current directory: $(pwd)"
echo "Directory contents: $(ls -la /var/www/html | head -n 10)"

# Check if composer.json exists
if [ -f "/var/www/html/composer.json" ]; then
    echo "composer.json found. Running composer install..."
    # Run composer as root but it will warn, it's ok for this setup
    composer install --no-interaction --no-dev --optimize-autoloader || echo "Composer install failed. Continuing..."
else
    echo "Warning: composer.json not found in $(pwd). Skipping composer install."
fi

# Only chown the tmp directory which MUST be writable
# We don't chown the whole /var/www/html to avoid issues with host mounts
mkdir -p /var/www/html/tmp
chown -R www-data:www-data /var/www/html/tmp
chmod -R 777 /var/www/html/tmp

# Execute CMD
exec "$@"

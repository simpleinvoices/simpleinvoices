# Stage 1: Build frontend vendor assets (Node.js)
FROM node:22-alpine AS asset-builder
WORKDIR /build
COPY package.json package-lock.json ./
RUN npm ci
COPY scripts/ ./scripts/
RUN node scripts/copy-assets.js

# Build Rspress documentation site
COPY docs-rspress/ ./docs-rspress/
RUN cd docs-rspress && npm install && npm run build
COPY docs/ ./docs/

# Stage 2: PHP-FPM + Nginx (Alpine-based, no official php-apache-alpine)
FROM php:8.2-fpm-alpine

# Build deps for PHP extensions (removed after install)
RUN apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev

# Runtime deps (kept); nginx for serving
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    libxml2 \
    nginx \
    icu-libs \
    icu-data-full

# PHP extensions (dom/xml for htmlpurifier etc.)
# pdo_sqlite: built into PHP but needs sqlite-dev at build time
# pdo_pgsql:  needs libpq-dev at build and libpq at runtime
RUN apk add --no-cache icu-dev sqlite-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    gd \
    dom \
    xml \
    intl \
    && apk del .build-deps \
    && apk add --no-cache libpq sqlite-libs

# Match nginx client_max_body_size: large JSON imports / backups (post_max_size must be >= upload_max_filesize)
RUN printf '%s\n' \
    'upload_max_filesize = 10000M' \
    'post_max_size = 10000M' \
    'memory_limit = 512M' \
    > /usr/local/etc/php/conf.d/docker-upload-limits.ini

# PHP-FPM: official image defaults to pm.max_children=5, which triggers "server reached pm.max_children"
# under light concurrent load. Tune down on small hosts (each child uses RAM while handling a request).
RUN set -eux; \
    fpm_conf=/usr/local/etc/php-fpm.d/www.conf; \
    sed -iE 's/^pm\.max_children = .*/pm.max_children = 20/' "$fpm_conf"; \
    grep -qE '^pm\.max_children = 20$' "$fpm_conf"; \
    sed -iE 's/^pm\.max_spare_servers = .*/pm.max_spare_servers = 8/' "$fpm_conf"

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy frontend vendor assets built in Stage 1
COPY --from=asset-builder --chown=www-data:www-data /build/templates/default/vendor ./templates/default/vendor

# Install composer deps before copying app source so layer is cached
# when only application code changes (not composer.json/composer.lock)
COPY --chown=www-data:www-data composer.json composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-interaction --no-dev --optimize-autoloader --prefer-dist

COPY --chown=www-data:www-data . /var/www/html/

# Copy Rspress documentation site built in Stage 1 (after app source to avoid overwrite)
COPY --from=asset-builder --chown=www-data:www-data /build/docs/ ./docs/

# Nginx config: root /var/www/html, PHP via FastCGI to 127.0.0.1:9000
RUN <<'NGINX'
cat > /etc/nginx/http.d/default.conf << 'EOF'
server {
    listen 80 default_server;
    root /var/www/html;
    index index.php index.html;
    client_max_body_size 10000M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location /docs/ {
        try_files $uri $uri.html $uri/ /docs/index.html =404;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
EOF
NGINX

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

RUN mkdir -p /var/www/html/tmp/cache /var/www/html/tmp/log /var/www/html/tmp/database_backups \
    && mkdir -p /var/www/html/databases/sqlite \
    && chown -R www-data:www-data /var/www/html/tmp /var/www/html/databases/sqlite

# PHP-FPM listens on 9000 by default
EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]

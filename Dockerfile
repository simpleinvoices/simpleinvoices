# Alpine-based image: PHP-FPM + Nginx (no official php-apache-alpine)
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
    nginx

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

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html/

ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-interaction --no-dev --optimize-autoloader --prefer-dist

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
    && chown -R www-data:www-data /var/www/html

# PHP-FPM listens on 9000 by default
EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]

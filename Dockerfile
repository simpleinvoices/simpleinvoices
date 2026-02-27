FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    curl \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (dom/xml needed by htmlpurifier and others)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    gd \
    dom \
    xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files into the container (no host volume)
COPY . /var/www/html/

# Install PHP dependencies with Composer (vendor created in image)
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts --prefer-dist

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Writable dirs for app (tmp/cache, logs)
RUN mkdir -p /var/www/html/tmp/cache /var/www/html/tmp/log /var/www/html/tmp/database_backups \
    && chown -R www-data:www-data /var/www/html

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]

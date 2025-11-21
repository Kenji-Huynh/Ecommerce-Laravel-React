# Dockerfile for deploying Laravel backend on Render when language autodetect is unavailable
# Uses Apache + PHP 8.3. Adjust extensions as needed.

FROM php:8.3-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip gd \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite for Laravel routing
RUN a2enmod rewrite

# Serve Laravel from public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy source code
COPY . .

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Composer install (no dev) & Laravel optimizations
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader \
 && mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs storage/app/public \
 && chown -R www-data:www-data storage bootstrap/cache \
 && php artisan key:generate --force || true \
 && php artisan package:discover --ansi || true \
 && php artisan config:cache || true \
 && php artisan route:cache || true \
 && php artisan view:cache || true

# Expose port (Render provides $PORT at runtime; Apache defaults to 80)
EXPOSE 80

# Health: optional simple script could be added

# Start command: run migrations (non-blocking) then start Apache
CMD bash -lc 'php artisan migrate --force || true; apache2-foreground'

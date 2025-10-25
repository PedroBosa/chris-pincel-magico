# syntax=docker/dockerfile:1.6

# Stage 1: Install PHP dependencies with Composer
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts
COPY . .
RUN mkdir -p storage/framework/{cache,data,sessions,testing,views} storage/app/public storage/logs bootstrap/cache
RUN composer dump-autoload --optimize --no-interaction --no-scripts

# Stage 2: Build frontend assets with Node
FROM node:20 AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 3: Final image with Apache + PHP
FROM php:8.2-apache
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Configure Apache for Laravel
RUN a2enmod rewrite \
    && sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
    && printf "<Directory /var/www/html/public>\n    AllowOverride All\n</Directory>\n" >> /etc/apache2/apache2.conf

# Copy application code
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

# Ensure runtime directories exist and have correct permissions
RUN rm -f /var/www/html/.env \
    && mkdir -p storage/framework/{cache,data,sessions,testing,views} storage/app/public bootstrap/cache storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

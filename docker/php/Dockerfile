FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Установим Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

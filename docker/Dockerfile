FROM php:7.1-fpm

WORKDIR /var/www/laravel

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zlib1g-dev \
    && docker-php-ext-install zip pdo pdo_mysql



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version



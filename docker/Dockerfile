FROM php:7-fpm

WORKDIR /var/www/laravel

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zlib1g-dev \
    libxml2-dev \
    && docker-php-ext-install zip pdo pdo_mysql  soap

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

#Set up debugger
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_host=192.168.0.59" >> 	/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini #Please provide your host (local machine IP)


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version



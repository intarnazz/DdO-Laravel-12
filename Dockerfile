FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    libmagickwand-dev \
    unzip \
    git \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip intl \
    && pecl install imagick \
    && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY . /var/www

RUN composer install \
    && mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www

RUN php artisan storage:link --force

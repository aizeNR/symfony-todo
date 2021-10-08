FROM php:7.4-fpm

RUN apt-get update --allow-releaseinfo-change\
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip supervisor\
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /etc

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]
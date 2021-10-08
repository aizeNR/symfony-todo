FROM php:7.4-fpm

RUN apt-get update --allow-releaseinfo-change\
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql

WORKDIR /var/www/symfony_docker

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY scripts /var/www/scripts

ENTRYPOINT ["bash", "/var/www/scripts/message.sh"]

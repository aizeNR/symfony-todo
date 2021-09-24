FROM php:7.4-fpm

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN apt-get update --allow-releaseinfo-change\
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/symfony_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD composer install; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate; bin/console lexik:jwt:generate-keypair --skip-if-exists; php-fpm;
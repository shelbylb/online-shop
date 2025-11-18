FROM php:8.3-fpm

RUN apt update -q;
RUN apt install -q -y libpq-dev;
RUN docker-php-ext-install pdo_pgsql pgsql;
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug;
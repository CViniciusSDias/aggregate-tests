FROM php:7.4-cli
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
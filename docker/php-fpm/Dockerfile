FROM php:8.1-fpm
RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug ;\
    docker-php-ext-install pdo pdo_mysql;

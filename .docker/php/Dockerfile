FROM php:8.0-fpm-alpine as web_api

# Mise à jour et ajout des paquets requis
RUN apk update && apk add --no-cache \
    git \
    shadow \
    libzip-dev \
    mongodb-tools \
    rabbitmq-c-dev \
    oniguruma-dev \
    linux-headers \
    icu-libs

# Installation des extensions PHP et de leurs dépendances
RUN set -xe && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip intl opcache pdo_mysql && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    pecl install amqp && \
    docker-php-ext-enable amqp && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps

# Installation de Composer
COPY install-composer.sh /usr/local/bin/install-composer

RUN set -xe && \
    apk add --no-cache --virtual .fetch-deps openssl && \
    chmod +x /usr/local/bin/install-composer && \
    /usr/local/bin/install-composer && \
    mv composer.phar /usr/local/bin/composer && \
    composer clear-cache && \
    apk del .fetch-deps

# Configuration Symfony et permissions
COPY symfony.ini /usr/local/etc/php/conf.d/symfony.ini

ARG uid=1000
ARG gid=1000
RUN usermod -u $uid www-data && \
    groupmod -g $gid www-data

USER www-data

WORKDIR /var/www/app

# Configuration Supervisor pour le consumer
FROM web_api as events_consumer

USER root

RUN apk add --no-cache supervisor && \
    mkdir /etc/supervisor /var/log/supervisor

COPY events_consumer.conf /etc/supervisor/events_consumer.conf
COPY supervisord.conf /etc/supervisor/supervisord.conf

ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/etc/supervisor/supervisord.conf"]

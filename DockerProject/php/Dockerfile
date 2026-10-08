FROM php:7.3-apache
COPY ./php.ini /usr/local/etc/php/
RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv \
  && apt-get install -y openssh-server

RUN apt-get install -y vim

RUN apt-get update && apt-get install -y default-mysql-client

RUN apt-get update && apt-get install -y --no-install-recommends \
    libmemcached-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    autoconf\
    build-essential\
    && rm -rf /var/lib/apt/lists/*
RUN pecl install redis-4.2.0 && docker-php-ext-enable redis

COPY ./var/www/html/

RUN pecl install memcached \
    && docker-php-ext-enable memcached

COPY ./php_apache2.conf /etc/apache2/mods-enabled
RUN apache2ctl restart
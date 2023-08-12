FROM composer:latest as build

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader

FROM php:8.2.8-cli-alpine3.18 as cli

WORKDIR /app

COPY --from=build /app /app

FROM php:8.2-fpm

WORKDIR /var/www/html

ARG user=laravel
ARG uid=1000

ENV APP_ENV=production
ENV APP_DEBUG=false

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y \
    curl \
    nano \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=build --chown=www-data:www-data /app ./

RUN chmod -R 777 ./storage ./bootstrap/cache

RUN useradd -G www-data -s /sbin/nologin -u $uid $user

USER $user

EXPOSE 8080

CMD [ "php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8080" ]
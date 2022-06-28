FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN apk update
RUN apk add --no-cache ffmpeg
RUN apk add --no-cache screen

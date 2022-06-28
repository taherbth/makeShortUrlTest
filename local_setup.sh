#!/bin/sh
set -e

# Removing all unnecessary containers
v=$(docker ps -q)
if [ -n "$v" ]; then
    echo There are containers from previous, killing...
    docker kill "$(docker ps -q)" && docker rm "$(docker ps -a -q)"
else
    echo No container running...
fi

echo "Building application ..."

# general setup
cp .env.docker .env
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache

# Installing dependencies if necessary
composer install --no-interaction --prefer-dist --optimize-autoloader
npm install

# building frontend
npm run dev

# building container
echo "Building containers ..."
docker-compose up -d --build

echo "Application built at localhost:9080"

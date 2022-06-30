# Url Shortner

## Local setup
For frontend, 
- use node version 14 or latest
- use npm version 6 latest


For backend, 
- use PHP version `7.4`. 
- create a database named as you like and configure it in .env file

Try using [phpenv](https://github.com/phpenv/phpenv) for local
```shell
phpenv local 7.4.0
```

run,
```sh
	composer install && npm install && php artisan key:generate && composer require laravel/passport && php artisan migrate && php artisan passport:install && npm run dev
```

### Fresh migration
```sh
php artisan migrate:fresh && php artisan passport:install --force && php artisan serve
``

#!/bin/bash
set -x
composer install
sleep 10
php artisan migrate
php artisan db:seed
chmod 777 -R ./
php-fpm

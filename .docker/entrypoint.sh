#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
cd /var/www/frontend && npm install && cd ..

cd backend
chown -R www-data:www-data .
if [ ! -f ".env" ]; then
  cp .env.example .env
fi
if [ ! -f ".env.testing" ]; then
  cp .env.testing.example .env.testing
fi

pwd
composer install
php artisan key:generate
php artisan db:create
php artisan migrate --seed

php-fpm

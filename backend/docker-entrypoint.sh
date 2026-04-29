#!/bin/bash



if [ ! -f /var/www/composer.json ]; then
    echo "Laravel not found, creating..."
    composer create-project laravel/laravel /var/www/app
    php artisan key:generate
    php artisan migrate --force

    chown -R www-data:www-data /var/www/app
    chmod -R 775 /var/www/app/storage
    chmod -R 775 /var/www/app/bootstrap/cache
fi

exec "$@"
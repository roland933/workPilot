#!/bin/bash

echo "Starting container..."

# 1. Laravel install (csak ha nincs)
if [ ! -f /var/www/app/composer.json ]; then
    echo "Laravel not found, creating..."

    composer create-project laravel/laravel /var/www/app

    php artisan key:generate
    php artisan migrate --force
fi

# 2. Permission fix (MINDIG fusson)
echo "Fixing permissions..."

chown -R www-data:www-data /var/www/app
chmod -R 775 /var/www/app/storage
chmod -R 775 /var/www/app/bootstrap/cache
chmod -R 777 /var/www/app/database

# 3. (opcionális) migrate mindig
php artisan migrate --force 

exec "$@"
#!/bin/bash

echo "Starting container..."

# 1. Laravel install
if [ ! -f /var/www/app/composer.json ]; then
    echo "Laravel not found, creating..."
    composer create-project laravel/laravel /var/www/app
fi

# 2. Composer install ha kell
if [ ! -d /var/www/app/vendor ]; then
    echo "Installing dependencies..."
    composer install
fi

# 3. Permissions
chown -R www-data:www-data /var/www/app
chmod -R 775 /var/www/app/storage
chmod -R 775 /var/www/app/bootstrap/cache
chmod -R 777 /var/www/app/database

# 4. Laravel setup
php artisan key:generate
php artisan migrate --force

exec "$@"
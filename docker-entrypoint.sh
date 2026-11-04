#!/bin/bash
set -e

# Ensure .env file exists
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
fi

# Update apache port to Render's dynamic PORT if provided
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf
fi

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/.env
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod 664 /var/www/html/.env

# Always clear config cache on startup so Render environment variables are dynamically read
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

exec apache2-foreground

#!/bin/bash
set -e

# Update apache port to Render's dynamic PORT if provided
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf
fi

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate key if not provided
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run caching optimizations safely
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec apache2-foreground

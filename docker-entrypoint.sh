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

# Write complete pre-configured .env so no manual dashboard setup is needed
PW=$(php -r "echo base64_decode('QVZOU19vaUNBRjZFdTB6VVBrRFVpYWVr');")
cat << EOF > /var/www/html/.env
APP_NAME="Biz Care IT"
APP_ENV=production
APP_KEY=base64:pmhr8DGga45cRnP2qrcMB6Ng11tg2yy+etYiqdy4k1U=
APP_DEBUG=false
APP_URL=https://bdpicks-shop-1.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=${DB_HOST:-mysql-14bf6e17-bishalovi4874-c878.j.aivencloud.com}
DB_PORT=${DB_PORT:-20616}
DB_DATABASE=${DB_DATABASE:-defaultdb}
DB_USERNAME=${DB_USERNAME:-avnadmin}
DB_PASSWORD=${DB_PASSWORD:-$PW}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF

chown www-data:www-data /var/www/html/.env
chmod 664 /var/www/html/.env

# Clear any cached config so these values apply immediately
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan storage:link || true

exec apache2-foreground

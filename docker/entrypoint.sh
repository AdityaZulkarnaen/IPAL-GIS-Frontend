#!/bin/sh
set -e

# ============================================================
# Wait for MySQL to be ready
# ============================================================
if [ "$DB_CONNECTION" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
    maxTries=30
    tries=0
    while ! php -r "try { new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'ok'; } catch(Exception \$e) { exit(1); }" 2>/dev/null; do
        tries=$((tries + 1))
        if [ $tries -ge $maxTries ]; then
            echo "Error: MySQL not reachable after $maxTries attempts. Exiting."
            exit 1
        fi
        echo "MySQL not ready yet... retry $tries/$maxTries"
        sleep 2
    done
    echo "MySQL is ready!"
fi

# ============================================================
# Create storage directories if they don't exist
# ============================================================
mkdir -p \
    storage/logs \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/app/public \
    bootstrap/cache

# ============================================================
# Fix permissions
# ============================================================
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ============================================================
# Create storage symlink
# ============================================================
if [ ! -L public/storage ]; then
    php artisan storage:link
    echo "Storage link created."
fi

# ============================================================
# Generate app key if not set
# ============================================================
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    php artisan key:generate --force
    echo "Application key generated."
fi

# ============================================================
# Run database migrations
# ============================================================
php artisan migrate --force
echo "Migrations completed."

# ============================================================
# Cache config in production
# ============================================================
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    echo "Configuration cached for production."
fi

# ============================================================
# Create PHP error log directory
# ============================================================
mkdir -p /var/log/php
chown www-data:www-data /var/log/php

echo "Entrypoint complete. Starting $@"
exec "$@"

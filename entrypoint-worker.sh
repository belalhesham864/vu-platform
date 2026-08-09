#!/bin/bash
set -e

# Wait for MySQL to be reachable
until nc -z db 3306; do sleep 1; done

# Ensure .env exists
[ -f .env ] || cp .env.example .env

# Generate APP_KEY if missing
grep -q "APP_KEY=base64" .env || php artisan key:generate --ansi

# Run migrations (idempotent)
php artisan migrate --force --ansi

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Remove any stale cache so .env values always win
rm -f bootstrap/cache/config.php bootstrap/cache/routes.php bootstrap/cache/views.php

# Start the queue worker as PID 1 (never php-fpm here)
exec php artisan queue:work --tries=3 --timeout=90 --sleep=3

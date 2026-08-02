#!/bin/sh

# Install composer dependencies if vendor is missing
if [ ! -d "vendor" ]; then
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# Copy .env if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate application key if not set
php artisan key:generate --ansi

# Wait for database to be ready
echo "Waiting for database connection..."
until nc -z db 3306; do
  sleep 1
done
echo "Database is ready!"

# Run database migrations
php artisan migrate --force --ansi

# Discover packages
php artisan package:discover --ansi

# Clear and cache configurations
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Run the main container command
exec "$@"

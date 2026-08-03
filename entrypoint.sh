#!/bin/sh
#!/bin/bash
set -e

# Wait for database
echo "Waiting for database..."
until nc -z db 3306; do
  sleep 1
done
echo "Database is up!"

# Setup Laravel
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Generate key if not present
if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate --ansi
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force --ansi

# Optimization
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set correct permissions just in case
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Execute CMD
exec "$@"

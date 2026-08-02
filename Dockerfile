# Stage 1: Build assets
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    icu-dev \
    libzip-dev \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . .

# Copy built assets from assets-builder
COPY --from=assets-builder /app/public/build ./public/build

# Install composer dependencies, skipping post-install scripts
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Artisan commands moved to entrypoint.sh to prevent build failures due to code-level issues during discovery

EXPOSE 9000
COPY entrypoint.sh /var/www/entrypoint.sh
RUN chmod +x /var/www/entrypoint.sh
CMD ["php-fpm"]

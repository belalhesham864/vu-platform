# Stage 1: Build Frontend Assets
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application Environment
FROM php:8.3-fpm-alpine

WORKDIR /var/www

# تثبيت التبعيات مع تنظيف الملفات المؤقتة فوراً لتوفير المساحة
RUN apk add --no-cache \
    bash curl libpng-dev libxml2-dev zip unzip git \
    oniguruma-dev icu-dev libzip-dev mysql-client nginx supervisor \
    freetype-dev libjpeg-turbo-dev libwebp-dev zlib-dev

# إعداد وتثبيت إضافات PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip \
    && rm -rf /tmp/* # تنظيف الملفات المؤقتة

# تثبيت Redis
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS # حذف أدوات البناء بعد الانتهاء لتوفير مساحة

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
COPY --from=assets-builder /app/public/build ./public/build

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

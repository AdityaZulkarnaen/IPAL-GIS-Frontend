# ============================================================
# Stage 1: Base PHP-FPM image with extensions
# ============================================================
FROM php:8.2-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        xml \
        bcmath \
        fileinfo \
        gd \
        zip \
        opcache \
        intl \
        pcntl \
    && rm -rf /var/cache/apk/*

# Copy custom PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Set working directory
WORKDIR /var/www/html

# ============================================================
# Stage 2: Composer dependency installation
# ============================================================
FROM base AS composer-deps

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install production dependencies only
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# Copy application source code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# ============================================================
# Stage 3: Node.js build stage for Vite assets
# ============================================================
FROM node:20-alpine AS node-build

WORKDIR /var/www/html

# Copy package files first for better layer caching
COPY package.json package-lock.json* ./

# Install Node.js dependencies
RUN npm ci

# Copy source files needed for build
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

# Build production assets
RUN npm run build

# ============================================================
# Stage 4: Development image
# ============================================================
FROM base AS dev

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Xdebug for debugging
RUN apk add --no-cache linux-headers $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS

# Xdebug configuration
RUN echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Create laravel storage directories
RUN mkdir -p \
    storage/logs \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/app/public \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

# ============================================================
# Stage 5: Production image
# ============================================================
FROM base AS production

# Copy vendor from composer-deps stage
COPY --from=composer-deps /var/www/html/vendor ./vendor

# Copy built assets from node-build stage
COPY --from=node-build /var/www/html/public/build ./public/build

# Copy application source code
COPY . .

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Create storage directories
RUN mkdir -p \
    storage/logs \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/app/public \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Cache Laravel configuration for performance
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

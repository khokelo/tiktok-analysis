FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    curl \
    git \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    xml \
    ctype \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies
RUN npm install --legacy-peer-deps

# Build frontend assets
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8000

# Health check
# HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
#     CMD curl -f http://localhost:8000/health || exit 1

# Run artisan and start server
CMD php artisan serve --host=0.0.0.0 --port=8000

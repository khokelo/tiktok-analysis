FROM php:8.2-fpm
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip
RUN docker-php-ext-install pdo_mysql gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . .
RUN composer install --no-dev
CMD php artisan serve --host=0.0.0.0 --port=$PORT
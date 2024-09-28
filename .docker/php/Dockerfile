FROM php:8.3.12-fpm-bookworm

WORKDIR /var/www/html

# Update all applications
RUN apt-get update

# Install applications
RUN apt-get install --quiet --yes --no-install-recommends \
    libzip-dev \
    libpq-dev \
    unzip

# Install Database PHP extensions and zip ext.
RUN docker-php-ext-install \
    zip \
    pdo \
    pgsql \
    pdo_pgsql \
    opcache

# Install and enable redis PHP extension
RUN pecl install -o -f redis-6.0.2
RUN docker-php-ext-enable \
    redis

# Create PHP-FPM's and nginx user
RUN groupadd \
    --gid 1010 \
    www
RUN useradd \
    --uid 1010 \
    --gid www \
    --no-create-home \
    www

# Set image's user
USER www

# Install Composer
COPY --from=composer/composer:2.7.9-bin /composer /usr/local/bin/composer

# Install project's dependencies
COPY --chown=www ./composer.json /var/www/html
RUN composer install \
            --no-dev \
            --optimize-autoloader \
            --prefer-dist \
            --no-scripts \
            --no-autoloader \
            --no-progress

# Install the PHP production configuration
COPY --chown=www .docker/php/php.ini "$PHP_INI_DIR/php.ini"
COPY --chown=www .docker/php/php-fpm.config "$PHP_INI_DIR/php-fpm.config"

# Install project files
COPY --chown=www . /var/www/html

# Generate autoload files
RUN composer dump-autoload --optimize
RUN composer check-platform-reqs

# Create image storage (symbolic link)
RUN php artisan storage:link
# Optimize stuff
RUN php artisan optimize

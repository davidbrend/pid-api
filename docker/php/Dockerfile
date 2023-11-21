# Base image
FROM php:8.1-fpm

USER root

# Install dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    libzip-dev \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy source code to container
COPY . /var/www/html

# Install dependencies with Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
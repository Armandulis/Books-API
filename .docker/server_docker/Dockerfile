FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    zip \
    libzip-dev \
    curl \
    git \
    unzip \
    bash

# Install PHP extensions
RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_mysql

# Install composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash; \
    mv /root/.symfony5/bin/symfony /usr/local/bin/

CMD ["php", "-S", "0.0.0.0:8040", "-t", "public"]
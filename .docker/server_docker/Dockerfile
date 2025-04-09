FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    zip \
    libzip-dev \
    curl \
    git \
    unzip \
    bash \
    $PHPIZE_DEPS \
    autoconf \
    gcc \
    g++ \
    make \
    linux-headers

# Install PHP extensions
RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_mysql

# Install and enable Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Recommended dev config for Xdebug
RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=no" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash; \
    mv /root/.symfony5/bin/symfony /usr/local/bin/

CMD ["php", "-S", "0.0.0.0:8040", "-t", "public"]
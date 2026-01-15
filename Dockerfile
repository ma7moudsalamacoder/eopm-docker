FROM php:8.3-apache

# -----------------------------
# ARGs for macOS UID/GID
# -----------------------------
ARG UID=501
ARG GID=1000

# -----------------------------
# Apache config
# -----------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/eopm/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

RUN a2enmod rewrite

# -----------------------------
# System dependencies
# -----------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# Create application user (macOS safe)
# -----------------------------
RUN groupadd -g ${GID} appgroup \
    && useradd -u ${UID} -g ${GID} -m appuser

# -----------------------------
# PHP temp directory
# -----------------------------
RUN mkdir -p /var/www/html/tmp \
    && chown -R appuser:appgroup /var/www/html/tmp

RUN echo "sys_temp_dir = /var/www/html/tmp" > /usr/local/etc/php/conf.d/temp.ini

# -----------------------------
# Install Composer
# -----------------------------
RUN docker-php-ext-install sockets
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# -----------------------------
# App directory permissions
# -----------------------------
RUN mkdir -p /var/www/html \
    && chown -R appuser:appgroup /var/www

# -----------------------------
# Switch user (IMPORTANT)
# -----------------------------
USER appuser

WORKDIR /var/www/html

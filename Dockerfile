# PHP-FPM 8.3
FROM php:8.3-fpm-alpine

# Variables pour UID/GID
ARG PUID=1000
ARG PGID=1000

# Installer dépendances système
RUN apk add --no-cache \
    bash git unzip icu-dev libzip-dev oniguruma-dev \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    mysql-client

# Installer extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) \
    pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Créer utilisateur avec même UID/GID que l’hôte
RUN addgroup -g ${PGID} app \
 && adduser -D -G app -u ${PUID} app

WORKDIR /var/www/html
USER app

EXPOSE 9000
CMD ["php-fpm"]

# Usa imagen base de PHP 8.3 con Apache
FROM php:8.3-apache

# 1. Dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl gd mbstring


# 2. Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
    
# 3. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Configuración Apache apuntando al public/ de Laravel
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

# 5. Habilitar mod_rewrite
RUN a2enmod rewrite

# 6. Directorio de trabajo
WORKDIR /var/www/html
FROM php:8.4-apache

# Instalar utilidades
RUN apt-get update && apt-get install -y zip unzip

# Instalar PDO MySQL
RUN docker-php-ext-install pdo_mysql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto completo al contenedor
WORKDIR /var/www/html
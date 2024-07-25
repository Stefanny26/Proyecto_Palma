FROM php:7.4-apache

# Instalar dependencias necesarias y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar el contenido del proyecto al directorio web de Apache
COPY . /var/www/html/

# Dar permisos adecuados a los archivos
RUN chown -R www-data:www-data /var/www/html/

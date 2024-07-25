FROM php:7.4-apache

# Instalar dependencias necesarias y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    cron \
    openssh-client \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar el contenido del proyecto al directorio web de Apache
COPY . /var/www/html/

# Copiar el script de backup al contenedor
COPY backup.sh /usr/local/bin/backup.sh

# Copiar la clave privada SSH al contenedor
COPY id_rsa /root/.ssh/id_rsa

# Configurar permisos para la clave privada y el script
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod 600 /root/.ssh/id_rsa \
    && chmod +x /usr/local/bin/backup.sh

# Configurar cron para ejecutar el script de backup diariamente
RUN echo "0 0 * * * /usr/local/bin/backup.sh" > /etc/cron.d/backup-cron \
    && chmod 0644 /etc/cron.d/backup-cron \
    && crontab /etc/cron.d/backup-cron

# Iniciar cron y apache en primer plano
CMD cron && apache2-foreground

FROM php:8.2-apache

# Utilidades del sistema: unzip (lo necesita Composer para extraer paquetes) y git.
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip git \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP necesarias para MySQL/MariaDB (la app usa PDO).
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite. El indice del directorio raiz lo resuelve index.php
# (la imagen ya define DirectoryIndex index.php index.html).
RUN a2enmod rewrite

# Composer (para instalar PHPMailer).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instalar dependencias PHP primero (aprovecha la cache de capas de Docker).
COPY composer.json ./
RUN composer update --no-dev --no-interaction --no-progress --optimize-autoloader

# Copiar el resto de la aplicacion.
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]

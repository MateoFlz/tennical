FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git

# Configure and install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# Copia solo composer.json y composer.lock primero para aprovechar el cache de Docker
COPY composer.json composer.lock* ./

# Instala dependencias PHP (incluyendo doctrine/annotations)
RUN composer install --no-interaction --no-scripts --no-progress

# Copia el resto del código fuente
COPY . /var/www/html

# Genera swagger.json (ignora errores para que no falle el build)
RUN ./vendor/bin/openapi --output public/swagger.json src/Infrastructure/Swagger src/Application/Controllers src/Domain/Entities || true

# Copia Swagger UI a /public/docs
RUN mkdir -p public/docs && cp -r vendor/swagger-api/swagger-ui/dist/* public/docs/

# Ejecuta el script para corregir la configuración de Swagger UI
COPY docker/fix-swagger.sh /tmp/fix-swagger.sh
RUN chmod +x /tmp/fix-swagger.sh && /tmp/fix-swagger.sh

# Configura Apache
COPY docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# Hacer ejecutable el script de inicialización
RUN chmod +x /var/www/html/docker/init.sh

# Usar nuestro script de inicialización en lugar de apache2-foreground
CMD ["/var/www/html/docker/init.sh"]
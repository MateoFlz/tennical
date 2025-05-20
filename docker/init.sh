#!/bin/bash


echo "Esperando a que la base de datos esté lista..."
sleep 10


cd /var/www/html


echo "Ejecutando migraciones..."
php database/migrate.php


echo "Ejecutando seeders..."
if [ -f database/seed.php ]; then
    php database/seed.php
fi


echo "Asignando permisos..."
if [ -d /var/www/html/storage ]; then
    chown -R www-data:www-data /var/www/html/storage
    chmod -R 775 /var/www/html/storage
fi


echo "Actualizando configuración de Swagger UI..."
cat > /var/www/html/public/docs/swagger-initializer.js << 'EOL'
window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // Configuración personalizada para nuestra API
  window.ui = SwaggerUIBundle({
    url: "/swagger.json",
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "StandaloneLayout"
  });

  //</editor-fold>
};
EOL
echo "Configuración de Swagger UI actualizada correctamente."


echo "Iniciando Apache..."
apache2-foreground

#!/bin/bash

sleep 2


SWAGGER_INIT_FILE="/var/www/html/public/docs/swagger-initializer.js"


if [ ! -f "$SWAGGER_INIT_FILE" ]; then
    echo "Error: El archivo $SWAGGER_INIT_FILE no existe."
    exit 1
fi


cp "$SWAGGER_INIT_FILE" "${SWAGGER_INIT_FILE}.bak"


cat > "$SWAGGER_INIT_FILE" << 'EOL'
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


if [ $? -eq 0 ]; then
    echo "Configuración de Swagger UI actualizada correctamente."
else
    echo "Error al actualizar la configuración de Swagger UI."
    cp "${SWAGGER_INIT_FILE}.bak" "$SWAGGER_INIT_FILE"
    exit 1
fi

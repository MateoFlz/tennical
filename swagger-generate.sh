#!/bin/sh
# Script para generar el spec OpenAPI en public/swagger.json
./vendor/bin/openapi --output public/swagger.json src/Infrastructure/Swagger src/Application/Controllers src/Domain/Entities

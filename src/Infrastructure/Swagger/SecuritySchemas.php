<?php
namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 */
class SecuritySchemas {}

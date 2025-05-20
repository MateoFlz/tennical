<?php
namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Profesor",
 *   type="object",
 *   required={"nombre"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *   @OA\Property(property="email", type="string", example="juan@demo.com")
 * )
 */
class ProfesorSchema {}

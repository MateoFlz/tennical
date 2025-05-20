<?php
namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Curso",
 *   type="object",
 *   required={"nombre"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nombre", type="string", example="Matemáticas"),
 *   @OA\Property(property="descripcion", type="string", example="Curso de álgebra básica")
 * )
 */
class CursoSchema {}

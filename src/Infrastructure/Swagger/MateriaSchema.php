<?php
namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Materia",
 *   type="object",
 *   required={"nombre"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nombre", type="string", example="Física"),
 *   @OA\Property(property="descripcion", type="string", example="Materia de física general")
 * )
 */
class MateriaSchema {}

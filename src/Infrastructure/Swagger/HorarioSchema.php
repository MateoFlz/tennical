<?php
namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Horario",
 *   type="object",
 *   required={"profesor_id","dia","hora_inicio","hora_fin"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="profesor_id", type="integer", example=2),
 *   @OA\Property(property="dia", type="string", example="Lunes"),
 *   @OA\Property(property="hora_inicio", type="string", example="08:00"),
 *   @OA\Property(property="hora_fin", type="string", example="10:00"),
 *   @OA\Property(property="curso_id", type="integer", example=3)
 * )
 */
class HorarioSchema {}

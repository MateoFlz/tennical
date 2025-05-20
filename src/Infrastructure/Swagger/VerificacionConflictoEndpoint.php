<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="VerificacionConflictoRequest",
 *   type="object",
 *   required={"profesor_id", "curso_id"},
 *   @OA\Property(property="profesor_id", type="integer", example=2),
 *   @OA\Property(property="curso_id", type="integer", example=3),
 *   @OA\Property(property="horario_id", type="integer", example=5, nullable=true)
 * )
 * 
 * @OA\Schema(
 *   schema="VerificacionConflictoResponse",
 *   type="object",
 *   @OA\Property(property="conflicto", type="boolean", example=true),
 *   @OA\Property(property="mensaje", type="string", example="Existe un conflicto de horario"),
 *   @OA\Property(property="detalles", type="object",
 *     @OA\Property(property="profesor_id", type="integer", example=2),
 *     @OA\Property(property="curso_id", type="integer", example=3),
 *     @OA\Property(property="dia", type="string", example="Lunes"),
 *     @OA\Property(property="hora_inicio", type="string", example="08:00"),
 *     @OA\Property(property="hora_fin", type="string", example="10:00"),
 *     @OA\Property(property="conflicto_con", type="string", example="Curso de Física")
 *   )
 * )
 */

class VerificacionConflictoEndpoint
{
    /**
     * @OA\Post(
     *     path="/api/verificar-conflicto",
     *     operationId="verificarConflicto",
     *     summary="Verificar si existe un conflicto de horario",
     *     tags={"Conflictos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para verificar conflictos",
     *         @OA\JsonContent(ref="#/components/schemas/VerificacionConflictoRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="No hay conflictos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="conflicto", type="boolean", example=false),
     *             @OA\Property(property="mensaje", type="string", example="No hay conflictos de horario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Existe un conflicto",
     *         @OA\JsonContent(ref="#/components/schemas/VerificacionConflictoResponse")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function verificarConflicto() {}
}

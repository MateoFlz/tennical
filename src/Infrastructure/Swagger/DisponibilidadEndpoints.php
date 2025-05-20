<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;


class DisponibilidadEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/profesores/{id}/disponibilidad",
     *     summary="Obtener la disponibilidad de un profesor",
     *     tags={"Disponibilidad"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Disponibilidad del profesor",
     *         @OA\JsonContent(type="array", @OA\Items(
     *           type="object",
     *           required={"profesor_id","dia","hora_inicio","hora_fin"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="dia", type="string", example="Lunes"),
     *           @OA\Property(property="hora_inicio", type="string", example="08:00"),
     *           @OA\Property(property="hora_fin", type="string", example="10:00")
     *         ))
     *     ),
     *     @OA\Response(response=404, description="Profesor no encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerDisponibilidadProfesor() {}

    /**
     * @OA\Post(
     *     path="/api/profesores/{id}/disponibilidad",
     *     summary="Definir disponibilidad de un profesor",
     *     tags={"Disponibilidad"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *           type="object",
     *           required={"profesor_id","dia","hora_inicio","hora_fin"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="dia", type="string", example="Lunes"),
     *           @OA\Property(property="hora_inicio", type="string", example="08:00"),
     *           @OA\Property(property="hora_fin", type="string", example="10:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Disponibilidad registrada",
     *         @OA\JsonContent(
     *           type="object",
     *           required={"profesor_id","dia","hora_inicio","hora_fin"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="dia", type="string", example="Lunes"),
     *           @OA\Property(property="hora_inicio", type="string", example="08:00"),
     *           @OA\Property(property="hora_fin", type="string", example="10:00")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=404, description="Profesor no encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function definirDisponibilidadProfesor() {}

    /**
     * @OA\Put(
     *     path="/api/profesores/{id}/disponibilidad/{disponibilidad_id}",
     *     summary="Actualizar disponibilidad de un profesor",
     *     tags={"Disponibilidad"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="disponibilidad_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *           type="object",
     *           required={"profesor_id","dia","hora_inicio","hora_fin"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="dia", type="string", example="Lunes"),
     *           @OA\Property(property="hora_inicio", type="string", example="08:00"),
     *           @OA\Property(property="hora_fin", type="string", example="10:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disponibilidad actualizada",
     *         @OA\JsonContent(
     *           type="object",
     *           required={"profesor_id","dia","hora_inicio","hora_fin"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="dia", type="string", example="Lunes"),
     *           @OA\Property(property="hora_inicio", type="string", example="08:00"),
     *           @OA\Property(property="hora_fin", type="string", example="10:00")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=404, description="Profesor o disponibilidad no encontrados"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function actualizarDisponibilidadProfesor() {}

    /**
     * @OA\Delete(
     *     path="/api/profesores/{id}/disponibilidad/{disponibilidad_id}",
     *     summary="Eliminar disponibilidad de un profesor",
     *     tags={"Disponibilidad"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="disponibilidad_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Disponibilidad eliminada"),
     *     @OA\Response(response=404, description="Profesor o disponibilidad no encontrados"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function eliminarDisponibilidadProfesor() {}
}

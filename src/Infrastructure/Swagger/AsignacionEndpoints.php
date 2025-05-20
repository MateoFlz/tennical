<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

class AsignacionEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/profesores/{id}/cursos",
     *     summary="Obtener cursos asignados a un profesor",
     *     tags={"Asignación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Cursos asignados al profesor",
     *         @OA\JsonContent(type="array", @OA\Items(
     *           type="object",
     *           required={"profesor_id","curso_id"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="curso_id", type="integer", example=3),
     *           @OA\Property(property="semestre", type="string", example="2025-1")
     *         ))
     *     ),
     *     @OA\Response(response=404, description="Profesor no encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerCursosProfesor() {}

    /**
     * @OA\Post(
     *     path="/api/profesores/{id}/cursos",
     *     summary="Asignar un curso a un profesor",
     *     tags={"Asignación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *           type="object",
     *           required={"profesor_id","curso_id"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="curso_id", type="integer", example=3),
     *           @OA\Property(property="semestre", type="string", example="2025-1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Curso asignado correctamente",
     *         @OA\JsonContent(
     *           type="object",
     *           required={"profesor_id","curso_id"},
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="curso_id", type="integer", example=3),
     *           @OA\Property(property="semestre", type="string", example="2025-1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflicto de horario",
     *         @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="mensaje", type="string", example="Existe un conflicto de horario"),
     *           @OA\Property(property="detalles", type="object",
     *             @OA\Property(property="profesor_id", type="integer", example=2),
     *             @OA\Property(property="dia", type="string", example="Lunes"),
     *             @OA\Property(property="hora_inicio", type="string", example="08:00"),
     *             @OA\Property(property="hora_fin", type="string", example="10:00"),
     *             @OA\Property(property="conflicto_con", type="string", example="Curso de Física")
     *           )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=404, description="Profesor o curso no encontrados"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function asignarCursoProfesor() {}

    /**
     * @OA\Delete(
     *     path="/api/profesores/{id}/cursos/{curso_id}",
     *     summary="Eliminar asignación de curso a profesor",
     *     tags={"Asignación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="curso_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Asignación eliminada"),
     *     @OA\Response(response=404, description="Profesor, curso o asignación no encontrados"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function eliminarAsignacionCurso() {}

    /**
     * @OA\Post(
     *     path="/api/verificar-conflicto",
     *     summary="Verificar si existe un conflicto de horario",
     *     tags={"Asignación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *           type="object",
     *           required={"profesor_id","curso_id","horario_id"},
     *           @OA\Property(property="profesor_id", type="integer", example=2),
     *           @OA\Property(property="curso_id", type="integer", example=3),
     *           @OA\Property(property="horario_id", type="integer", example=4)
     *         )
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
     *         @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="mensaje", type="string", example="Existe un conflicto de horario"),
     *           @OA\Property(property="detalles", type="object",
     *             @OA\Property(property="profesor_id", type="integer", example=2),
     *             @OA\Property(property="dia", type="string", example="Lunes"),
     *             @OA\Property(property="hora_inicio", type="string", example="08:00"),
     *             @OA\Property(property="hora_fin", type="string", example="10:00"),
     *             @OA\Property(property="conflicto_con", type="string", example="Curso de Física")
     *           )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function verificarConflictoHorario() {}
}

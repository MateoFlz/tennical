<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="ReporteConflictos",
 *   type="object",
 *   @OA\Property(property="total_conflictos", type="integer", example=2),
 *   @OA\Property(property="conflictos", type="array", 
 *     @OA\Items(
 *       type="object",
 *       @OA\Property(property="id", type="integer", example=1),
 *       @OA\Property(property="profesor_id", type="integer", example=2),
 *       @OA\Property(property="curso1_id", type="integer", example=3),
 *       @OA\Property(property="curso1_nombre", type="string", example="Matemáticas Avanzadas"),
 *       @OA\Property(property="curso2_id", type="integer", example=5),
 *       @OA\Property(property="curso2_nombre", type="string", example="Física Básica"),
 *       @OA\Property(property="dia", type="string", example="Lunes"),
 *       @OA\Property(property="horario1", type="object",
 *         @OA\Property(property="hora_inicio", type="string", example="08:00"),
 *         @OA\Property(property="hora_fin", type="string", example="10:00")
 *       ),
 *       @OA\Property(property="horario2", type="object",
 *         @OA\Property(property="hora_inicio", type="string", example="09:00"),
 *         @OA\Property(property="hora_fin", type="string", example="11:00")
 *       )
 *     )
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="VerificacionConflicto",
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
 *
 * @OA\Schema(
 *   schema="ResolucionConflicto",
 *   type="object",
 *   @OA\Property(property="success", type="boolean", example=true),
 *   @OA\Property(property="mensaje", type="string", example="Conflicto resuelto correctamente"),
 *   @OA\Property(property="accion_tomada", type="string", example="cambiar_horario"),
 *   @OA\Property(property="detalles", type="object")
 * )
 */

class ConflictoEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/conflictos",
     *     summary="Obtener todos los conflictos de horarios existentes",
     *     tags={"Conflictos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de conflictos",
     *         @OA\JsonContent(ref="#/components/schemas/ReporteConflictos")
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function listarConflictos() {}

    /**
     * @OA\Get(
     *     path="/api/profesores/{id}/conflictos",
     *     summary="Obtener conflictos de horario de un profesor",
     *     tags={"Conflictos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Conflictos del profesor",
     *         @OA\JsonContent(ref="#/components/schemas/ReporteConflictos")
     *     ),
     *     @OA\Response(response=404, description="Profesor no encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerConflictosProfesor() {}

    /**
     * @OA\Get(
     *     path="/api/cursos/{id}/conflictos",
     *     summary="Obtener conflictos de horario de un curso",
     *     tags={"Conflictos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Conflictos del curso",
     *         @OA\JsonContent(ref="#/components/schemas/ReporteConflictos")
     *     ),
     *     @OA\Response(response=404, description="Curso no encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerConflictosCurso() {}

    /**
     * @OA\Post(
     *     path="/api/resolver-conflicto/{conflicto_id}",
     *     summary="Resolver un conflicto de horario específico",
     *     tags={"Conflictos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="conflicto_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="accion", type="string", enum={"reasignar_profesor", "cambiar_horario", "cancelar_asignacion"}, example="cambiar_horario"),
     *             @OA\Property(property="profesor_id", type="integer", example=3, description="Requerido para reasignar_profesor y cancelar_asignacion"),
     *             @OA\Property(property="curso_id", type="integer", example=5, description="Requerido para reasignar_profesor y cancelar_asignacion"),
     *             @OA\Property(property="horario_id", type="integer", example=7, description="Requerido para cambiar_horario"),
     *             @OA\Property(property="dia", type="string", example="Martes", description="Requerido para cambiar_horario"),
     *             @OA\Property(property="hora_inicio", type="string", example="14:00", description="Requerido para cambiar_horario"),
     *             @OA\Property(property="hora_fin", type="string", example="16:00", description="Requerido para cambiar_horario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Conflicto resuelto",
     *         @OA\JsonContent(ref="#/components/schemas/ResolucionConflicto")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos o acción no permitida"),
     *     @OA\Response(response=404, description="Conflicto no encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function resolverConflicto() {}
}

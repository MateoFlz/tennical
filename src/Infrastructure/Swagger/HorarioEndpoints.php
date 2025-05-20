<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

class HorarioEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/horarios",
     *     summary="Listar todos los horarios",
     *     tags={"Horario"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de horarios",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Horario"))
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function listarHorarios() {}

    /**
     * @OA\Post(
     *     path="/api/horarios",
     *     summary="Crear un nuevo horario",
     *     tags={"Horario"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Horario creado",
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function crearHorario() {}

    /**
     * @OA\Get(
     *     path="/api/horarios/{id}",
     *     summary="Obtener un horario por ID",
     *     tags={"Horario"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Horario encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerHorario() {}

    /**
     * @OA\Put(
     *     path="/api/horarios/{id}",
     *     summary="Actualizar un horario",
     *     tags={"Horario"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function actualizarHorario() {}

    /**
     * @OA\Delete(
     *     path="/api/horarios/{id}",
     *     summary="Eliminar un horario",
     *     tags={"Horario"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Horario eliminado"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function eliminarHorario() {}
}

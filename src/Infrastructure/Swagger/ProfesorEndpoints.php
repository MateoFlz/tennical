<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

class ProfesorEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/profesores",
     *     summary="Listar todos los profesores",
     *     tags={"Profesor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de profesores",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Profesor"))
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function listarProfesores() {}

    /**
     * @OA\Post(
     *     path="/api/profesores",
     *     summary="Crear un nuevo profesor",
     *     tags={"Profesor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Profesor")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Profesor creado",
     *         @OA\JsonContent(ref="#/components/schemas/Profesor")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function crearProfesor() {}

    /**
     * @OA\Get(
     *     path="/api/profesores/{id}",
     *     summary="Obtener un profesor por ID",
     *     tags={"Profesor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Profesor encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Profesor")
     *     ),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerProfesor() {}

    /**
     * @OA\Put(
     *     path="/api/profesores/{id}",
     *     summary="Actualizar un profesor",
     *     tags={"Profesor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Profesor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profesor actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Profesor")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function actualizarProfesor() {}

    /**
     * @OA\Delete(
     *     path="/api/profesores/{id}",
     *     summary="Eliminar un profesor",
     *     tags={"Profesor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Profesor eliminado"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function eliminarProfesor() {}
}

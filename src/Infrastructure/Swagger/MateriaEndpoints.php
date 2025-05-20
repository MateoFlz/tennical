<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

class MateriaEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/materias",
     *     summary="Listar todas las materias",
     *     tags={"Materia"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de materias",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Materia"))
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function listarMaterias() {}

    /**
     * @OA\Post(
     *     path="/api/materias",
     *     summary="Crear una nueva materia",
     *     tags={"Materia"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Materia creada",
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function crearMateria() {}

    /**
     * @OA\Get(
     *     path="/api/materias/{id}",
     *     summary="Obtener una materia por ID",
     *     tags={"Materia"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Materia encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(response=404, description="No encontrada"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerMateria() {}

    /**
     * @OA\Put(
     *     path="/api/materias/{id}",
     *     summary="Actualizar una materia",
     *     tags={"Materia"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Materia actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=404, description="No encontrada"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function actualizarMateria() {}

    /**
     * @OA\Delete(
     *     path="/api/materias/{id}",
     *     summary="Eliminar una materia",
     *     tags={"Materia"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Materia eliminada"),
     *     @OA\Response(response=404, description="No encontrada"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function eliminarMateria() {}
}

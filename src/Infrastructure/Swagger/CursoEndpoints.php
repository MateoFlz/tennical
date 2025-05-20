<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

class CursoEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/cursos",
     *     summary="Listar todos los cursos",
     *     tags={"Curso"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cursos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Curso"))
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function listarCursos() {}

    /**
     * @OA\Post(
     *     path="/api/cursos",
     *     summary="Crear un nuevo curso",
     *     tags={"Curso"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Curso")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Curso creado",
     *         @OA\JsonContent(ref="#/components/schemas/Curso")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function crearCurso() {}

    /**
     * @OA\Get(
     *     path="/api/cursos/{id}",
     *     summary="Obtener un curso por ID",
     *     tags={"Curso"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Curso encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Curso")
     *     ),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function obtenerCurso() {}

    /**
     * @OA\Put(
     *     path="/api/cursos/{id}",
     *     summary="Actualizar un curso",
     *     tags={"Curso"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Curso")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Curso actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Curso")
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function actualizarCurso() {}

    /**
     * @OA\Delete(
     *     path="/api/cursos/{id}",
     *     summary="Eliminar un curso",
     *     tags={"Curso"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Curso eliminado"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function eliminarCurso() {}
}

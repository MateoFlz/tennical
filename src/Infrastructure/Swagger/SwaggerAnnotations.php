<?php
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Gestión de Horarios",
 *     description="Documentación OpenAPI para la API de gestión de horarios, cursos, profesores y autenticación."
 * )
 * @OA\Server(
 *     url="http://localhost:8080",
 *     description="Servidor local Docker"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

/**
 * @OA\Get(
 *     path="/swagger-ping",
 *     summary="Ping para forzar path item (dummy, ignorar)",
 *     @OA\Response(response=200, description="pong")
 * )
 */

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

/**
 * @OA\Schema(
 *   schema="Curso",
 *   type="object",
 *   required={"nombre"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nombre", type="string", example="Matemáticas"),
 *   @OA\Property(property="descripcion", type="string", example="Curso de álgebra básica")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Profesor",
 *   type="object",
 *   required={"nombre"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *   @OA\Property(property="email", type="string", example="juan@demo.com")
 * )
 */

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

namespace App\Infrastructure\Swagger;

class SwaggerAnnotations {}


// Esquema de login
/**
 * @OA\Schema(
 *   schema="LoginRequest",
 *   type="object",
 *   required={"username","password"},
 *   @OA\Property(property="username", type="string", example="admin"),
 *   @OA\Property(property="password", type="string", example="admin123")
 * )
 *
 * @OA\Schema(
 *   schema="LoginResponse",
 *   type="object",
 *   @OA\Property(property="success", type="boolean", example=true),
 *   @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
 * )
 */

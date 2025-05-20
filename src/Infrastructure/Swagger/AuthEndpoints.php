<?php

namespace App\Infrastructure\Swagger;

use OpenApi\Annotations as OA;

class AuthEndpoints
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Autenticación y obtención de JWT",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token JWT generado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
     *             @OA\Property(property="expires_at", type="string", format="date-time", example="2023-12-31T23:59:59Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciales inválidas")
     * )
     */
    public function login() {}
}

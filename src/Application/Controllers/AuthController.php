<?php
namespace App\Application\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

class AuthController
{

    public function login(Request $request, Response $response): Response
    {
        try {
            $params = (array)$request->getParsedBody();
            $username = $params['username'] ?? '';
            $password = $params['password'] ?? '';

            $demoUser = 'admin';
            $demoPass = 'admin123';
            if ($username !== $demoUser || $password !== $demoPass) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'Credenciales inválidas'
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }


        $now = time();
        $exp = $now + ((int)($_ENV['JWT_EXPIRATION'] ?? 3600));
        $payload = [
            'sub' => $username,
            'iat' => $now,
            'exp' => $exp
        ];
        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        $response->getBody()->write(json_encode([
            'success' => true,
            'token' => $jwt
        ]));
        return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Error interno del servidor'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}

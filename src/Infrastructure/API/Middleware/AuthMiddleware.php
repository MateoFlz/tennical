<?php
namespace App\Infrastructure\API\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Permitir preflight CORS sin autenticación
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return $handler->handle($request);
        }

        $path = $request->getUri()->getPath();
        if ($path === '/api/login') {
            return $handler->handle($request);
        }
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $this->unauthorizedResponse(new \Slim\Psr7\Response());
        }
        $token = $matches[1];
        $secret = $_ENV['JWT_SECRET'] ?? 'secret';
        try {
            JWT::decode($token, new Key($secret, 'HS256'));
        } catch (\Exception $e) {
            return $this->unauthorizedResponse(new \Slim\Psr7\Response());
        }
        return $handler->handle($request);
    }
    private function unauthorizedResponse(Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'success' => false,
            'error' => 'Unauthorized'
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}

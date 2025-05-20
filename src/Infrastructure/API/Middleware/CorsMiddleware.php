<?php

namespace App\Infrastructure\API\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * Middleware para habilitar CORS en Slim API.
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Procesa la petición y agrega los headers CORS necesarios.
     *
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function process(Request $request, Handler $handler): Response
    {
        $response = $handler->handle($request);
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type, X-Requested-With')
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        // Si es preflight, devolver respuesta inmediata
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return $response->withStatus(200);
        }
        return $response;
    }
}

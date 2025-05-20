<?php

namespace App\Infrastructure\API\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * Middleware para habilitar CORS en Slim API con implementación directa.
 */
class DirectCorsMiddleware implements MiddlewareInterface
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
        // Para peticiones OPTIONS (preflight), respondemos inmediatamente
        if ($request->getMethod() === 'OPTIONS') {
            $response = new \Slim\Psr7\Response();
            return $this->addCorsHeaders($response);
        }
        
        // Para otras peticiones, procesamos normalmente y añadimos headers
        $response = $handler->handle($request);
        return $this->addCorsHeaders($response);
    }
    
    /**
     * Añade los headers CORS a cualquier respuesta
     * 
     * @param Response $response
     * @return Response
     */
    private function addCorsHeaders(Response $response): Response
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-HTTP-Method-Override')
            ->withHeader('Access-Control-Max-Age', '86400') // 24 horas
            ->withStatus($response->getStatusCode());
    }
}

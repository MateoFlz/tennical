<?php
declare(strict_types=1);

namespace App\Infrastructure\API;

use App\Domain\Exceptions\NotFoundException;
use App\Infrastructure\Security\Authentication;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;


class Router
{
    private array $routes = [];
    private bool $requireAuthentication = true;
    

    public function get(string $route, array $handler, bool $public = false): void
    {
        $this->addRoute('GET', $route, $handler, $public);
    }
    

    public function post(string $route, array $handler, bool $public = false): void
    {
        $this->addRoute('POST', $route, $handler, $public);
    }
    

    public function put(string $route, array $handler, bool $public = false): void
    {
        $this->addRoute('PUT', $route, $handler, $public);
    }
    

    public function delete(string $route, array $handler, bool $public = false): void
    {
        $this->addRoute('DELETE', $route, $handler, $public);
    }
    

    private function addRoute(string $method, string $route, array $handler, bool $public): void
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'handler' => $handler,
            'public' => $public
        ];
    }
    

    public function dispatch(): void
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['route'], [
                    'handler' => $route['handler'],
                    'public' => $route['public']
                ]);
            }
        });
        
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        $pos = strpos($uri, '?');
        if ($pos !== false) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new NotFoundException("Ruta no encontrada: $uri");
                
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                throw new NotFoundException(
                    "Método no permitido. Métodos permitidos: " . implode(', ', $allowedMethods)
                );
                
            case Dispatcher::FOUND:
                $handler = $routeInfo[1]['handler'];
                $params = $routeInfo[2];
                $public = $routeInfo[1]['public'];
                
                if (!$public && $this->requireAuthentication) {
                    $auth = new Authentication();
                    $auth->verifyToken();
                }
                
                $controller = new $handler[0]();
                $method = $handler[1];
                
                call_user_func_array([$controller, $method], $params);
                break;
        }
    }
    

    public function disableAuthentication(): void
    {
        $this->requireAuthentication = false;
    }
}

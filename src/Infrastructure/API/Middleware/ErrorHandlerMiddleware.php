<?php
declare(strict_types=1);

namespace App\Infrastructure\API\Middleware;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Infrastructure\API\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (EntityNotFoundException $e) {
            return $this->response->error($e->getMessage(), 404)->send();
        } catch (ValidationException $e) {
            return $this->response->error($e->getMessage(), 422, $e->getErrors())->send();
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            
            $message = $_ENV['APP_ENV'] === 'production' 
                ? 'Error interno del servidor' 
                : 'Error: ' . $e->getMessage();
            
            return $this->response->error($message, 500)->send();
        }
    }
}

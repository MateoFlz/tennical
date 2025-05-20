<?php
namespace App\Infrastructure\API\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Respect\Validation\Validator as v;

class ValidationMiddleware
{
    private array $rules;
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $data = $request->getParsedBody();
        $errors = [];
        foreach ($this->rules as $field => $validator) {
            if (!isset($data[$field]) || !$validator->validate($data[$field])) {
                $errors[$field] = 'Invalid value';
            }
        }
        if (!empty($errors)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'success' => false,
                'errors' => $errors
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        }
        return $handler->handle($request);
    }
}

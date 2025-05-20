<?php
declare(strict_types=1);

namespace App\Application\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Services\HorarioService;
use App\Infrastructure\Persistence\Eloquent\HorarioRepository;
use App\Infrastructure\API\Response as ApiResponse;

class HorarioController
{
    private HorarioService $service;
    private ApiResponse $apiResponse;

    public function __construct(HorarioService $service, ApiResponse $apiResponse)
    {
        $this->service = $service;
        $this->apiResponse = $apiResponse;
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        try {
            $result = $this->service->getAllHorarios();
            $response->getBody()->write(
                json_encode(['success' => true, 'data' => $result])
            );
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(
                json_encode(['success' => false, 'error' => 'Error interno del servidor'])
            );
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $result = $this->service->getHorarioById($id);
            
            if ($result) {
                $response->getBody()->write(
                    json_encode(['success' => true, 'data' => $result])
                );
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $response->getBody()->write(
                    json_encode(['success' => false, 'error' => 'Horario no encontrado'])
                );
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        } catch (\Throwable $e) {
            $response->getBody()->write(
                json_encode(['success' => false, 'error' => 'Error interno del servidor'])
            );
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        try {
            $data = $request->getParsedBody();
            $result = $this->service->createHorario($data);
            
            if (isset($result['success']) && !$result['success']) {
                $response->getBody()->write(
                    json_encode(['success' => false, 'error' => $result['error'] ?? 'Error de validación'])
                );
                return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
            }
            
            $response->getBody()->write(
                json_encode(['success' => true, 'data' => $result['data'] ?? $result])
            );
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            
        } catch (\Throwable $e) {
            $response->getBody()->write(
                json_encode(['success' => false, 'error' => 'Error interno del servidor: ' . $e->getMessage()])
            );
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $data = $request->getParsedBody();
            $result = $this->service->updateHorario($id, $data);
            
            if ($result) {
                $response->getBody()->write(
                    json_encode(['success' => true, 'data' => $result])
                );
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $response->getBody()->write(
                    json_encode(['success' => false, 'error' => 'Horario no encontrado'])
                );
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        } catch (\Throwable $e) {
            $response->getBody()->write(
                json_encode(['success' => false, 'error' => 'Error interno del servidor'])
            );
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $this->service->deleteHorario($id);
            $response->getBody()->write(
                json_encode(['success' => true, 'data' => ['deleted' => true]])
            );
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(
                json_encode(['success' => false, 'error' => 'Error interno del servidor'])
            );
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}

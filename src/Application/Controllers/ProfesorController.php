<?php
declare(strict_types=1);

namespace App\Application\Controllers;

use App\Domain\Services\ProfesorService;
use App\Infrastructure\Persistence\Eloquent\ProfesorRepository;
use App\Infrastructure\API\Response;

class ProfesorController
{
    private ProfesorService $service;
    private Response $response;

    public function __construct(ProfesorService $service, Response $response)
    {
        $this->service = $service;
        $this->response = $response;
    }

    public function index($request, $response, $args = []): \App\Infrastructure\API\Response
    {
        try {
            $result = $this->service->getAllProfesores();
            return $this->response->success($result)->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }

    public function show($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $result = $this->service->getProfesorById($id);
            if ($result) {
                return $this->response->success($result)->send();
            } else {
                return $this->response->error('Profesor no encontrado', 404)->send();
            }
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }

    public function store($request, $response, $args = []): \App\Infrastructure\API\Response
    {
        try {
            $data = $request->getParsedBody();
            $result = $this->service->createProfesor($data);
            return $this->response->success($result, 201)->send();
        } catch (\App\Domain\Exceptions\DuplicateProfesorException $e) {
            return $this->response->error($e->getMessage(), 409)->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor: ' . $e->getMessage(), 500)->send();
        }
    }

    public function update($request, $response, $args = []): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $data = $request->getParsedBody();
            $result = $this->service->updateProfesor($id, $data);
            if ($result) {
                return $this->response->success($result, 200)->send();
            } else {
                return $this->response->error('Profesor no encontrado', 404)->send();
            }
        } catch (\App\Domain\Exceptions\DuplicateProfesorException $e) {
            return $this->response->error($e->getMessage(), 409)->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor: ' . $e->getMessage(), 500)->send();
        }
    }

    public function delete($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $this->service->deleteProfesor($id);
            return $this->response->success(['deleted' => true])->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }
}

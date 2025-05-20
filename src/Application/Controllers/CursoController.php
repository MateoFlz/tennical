<?php
declare(strict_types=1);

namespace App\Application\Controllers;

use App\Domain\Services\CursoService;
use App\Infrastructure\Persistence\Eloquent\CursoRepository;
use App\Infrastructure\API\Response;

class CursoController
{
    private CursoService $service;
    private Response $response;

    public function __construct(CursoService $service, Response $response)
    {
        $this->service = $service;
        $this->response = $response;
    }


    public function index($request, $response, $args = []): \App\Infrastructure\API\Response
    {
        try {
            $result = $this->service->getAllCursos();
            return $this->response->success($result)->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }


    public function show($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $result = $this->service->getCursoById($id);
            if ($result) {
                return $this->response->success($result)->send();
            } else {
                return $this->response->error('Curso no encontrado', 404)->send();
            }
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }


    public function store($request, $response, $args = []): \App\Infrastructure\API\Response
    {
        try {
            $data = $request->getParsedBody();
            $result = $this->service->createCurso($data);
            return $this->response->success($result, 201)->send();
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }


    public function update($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $data = $request->getParsedBody();
            $result = $this->service->updateCurso($id, $data);
            if ($result) {
                return $this->response->success($result)->send();
            } else {
                return $this->response->error('Curso no encontrado', 404)->send();
            }
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }

    public function delete($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $this->service->deleteCurso($id);
            return $this->response->success(['deleted' => true])->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }
}

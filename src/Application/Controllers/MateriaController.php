<?php
declare(strict_types=1);

namespace App\Application\Controllers;

use App\Domain\Services\MateriaService;
use App\Infrastructure\Persistence\Eloquent\MateriaRepository;
use App\Infrastructure\API\Response;

class MateriaController
{
    private MateriaService $service;
    private Response $response;

    public function __construct(MateriaService $service, Response $response)
    {
        $this->service = $service;
        $this->response = $response;
    }


    public function index($request, $response, $args = []): \App\Infrastructure\API\Response
    {
        try {
            $result = $this->service->getAllMaterias();
            return $this->response->success($result)->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }


    public function show($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $result = $this->service->getMateriaById($id);
            if ($result) {
                return $this->response->success($result)->send();
            } else {
                return $this->response->error('Materia no encontrada', 404)->send();
            }
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }


    public function store($request, $response, $args = []): \App\Infrastructure\API\Response
    {
        try {
            $data = $request->getParsedBody();
            $result = $this->service->createMateria($data);
            return $this->response->success($result, 201)->send();
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }


    public function update($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $data = $request->getParsedBody();
            $result = $this->service->updateMateria($id, $data);
            if ($result) {
                return $this->response->success($result)->send();
            } else {
                return $this->response->error('Materia no encontrada', 404)->send();
            }
        } catch (\Throwable $e) {
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }

    /**
     * Elimina una materia por su ID
     * 
     * @param mixed $request Objeto de solicitud
     * @param mixed $response Objeto de respuesta
     * @param array $args Argumentos de la ruta
     * @return \App\Infrastructure\API\Response
     */
    public function delete($request, $response, $args): \App\Infrastructure\API\Response
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $this->service->deleteMateria($id);
            return $this->response->success(['deleted' => true])->send();
        } catch (\Exception $e) {
            // Verificar si es un error de relación
            if (strpos($e->getMessage(), 'siendo utilizada') !== false) {
                // Es un error de relación, devolver un mensaje específico
                return $this->response->error($e->getMessage(), 400)->send();
            }
            // Otro tipo de error
            return $this->response->error('Error interno del servidor', 500)->send();
        }
    }
}

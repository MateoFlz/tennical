<?php
namespace App\Application\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Services\ProfesorService;
use App\Infrastructure\Persistence\Eloquent\ProfesorRepository;

class ProfesorCursoController
{
    private ProfesorService $profesorService;
    public function __construct(ProfesorService $profesorService)
    {
        $this->profesorService = $profesorService;
    }

    public function asignarCursos(Request $request, Response $response, array $args): Response
    {
        $profesorId = (int)($args['id'] ?? 0);
        $data = $request->getParsedBody();
        $result = $this->profesorService->asignarCursos($profesorId, $data['cursos'] ?? []);
        if ($result['success']) {
            $response->getBody()->write(json_encode(['success' => true, 'data' => $result['data']]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['success' => false, 'error' => $result['error']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        }
    }
}

<?php
namespace App\Application\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Services\ConflictoService;


class ConflictoController
{
    private ConflictoService $conflictoService;
    
    public function __construct(ConflictoService $conflictoService)
    {
        $this->conflictoService = $conflictoService;
    }
    

    public function listarConflictos(Request $request, Response $response): Response
    {
        $conflictos = $this->conflictoService->getAllConflictos();
        
        $response->getBody()->write(json_encode([
            'total_conflictos' => count($conflictos),
            'conflictos' => $conflictos
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
    

    public function obtenerConflictosProfesor(Request $request, Response $response, array $args): Response
    {
        $profesorId = (int)($args['id'] ?? 0);
        $conflictos = $this->conflictoService->getConflictosByProfesorId($profesorId);
        
        $response->getBody()->write(json_encode([
            'total_conflictos' => count($conflictos),
            'conflictos' => $conflictos
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
    

    public function obtenerConflictosCurso(Request $request, Response $response, array $args): Response
    {
        $cursoId = (int)($args['id'] ?? 0);
        $conflictos = $this->conflictoService->getConflictosByCursoId($cursoId);
        
        $response->getBody()->write(json_encode([
            'total_conflictos' => count($conflictos),
            'conflictos' => $conflictos
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
    

    public function resolverConflicto(Request $request, Response $response, array $args): Response
    {
        $conflictoId = (int)($args['conflicto_id'] ?? 0);
        $data = $request->getParsedBody();
        

        if (!isset($data['accion']) || !in_array($data['accion'], ['reasignar_profesor', 'cambiar_horario', 'cancelar_asignacion'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Acción no válida. Debe ser una de: reasignar_profesor, cambiar_horario, cancelar_asignacion'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $result = $this->conflictoService->resolverConflicto($conflictoId, $data);
        
        if ($result['success']) {
            $response->getBody()->write(json_encode([
                'success' => true,
                'mensaje' => 'Conflicto resuelto correctamente',
                'accion_tomada' => $data['accion'],
                'detalles' => $result['data']
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $result['error']
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    

    public function verificarConflicto(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        

        if (!isset($data['profesor_id']) || !isset($data['curso_id']) || !isset($data['horario_id'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Datos incompletos. Se requiere profesor_id, curso_id y horario_id'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $verificacion = $this->conflictoService->verificarConflicto(
            (int)$data['profesor_id'],
            (int)$data['curso_id']
        );
        
        if ($verificacion['conflicto']) {
            $response->getBody()->write(json_encode($verificacion));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(409);
        } else {
            $response->getBody()->write(json_encode([
                'conflicto' => false,
                'mensaje' => 'No hay conflictos de horario'
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\HorarioRepositoryInterface;
use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Domain\Repositories\CursoRepositoryInterface;


class ConflictoService
{
    private HorarioRepositoryInterface $horarioRepository;
    private ProfesorRepositoryInterface $profesorRepository;
    private CursoRepositoryInterface $cursoRepository;

    public function __construct(
        HorarioRepositoryInterface $horarioRepository,
        ProfesorRepositoryInterface $profesorRepository,
        CursoRepositoryInterface $cursoRepository
    ) {
        $this->horarioRepository = $horarioRepository;
        $this->profesorRepository = $profesorRepository;
        $this->cursoRepository = $cursoRepository;
    }


    public function verificarConflicto(int $profesorId, int $cursoId): array
    {

        $horariosProfesor = $this->horarioRepository->findByProfesorId($profesorId);
        

        $horariosCurso = $this->horarioRepository->findByCursoId($cursoId);
        

        foreach ($horariosProfesor as $horarioProfesor) {
            foreach ($horariosCurso as $horarioCurso) {
                if ($this->hayConflictoEntreHorarios($horarioProfesor, $horarioCurso)) {
                    return [
                        'conflicto' => true,
                        'mensaje' => 'Existe un conflicto de horario',
                        'detalles' => [
                            'profesor_id' => $profesorId,
                            'curso_id' => $cursoId,
                            'dia' => $horarioProfesor->getDia(),
                            'hora_inicio' => $horarioProfesor->getHoraInicio(),
                            'hora_fin' => $horarioProfesor->getHoraFin(),
                            'conflicto_con' => $this->cursoRepository->findById($horarioCurso->getCursoId())->getNombre()
                        ]
                    ];
                }
            }
        }

        
        return [
            'conflicto' => false,
            'mensaje' => 'No hay conflictos de horario',
            'detalles' => null
        ];
    }
    

    private function hayConflictoEntreHorarios($horario1, $horario2): bool
    {

        if ($horario1->getDia() !== $horario2->getDia()) {
            return false;
        }
        

        $inicio1 = $this->convertirHoraAMinutos($horario1->getHoraInicio());
        $fin1 = $this->convertirHoraAMinutos($horario1->getHoraFin());
        $inicio2 = $this->convertirHoraAMinutos($horario2->getHoraInicio());
        $fin2 = $this->convertirHoraAMinutos($horario2->getHoraFin());
        


        if ($fin1 <= $inicio2 || $fin2 <= $inicio1) {
            return false;
        }
        
        return true;
    }
    

    private function convertirHoraAMinutos(string $hora): int
    {
        list($horas, $minutos) = explode(':', $hora);
        return (int)$horas * 60 + (int)$minutos;
    }
    

    public function getAllConflictos(): array
    {
        $resultado = [];
        $profesores = $this->profesorRepository->findAll();
        
        foreach ($profesores as $profesor) {
            $conflictosProfesor = $this->getConflictosByProfesorId($profesor->getId());
            if (!empty($conflictosProfesor)) {
                $resultado = array_merge($resultado, $conflictosProfesor);
            }
        }
        
        return $resultado;
    }
    

    public function getConflictosByProfesorId(int $profesorId): array
    {
        $resultado = [];
        $profesor = $this->profesorRepository->findById($profesorId);
        
        if (!$profesor) {
            return [];
        }
        

        $cursosAsignados = $this->profesorRepository->getCursosAsignados($profesorId);
        

        for ($i = 0; $i < count($cursosAsignados); $i++) {
            for ($j = $i + 1; $j < count($cursosAsignados); $j++) {
                $curso1 = $cursosAsignados[$i];
                $curso2 = $cursosAsignados[$j];
                

                $horariosCurso1 = $this->horarioRepository->findByCursoId($curso1['id']);
                $horariosCurso2 = $this->horarioRepository->findByCursoId($curso2['id']);
                

                foreach ($horariosCurso1 as $horario1) {
                    foreach ($horariosCurso2 as $horario2) {
                        if ($this->hayConflictoEntreHorarios($horario1, $horario2)) {
                            $resultado[] = [
                                'id' => count($resultado) + 1, // ID generado para el conflicto
                                'profesor_id' => $profesorId,
                                'curso1_id' => $curso1['id'],
                                'curso1_nombre' => $curso1['nombre'],
                                'curso2_id' => $curso2['id'],
                                'curso2_nombre' => $curso2['nombre'],
                                'dia' => $horario1->getDia(),
                                'horario1' => [
                                    'hora_inicio' => $horario1->getHoraInicio(),
                                    'hora_fin' => $horario1->getHoraFin()
                                ],
                                'horario2' => [
                                    'hora_inicio' => $horario2->getHoraInicio(),
                                    'hora_fin' => $horario2->getHoraFin()
                                ]
                            ];
                        }
                    }
                }
            }
        }
        
        return $resultado;
    }
    

    public function getConflictosByCursoId(int $cursoId): array
    {
        $resultado = [];
        $curso = $this->cursoRepository->find($cursoId);
        
        if (!$curso) {
            return [];
        }
        

        $profesoresAsignados = $this->cursoRepository->getProfesoresAsignados($cursoId);
        

        foreach ($profesoresAsignados as $profesor) {
            $conflictosProfesor = $this->getConflictosByProfesorId($profesor['id']);
            

            foreach ($conflictosProfesor as $conflicto) {
                if ($conflicto['curso1_id'] == $cursoId || $conflicto['curso2_id'] == $cursoId) {
                    $resultado[] = $conflicto;
                }
            }
        }
        
        return $resultado;
    }
    

    public function resolverConflicto(int $conflictoId, array $data): array
    {


        
        $accion = $data['accion'] ?? '';
        
        switch ($accion) {
            case 'reasignar_profesor':

                if (!isset($data['profesor_id']) || !isset($data['curso_id'])) {
                    return [
                        'success' => false,
                        'error' => 'Se requiere profesor_id y curso_id para reasignar un profesor'
                    ];
                }
                

                return [
                    'success' => true,
                    'data' => [
                        'mensaje' => 'Curso reasignado correctamente',
                        'profesor_id' => $data['profesor_id'],
                        'curso_id' => $data['curso_id']
                    ]
                ];
                
            case 'cambiar_horario':

                if (!isset($data['horario_id']) || !isset($data['dia']) || 
                    !isset($data['hora_inicio']) || !isset($data['hora_fin'])) {
                    return [
                        'success' => false,
                        'error' => 'Se requiere horario_id, dia, hora_inicio y hora_fin para cambiar un horario'
                    ];
                }
                

                return [
                    'success' => true,
                    'data' => [
                        'mensaje' => 'Horario cambiado correctamente',
                        'horario_id' => $data['horario_id'],
                        'dia' => $data['dia'],
                        'hora_inicio' => $data['hora_inicio'],
                        'hora_fin' => $data['hora_fin']
                    ]
                ];
                
            case 'cancelar_asignacion':

                if (!isset($data['profesor_id']) || !isset($data['curso_id'])) {
                    return [
                        'success' => false,
                        'error' => 'Se requiere profesor_id y curso_id para cancelar una asignación'
                    ];
                }
                

                return [
                    'success' => true,
                    'data' => [
                        'mensaje' => 'Asignación cancelada correctamente',
                        'profesor_id' => $data['profesor_id'],
                        'curso_id' => $data['curso_id']
                    ]
                ];
                
            default:
                return [
                    'success' => false,
                    'error' => 'Acción no válida'
                ];
        }
    }
}

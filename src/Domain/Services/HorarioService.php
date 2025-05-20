<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\Horario;
use App\Domain\Repositories\HorarioRepositoryInterface;

use App\Domain\Services\ProfesorDisponibleTrait;

class HorarioService
{
    use ProfesorDisponibleTrait;
    private HorarioRepositoryInterface $horarioRepository;
    public function __construct(HorarioRepositoryInterface $horarioRepository)
    {
        $this->horarioRepository = $horarioRepository;
    }
    public function getAllHorarios(): array
    {
        $horarios = $this->horarioRepository->findAll();
        return array_map(fn(Horario $h) => $h->toArray(), $horarios);
    }
    public function getHorarioById(int $id): ?array
    {
        $horario = $this->horarioRepository->findById($id);
        return $horario ? $horario->toArray() : null;
    }
    public function createHorario(array $data): array
    {

        $conflictosProfesor = $this->horarioRepository->findConflictos(
            $data['profesor_id'],
            $data['dia'],
            $data['hora_inicio'],
            $data['hora_fin'],
            null,
        );
        if (!empty($conflictosProfesor)) {
            return [
                'success' => false,
                'error' => 'Conflicto de horario para el profesor en ese rango de horas.'
            ];
        }

        $conflictosCurso = $this->horarioRepository->findConflictos(
            null,
            $data['dia'],
            $data['hora_inicio'],
            $data['hora_fin'],
            $data['curso_id'] ?? null
        );
        if (!empty($conflictosCurso)) {
            return [
                'success' => false,
                'error' => 'Conflicto de horario para el curso en ese rango de horas.'
            ];
        }

        // Verificar si el profesor está disponible en el horario solicitado
        if (method_exists($this, 'profesorDisponible')) {
            $disponible = $this->profesorDisponible(
                $data['profesor_id'], 
                $data['dia'], 
                $data['hora_inicio'], 
                $data['hora_fin']
            );
            
            if (!$disponible) {
                return [
                    'success' => false,
                    'error' => 'El profesor no está disponible en ese horario.'
                ];
            }
        }
        $horario = new Horario(
            $data['profesor_id'],
            $data['dia'],
            $data['hora_inicio'],
            $data['hora_fin'],
            $data['curso_id'] ?? null,
            $data['id'] ?? null
        );
        $this->horarioRepository->save($horario);
        return ['success' => true, 'data' => $horario->toArray()];
    }
    public function updateHorario(int $id, array $data): ?array
    {
        $horario = $this->horarioRepository->findById($id);
        if (!$horario) return null;
        $horarioActualizado = new Horario(
            $data['profesor_id'] ?? $horario->getProfesorId(),
            $data['dia'] ?? $horario->getDia(),
            $data['hora_inicio'] ?? $horario->getHoraInicio(),
            $data['hora_fin'] ?? $horario->getHoraFin(),
            $data['curso_id'] ?? $horario->getCursoId(),
            $id
        );
        $this->horarioRepository->update($horarioActualizado);
        return $horarioActualizado->toArray();
    }
    public function deleteHorario(int $id): void
    {
        $this->horarioRepository->delete($id);
    }
}

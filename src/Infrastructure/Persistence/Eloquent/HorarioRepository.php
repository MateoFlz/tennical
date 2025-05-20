<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Horario as HorarioEntity;
use App\Domain\Repositories\HorarioRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Horario as HorarioModel;

class HorarioRepository implements HorarioRepositoryInterface
{

    public function findConflictos(?int $profesorId, string $dia, string $horaInicio, string $horaFin, ?int $cursoId = null): array
    {
        $query = HorarioModel::where('dia', $dia)
            ->where(function($q) use ($horaInicio, $horaFin) {
                $q->where(function($q2) use ($horaInicio, $horaFin) {
                    $q2->where('hora_inicio', '<', $horaFin)
                       ->where('hora_fin', '>', $horaInicio);
                });
            });
        if ($profesorId !== null) {
            $query->where('profesor_id', $profesorId);
        }
        if ($cursoId !== null) {
            $query->where('curso_id', $cursoId);
        }
        return $query->get()->all();
    }

    public function findById(int $id): ?HorarioEntity
    {
        $model = HorarioModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }
    public function findAll(): array
    {
        $models = HorarioModel::all();
        return array_map([$this, 'mapToEntity'], $models->all());
    }
    public function save(HorarioEntity $horario): HorarioEntity
    {
        $model = new HorarioModel();
        $model->fill([
            'profesor_id' => $horario->getProfesorId(),
            'dia' => $horario->getDia(),
            'hora_inicio' => $horario->getHoraInicio(),
            'hora_fin' => $horario->getHoraFin(),
            'curso_id' => $horario->getCursoId(),
        ]);
        $model->save();
        $horario->setId($model->id);
        return $horario;
    }
    public function update(HorarioEntity $horario): void
    {
        $model = HorarioModel::find($horario->getId());
        if ($model) {
            $model->fill([
                'profesor_id' => $horario->getProfesorId(),
                'dia' => $horario->getDia(),
                'hora_inicio' => $horario->getHoraInicio(),
                'hora_fin' => $horario->getHoraFin(),
                'curso_id' => $horario->getCursoId(),
            ]);
            $model->save();
        }
    }
    public function delete(int $id): void
    {
        $model = HorarioModel::find($id);
        if ($model) {
            $model->delete();
        }
    }
    private function mapToEntity(HorarioModel $model): HorarioEntity
    {
        return new HorarioEntity(
            $model->profesor_id,
            $model->dia,
            $model->hora_inicio,
            $model->hora_fin,
            $model->curso_id,
            $model->id
        );
    }
    

    public function findByProfesorId(int $profesorId): array
    {
        $models = HorarioModel::where('profesor_id', $profesorId)->get();
        return array_map([$this, 'mapToEntity'], $models->all());
    }
    

    public function findByCursoId(int $cursoId): array
    {
        $models = HorarioModel::where('curso_id', $cursoId)->get();
        return array_map([$this, 'mapToEntity'], $models->all());
    }
    

    public function getDisponibilidadProfesor(int $profesorId): array
    {
        $horarios = HorarioModel::where('profesor_id', $profesorId)
            ->select('dia', 'hora_inicio', 'hora_fin')
            ->distinct()
            ->get()
            ->toArray();
            
        
        if (empty($horarios)) {
            return [
                ['dia' => 'Lunes', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                ['dia' => 'Martes', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                ['dia' => 'Miércoles', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                ['dia' => 'Jueves', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                ['dia' => 'Viernes', 'hora_inicio' => '08:00', 'hora_fin' => '18:00']
            ];
        }
        
      
        $disponibilidad = [];
        foreach ($horarios as $horario) {
            $disponibilidad[] = [
                'dia' => $horario['dia'],
                'hora_inicio' => $horario['hora_inicio'],
                'hora_fin' => $horario['hora_fin']
            ];
        }
        
        return $disponibilidad;
    }
}

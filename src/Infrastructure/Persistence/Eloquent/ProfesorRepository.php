<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Profesor as ProfesorEntity;
use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor as ProfesorModel;

class ProfesorRepository implements ProfesorRepositoryInterface
{
    public function findById(int $id): ?ProfesorEntity
    {
        $model = ProfesorModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }

    public function findAll(): array
    {
        $models = ProfesorModel::all();
        return array_map([$this, 'mapToEntity'], $models->all());
    }

    public function findByEmail(string $email): ?ProfesorEntity
    {
        $model = ProfesorModel::where('email', $email)->first();
        return $model ? $this->mapToEntity($model) : null;
    }

    public function findByDocumento(string $documento): ?ProfesorEntity
    {
        $model = ProfesorModel::where('documento', $documento)->first();
        return $model ? $this->mapToEntity($model) : null;
    }

    public function save(ProfesorEntity $profesor): ProfesorEntity
    {
        $model = new ProfesorModel();
        $model->fill([
            'nombre' => $profesor->getNombre(),
            'apellido' => $profesor->getApellido(),
            'email' => $profesor->getEmail(),
            'telefono' => $profesor->getTelefono(),
            'documento' => $profesor->getDocumento(),
        ]);
        $model->save();
        return $this->mapToEntity($model);
    }

    public function update(ProfesorEntity $profesor): void
    {
        $model = ProfesorModel::find($profesor->getId());
        if ($model) {
            $model->fill([
                'nombre' => $profesor->getNombre(),
                'apellido' => $profesor->getApellido(),
                'email' => $profesor->getEmail(),
                'telefono' => $profesor->getTelefono(),
            ]);
            $model->save();
        }
    }

    public function delete(int $id): void
    {
        $model = ProfesorModel::find($id);
        if ($model) {
            $model->delete();
        }
    }

    private function mapToEntity(ProfesorModel $model): ProfesorEntity
    {
        return new ProfesorEntity(
            $model->nombre,
            $model->apellido,
            $model->email,
            $model->telefono,
            $model->documento,
            $model->id
        );
    }
    

    public function asignarCursos(int $profesorId, array $cursosIds): array
    {
        $profesor = ProfesorModel::find($profesorId);
        if (!$profesor) {
            return [];
        }
        
        // Sincronizar los cursos (esto elimina los existentes y asigna los nuevos)
        // Si quisiéramos mantener los existentes, usaríamos syncWithoutDetaching
        $profesor->cursos()->sync($cursosIds);
        
        // Devolver información de los cursos asignados
        return $this->getCursosAsignados($profesorId);
    }
    

    public function getCursosAsignados(int $profesorId): array
    {
        $profesor = ProfesorModel::find($profesorId);
        if (!$profesor) {
            return [];
        }
        
        $cursos = $profesor->cursos()->get();
        $resultado = [];
        
        foreach ($cursos as $curso) {
            $resultado[] = [
                'id' => $curso->id,
                'nombre' => $curso->nombre,
                'descripcion' => $curso->descripcion,
                'pivot' => [
                    'profesor_id' => $profesorId,
                    'curso_id' => $curso->id,
                    'created_at' => $curso->pivot->created_at,
                    'updated_at' => $curso->pivot->updated_at
                ]
            ];
        }
        
        return $resultado;
    }
}

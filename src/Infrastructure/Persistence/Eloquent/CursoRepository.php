<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Curso as CursoEntity;
use App\Domain\Repositories\CursoRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Curso as CursoModel;

class CursoRepository implements CursoRepositoryInterface
{

    public function findById(int $id): ?CursoEntity
    {
        $model = CursoModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }
    

    public function find(int $id): ?CursoEntity
    {
        return $this->findById($id);
    }
    

    public function all(): array
    {
        return $this->findAll();
    }
    

    public function findAll(): array
    {
        $models = CursoModel::all();
        return array_map([$this, 'mapToEntity'], $models->all());
    }
    public function save(CursoEntity $curso): CursoEntity
    {
        $model = new CursoModel();
        $model->fill([
            'nombre' => $curso->getNombre(),
            'codigo' => $curso->getCodigo(),
            'materia_id' => $curso->getMateriaId(),
        ]);
        $model->save();
        $curso->setId($model->id);
        return $curso;
    }
    public function update(CursoEntity $curso): void
    {
        $model = CursoModel::find($curso->getId());
        if ($model) {
            $model->fill([
                'nombre' => $curso->getNombre(),
                'codigo' => $curso->getCodigo(),
                'materia_id' => $curso->getMateriaId(),
            ]);
            $model->save();
        }
    }
    public function delete(int $id): void
    {
        $model = CursoModel::find($id);
        if ($model) {
            $model->delete();
        }
    }
    private function mapToEntity(CursoModel $model): CursoEntity
    {
        return new CursoEntity(
            $model->nombre,
            $model->codigo,
            $model->materia_id,
            $model->id
        );
    }
    

    public function getProfesoresAsignados(int $cursoId): array
    {
        $curso = CursoModel::find($cursoId);
        if (!$curso) {
            return [];
        }
        
        $profesores = $curso->profesores()->get();
        $resultado = [];
        
        foreach ($profesores as $profesor) {
            $resultado[] = [
                'id' => $profesor->id,
                'nombre' => $profesor->nombre,
                'apellido' => $profesor->apellido,
                'email' => $profesor->email,
                'telefono' => $profesor->telefono,
                'pivot' => [
                    'profesor_id' => $profesor->id,
                    'curso_id' => $cursoId,
                    'created_at' => $profesor->pivot->created_at,
                    'updated_at' => $profesor->pivot->updated_at
                ]
            ];
        }
        
        return $resultado;
    }
}

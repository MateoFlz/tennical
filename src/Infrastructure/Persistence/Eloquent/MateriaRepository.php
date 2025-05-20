<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Materia as MateriaEntity;
use App\Domain\Repositories\MateriaRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Materia as MateriaModel;
use App\Infrastructure\Persistence\Eloquent\Models\Curso;

class MateriaRepository implements MateriaRepositoryInterface
{
    public function findById(int $id): ?MateriaEntity
    {
        $model = MateriaModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }
    public function findAll(): array
    {
        $models = MateriaModel::all();
        return array_map([$this, 'mapToEntity'], $models->all());
    }
    public function save(MateriaEntity $materia): MateriaEntity
    {
        $model = new MateriaModel();
        $model->fill([
            'nombre' => $materia->getNombre(),
            'codigo' => $materia->getCodigo(),
            'descripcion' => $materia->getDescripcion(),
        ]);
        $model->save();
        $materia->setId($model->id);
        return $materia;
    }
    public function update(MateriaEntity $materia): void
    {
        $model = MateriaModel::find($materia->getId());
        if ($model) {
            $model->fill([
                'nombre' => $materia->getNombre(),
                'codigo' => $materia->getCodigo(),
                'descripcion' => $materia->getDescripcion(),
            ]);
            $model->save();
        }
    }

    public function delete(int $id): void
    {
        $model = MateriaModel::find($id);
        if ($model) {
            $cursos = \App\Infrastructure\Persistence\Eloquent\Models\Curso::where('materia_id', $id)->count();
            
            if ($cursos > 0) {
                throw new \Exception("No se puede eliminar la materia porque está siendo utilizada por {$cursos} curso(s)");
            }
            
            $model->delete();
        }
    }
    private function mapToEntity(MateriaModel $model): MateriaEntity
    {
        return new MateriaEntity(
            $model->nombre,
            $model->codigo,
            $model->descripcion,
            $model->id
        );
    }
}

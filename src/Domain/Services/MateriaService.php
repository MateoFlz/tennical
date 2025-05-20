<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\Materia;
use App\Domain\Repositories\MateriaRepositoryInterface;

class MateriaService
{
    private MateriaRepositoryInterface $materiaRepository;
    public function __construct(MateriaRepositoryInterface $materiaRepository)
    {
        $this->materiaRepository = $materiaRepository;
    }
    public function getAllMaterias(): array
    {
        $materias = $this->materiaRepository->findAll();
        return array_map(fn(Materia $m) => $m->toArray(), $materias);
    }
    public function getMateriaById(int $id): ?array
    {
        $materia = $this->materiaRepository->findById($id);
        return $materia ? $materia->toArray() : null;
    }
    public function createMateria(array $data): array
    {
        $materia = new Materia($data['nombre'], $data['codigo'], $data['descripcion'] ?? null);
        $this->materiaRepository->save($materia);
        return $materia->toArray();
    }
    public function updateMateria(int $id, array $data): ?array
    {
        $materia = $this->materiaRepository->findById($id);
        if (!$materia) return null;
        $materiaActualizada = new Materia(
            $data['nombre'] ?? $materia->getNombre(),
            $data['codigo'] ?? $materia->getCodigo(),
            $data['descripcion'] ?? $materia->getDescripcion(),
            $id
        );
        $this->materiaRepository->update($materiaActualizada);
        return $materiaActualizada->toArray();
    }
    public function deleteMateria(int $id): void
    {
        $this->materiaRepository->delete($id);
    }
}

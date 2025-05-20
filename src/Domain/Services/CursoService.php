<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\Curso;
use App\Domain\Repositories\CursoRepositoryInterface;

class CursoService
{
    private CursoRepositoryInterface $cursoRepository;
    public function __construct(CursoRepositoryInterface $cursoRepository)
    {
        $this->cursoRepository = $cursoRepository;
    }
    public function getAllCursos(): array
    {
        $cursos = $this->cursoRepository->findAll();
        return array_map(fn(Curso $c) => $c->toArray(), $cursos);
    }
    public function getCursoById(int $id): ?array
    {
        $curso = $this->cursoRepository->findById($id);
        return $curso ? $curso->toArray() : null;
    }
    public function createCurso(array $data): array
    {
        $curso = new Curso(
            $data['nombre'], 
            $data['codigo'], 
            (int)$data['materia_id'],
            isset($data['id']) ? (int)$data['id'] : null
        );
        $this->cursoRepository->save($curso);
        return $curso->toArray();
    }
    public function updateCurso(int $id, array $data): ?array
    {
        $curso = $this->cursoRepository->findById($id);
        if (!$curso) return null;
        $cursoActualizado = new Curso(
            $data['nombre'] ?? $curso->getNombre(),
            $data['codigo'] ?? $curso->getCodigo(),
            $data['materia_id'] ?? $curso->getMateriaId(),
            $id
        );
        $this->cursoRepository->update($cursoActualizado);
        return $cursoActualizado->toArray();
    }
    public function deleteCurso(int $id): void
    {
        $this->cursoRepository->delete($id);
    }
}

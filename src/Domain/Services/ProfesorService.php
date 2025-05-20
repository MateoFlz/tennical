<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\Profesor;
use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Domain\Repositories\CursoRepositoryInterface;

class ProfesorService
{
    private ProfesorRepositoryInterface $profesorRepository;
    private CursoRepositoryInterface $cursoRepository;
    private ConflictoService $conflictoService;

    public function __construct(
        ProfesorRepositoryInterface $profesorRepository,
        CursoRepositoryInterface $cursoRepository,
        ConflictoService $conflictoService
    ) {
        $this->profesorRepository = $profesorRepository;
        $this->cursoRepository = $cursoRepository;
        $this->conflictoService = $conflictoService;
    }


    public function getAllProfesores(): array
    {
        $profesores = $this->profesorRepository->findAll();
        return array_map(fn(Profesor $p) => $p->toArray(), $profesores);
    }

    public function getProfesorById(int $id): ?array
    {
        $profesor = $this->profesorRepository->findById($id);
        return $profesor ? $profesor->toArray() : null;
    }


    public function createProfesor(array $data): array
    {
        if ($this->profesorRepository->findByEmail($data['email'])) {
            throw new \App\Domain\Exceptions\DuplicateProfesorException('El email ya está registrado para otro profesor.');
        }
        if ($this->profesorRepository->findByDocumento($data['documento'])) {
            throw new \App\Domain\Exceptions\DuplicateProfesorException('El documento ya está registrado para otro profesor.');
        }
        $profesor = new Profesor(
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'],
            $data['documento'],
            null
        );
        $profesor = $this->profesorRepository->save($profesor);
        return $profesor->toArray();
    }


    public function updateProfesor(int $id, array $data): ?array
    {
        $profesor = $this->profesorRepository->findById($id);
        if (!$profesor) return null;
        $nuevoEmail = $data['email'] ?? $profesor->getEmail();
        $nuevoDocumento = $data['documento'] ?? $profesor->getDocumento();
        $existeEmail = $this->profesorRepository->findByEmail($nuevoEmail);
        if ($existeEmail && $existeEmail->getId() !== $id) {
            throw new \App\Domain\Exceptions\DuplicateProfesorException('El email ya está registrado para otro profesor.');
        }
        
        $existeDocumento = $this->profesorRepository->findByDocumento($nuevoDocumento);
        if ($existeDocumento && $existeDocumento->getId() !== $id) {
            throw new \App\Domain\Exceptions\DuplicateProfesorException('El documento ya está registrado para otro profesor.');
        }
        $profesorActualizado = new Profesor(
            $data['nombre'] ?? $profesor->getNombre(),
            $data['apellido'] ?? $profesor->getApellido(),
            $nuevoEmail,
            $data['telefono'] ?? $profesor->getTelefono(),
            $nuevoDocumento,
            $id
        );
        $this->profesorRepository->update($profesorActualizado);
        return $profesorActualizado->toArray();
    }

    public function deleteProfesor(int $id): void
    {
        $this->profesorRepository->delete($id);
    }
    

    public function asignarCursos(int $profesorId, array $cursosIds): array
    {
        $profesor = $this->profesorRepository->findById($profesorId);
        if (!$profesor) {
            return [
                'success' => false,
                'data' => null,
                'error' => 'El profesor no existe'
            ];
        }
        
        $cursos = [];
        foreach ($cursosIds as $cursoId) {
            $curso = $this->cursoRepository->findById((int)$cursoId);
            if (!$curso) {
                return [
                    'success' => false,
                    'data' => null,
                    'error' => "El curso con ID {$cursoId} no existe"
                ];
            }
            $cursos[] = $curso;
        }
        
        foreach ($cursosIds as $cursoId) {
            $verificacion = $this->conflictoService->verificarConflicto($profesorId, (int)$cursoId);
            if ($verificacion['conflicto']) {
                return [
                    'success' => false,
                    'data' => null,
                    'error' => $verificacion['mensaje'],
                    'detalles' => $verificacion['detalles']
                ];
            }
        }
        
        $cursosAsignados = $this->profesorRepository->asignarCursos($profesorId, $cursosIds);
        
        return [
            'success' => true,
            'data' => $cursosAsignados,
            'error' => null
        ];
    }
}

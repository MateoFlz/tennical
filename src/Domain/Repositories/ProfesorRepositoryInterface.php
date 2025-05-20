<?php
namespace App\Domain\Repositories;

use App\Domain\Entities\Profesor;

interface ProfesorRepositoryInterface
{

    public function findById(int $id): ?Profesor;
    
    public function findAll(): array;

    public function findByEmail(string $email): ?Profesor;

    public function findByDocumento(string $documento): ?Profesor;

    public function save(Profesor $profesor): Profesor;

    public function update(Profesor $profesor): void;
    
    public function delete(int $id): void;
    
    public function asignarCursos(int $profesorId, array $cursosIds): array;
    
    public function getCursosAsignados(int $profesorId): array;
}

<?php
namespace App\Domain\Repositories;

use App\Domain\Entities\Horario;

interface HorarioRepositoryInterface
{
    public function findById(int $id): ?Horario;
    public function findAll(): array;
    public function save(Horario $horario): Horario;
    public function update(Horario $horario): void;
    public function delete(int $id): void;
    public function findConflictos(?int $profesorId, string $dia, string $horaInicio, string $horaFin, ?int $cursoId = null): array;
    public function findByProfesorId(int $profesorId): array;
    public function findByCursoId(int $cursoId): array;
    public function getDisponibilidadProfesor(int $profesorId): array;
}

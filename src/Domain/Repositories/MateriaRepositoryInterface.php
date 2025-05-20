<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Materia;

interface MateriaRepositoryInterface
{
    public function findById(int $id): ?Materia;
    
    public function findAll(): array;
    
    public function save(Materia $materia): Materia;
    
    public function update(Materia $materia): void;
    
    public function delete(int $id): void;
}

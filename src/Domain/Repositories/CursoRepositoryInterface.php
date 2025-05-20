<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Curso;

interface CursoRepositoryInterface
{
    public function all();
    public function find(int $id): ?Curso;
    public function save(Curso $curso): Curso;
    public function delete(int $id): void;
}

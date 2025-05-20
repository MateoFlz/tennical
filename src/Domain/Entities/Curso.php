<?php
declare(strict_types=1);

namespace App\Domain\Entities;

class Curso
{
    private ?int $id;
    private string $nombre;
    private string $codigo;
    private int $materiaId;

    public function __construct(string $nombre, string $codigo, int $materiaId, ?int $id = null)
    {
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->materiaId = $materiaId;
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getNombre(): string { return $this->nombre; }
    public function getCodigo(): string { return $this->codigo; }
    public function getMateriaId(): int { return $this->materiaId; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'materia_id' => $this->materiaId,
        ];
    }
}
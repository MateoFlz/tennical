<?php
declare(strict_types=1);

namespace App\Domain\Entities;


class Materia
{
    private ?int $id;
    private string $nombre;
    private string $codigo;
    private ?string $descripcion;

    
    public function __construct(string $nombre, string $codigo, ?string $descripcion = null, ?int $id = null)
    {
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->descripcion = $descripcion;
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getNombre(): string { return $this->nombre; }
    public function getCodigo(): string { return $this->codigo; }
    public function getDescripcion(): ?string { return $this->descripcion; }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion,
        ];
    }
}

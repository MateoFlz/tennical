<?php

declare(strict_types=1);

namespace App\Domain\Entities;


class Profesor
{

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'documento' => $this->documento,
        ];
    }


    private ?int $id;

    private string $nombre;

    private string $apellido;

    private string $email;

    private string $telefono;

    private string $documento;


    public function __construct(
        string $nombre,
        string $apellido,
        string $email,
        string $telefono,
        string $documento,
        ?int $id = null
    ) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->documento = $documento;
        $this->id = $id;
    }


    public function getId(): ?int { return $this->id; }

    public function getNombre(): string { return $this->nombre; }

    public function getApellido(): string { return $this->apellido; }

    public function getEmail(): string { return $this->email; }

    public function getTelefono(): string { return $this->telefono; }

    public function getDocumento(): string { return $this->documento; }
}

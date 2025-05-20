<?php
namespace App\Domain\Entities;

class Horario
{
    private int $profesorId;
    private string $dia;
    private string $horaInicio;
    private string $horaFin;
    private ?int $cursoId;
    private ?int $id;

    public function __construct(
        int $profesorId,
        string $dia,
        string $horaInicio,
        string $horaFin,
        ?int $cursoId = null,
        ?int $id = null
    ) {
        $this->profesorId = $profesorId;
        $this->dia = $dia;
        $this->horaInicio = $horaInicio;
        $this->horaFin = $horaFin;
        $this->cursoId = $cursoId;
        $this->id = $id;
    }
    public function getProfesorId(): int { return $this->profesorId; }
    public function getDia(): string { return $this->dia; }
    public function getHoraInicio(): string { return $this->horaInicio; }
    public function getHoraFin(): string { return $this->horaFin; }
    public function getCursoId(): ?int { return $this->cursoId; }
    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function toArray(): array {
        return [
            'id' => $this->id,
            'profesor_id' => $this->profesorId,
            'dia' => $this->dia,
            'hora_inicio' => $this->horaInicio,
            'hora_fin' => $this->horaFin,
            'curso_id' => $this->cursoId
        ];
    }
}

<?php
namespace App\Domain\Services;

use App\Domain\Repositories\ProfesorRepositoryInterface;

trait ProfesorDisponibleTrait
{

    public function profesorDisponible(int $profesorId, string $dia, string $horaInicio, string $horaFin): bool
    {
    
        $disponibilidad = $this->getDisponibilidad($profesorId);
        foreach ($disponibilidad as $bloque) {
            if ($bloque['dia'] === $dia && $bloque['hora_inicio'] <= $horaInicio && $bloque['hora_fin'] >= $horaFin) {
                return true;
            }
        }
        return false;
    }

    private function getDisponibilidad(int $profesorId): array
    {
        if ($profesorId === 1) {
            return [
                ['dia' => 'Lunes', 'hora_inicio' => '08:00', 'hora_fin' => '12:00']
            ];
        }
        return [];
    }
}

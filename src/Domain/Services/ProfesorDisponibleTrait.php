<?php
namespace App\Domain\Services;

use App\Domain\Repositories\HorarioRepositoryInterface;


trait ProfesorDisponibleTrait
{

    public function profesorDisponible(int $profesorId, string $dia, string $horaInicio, string $horaFin): bool
    {
        if (!isset($this->horarioRepository) || !($this->horarioRepository instanceof HorarioRepositoryInterface)) {
            throw new \RuntimeException('Este trait requiere que la clase que lo utiliza tenga una propiedad horarioRepository que implemente HorarioRepositoryInterface');
        }
        
        // Obtener todos los horarios asignados al profesor
        $horariosAsignados = $this->horarioRepository->findByProfesorId($profesorId);
        
        // Si no hay horarios asignados, el profesor está disponible
        if (empty($horariosAsignados)) {
            return true;
        }
        
        $horaInicioSolicitada = strtotime($horaInicio);
        $horaFinSolicitada = strtotime($horaFin);
        
        // Verificar si hay algún horario asignado que se superponga
        foreach ($horariosAsignados as $horario) {
            if ($horario->getDia() === $dia) {
                $inicioAsignado = strtotime($horario->getHoraInicio());
                $finAsignado = strtotime($horario->getHoraFin());
                
                // Verificar si hay superposición de horarios
                if ($horaInicioSolicitada < $finAsignado && $horaFinSolicitada > $inicioAsignado) {
                    return false; // Hay conflicto
                }
            }
        }
        
        // No hay conflictos, el profesor está disponible
        return true;
    }
}

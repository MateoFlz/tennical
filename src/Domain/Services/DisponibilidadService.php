<?php
namespace App\Domain\Services;

use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Domain\Repositories\HorarioRepositoryInterface;


class DisponibilidadService
{
    private ProfesorRepositoryInterface $profesorRepository;
    private HorarioRepositoryInterface $horarioRepository;
    

    public function __construct(
        ProfesorRepositoryInterface $profesorRepository,
        HorarioRepositoryInterface $horarioRepository
    ) {
        $this->profesorRepository = $profesorRepository;
        $this->horarioRepository = $horarioRepository;
    }
    

    public function setDisponibilidad(int $profesorId, array $disponibilidad): array
    {

        $profesor = $this->profesorRepository->findById($profesorId);
        if (!$profesor) {
            return [
                'success' => false,
                'error' => 'El profesor no existe',
                'data' => null
            ];
        }
        

        $errores = $this->validarEstructuraDisponibilidad($disponibilidad);
        if (!empty($errores)) {
            return [
                'success' => false,
                'error' => 'Datos de disponibilidad inválidos',
                'detalles' => $errores,
                'data' => null
            ];
        }
        

        try {


            

            $this->eliminarDisponibilidadAnterior($profesorId);
            

            $disponibilidadGuardada = $this->guardarNuevaDisponibilidad($profesorId, $disponibilidad);
            
            return [
                'success' => true,
                'data' => $disponibilidadGuardada
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error al guardar la disponibilidad: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
    

    private function validarEstructuraDisponibilidad(array $disponibilidad): array
    {
        $errores = [];
        $diasValidos = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        
        foreach ($disponibilidad as $index => $bloque) {
            if (!isset($bloque['dia']) || !isset($bloque['hora_inicio']) || !isset($bloque['hora_fin'])) {
                $errores[] = "El bloque {$index} no tiene todas las claves requeridas (dia, hora_inicio, hora_fin)";
                continue;
            }
            
            if (!in_array($bloque['dia'], $diasValidos)) {
                $errores[] = "El día '{$bloque['dia']}' en el bloque {$index} no es válido";
            }
            
            if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $bloque['hora_inicio'])) {
                $errores[] = "La hora de inicio '{$bloque['hora_inicio']}' en el bloque {$index} no tiene un formato válido (HH:MM)";
            }
            
            if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $bloque['hora_fin'])) {
                $errores[] = "La hora de fin '{$bloque['hora_fin']}' en el bloque {$index} no tiene un formato válido (HH:MM)";
            }
            
            if (isset($bloque['hora_inicio']) && isset($bloque['hora_fin']) && 
                $bloque['hora_inicio'] >= $bloque['hora_fin']) {
                $errores[] = "La hora de inicio debe ser anterior a la hora de fin en el bloque {$index}";
            }
        }
        
        return $errores;
    }
    

    private function eliminarDisponibilidadAnterior(int $profesorId): void
    {



        
        try {

            $horarios = $this->horarioRepository->findByProfesorId($profesorId);
            

            foreach ($horarios as $horario) {
                if ($horario->getCursoId() === null) {
                    $this->horarioRepository->delete($horario->getId());
                }
            }
        } catch (\Exception $e) {


            error_log('Error al eliminar disponibilidad anterior: ' . $e->getMessage());
        }
    }
    

    private function guardarNuevaDisponibilidad(int $profesorId, array $disponibilidad): array
    {
        $disponibilidadGuardada = [];
        


        
        foreach ($disponibilidad as $bloque) {

            $horario = new \App\Domain\Entities\Horario(
                $profesorId,
                $bloque['dia'],
                $bloque['hora_inicio'],
                $bloque['hora_fin'],
                null, // curso_id = null indica que es un registro de disponibilidad
                null  // id = null para que se genere uno nuevo
            );
            

            $horarioGuardado = $this->horarioRepository->save($horario);
            

            $disponibilidadGuardada[] = [
                'id' => $horarioGuardado->getId(),
                'dia' => $horarioGuardado->getDia(),
                'hora_inicio' => $horarioGuardado->getHoraInicio(),
                'hora_fin' => $horarioGuardado->getHoraFin()
            ];
        }
        
        return $disponibilidadGuardada;
    }
}

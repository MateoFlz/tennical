<?php
use Slim\Routing\RouteCollectorProxy;
use App\Application\Controllers\ProfesorController;
use App\Application\Controllers\MateriaController;
use App\Application\Controllers\CursoController;
use App\Application\Controllers\HorarioController;
use App\Application\Controllers\AuthController;
use App\Application\Controllers\DisponibilidadController;
use App\Application\Controllers\ProfesorCursoController;
use App\Application\Controllers\ConflictoController;

use App\Infrastructure\API\Middleware\AuthMiddleware;
use App\Infrastructure\API\Middleware\ValidationMiddleware;
use Respect\Validation\Validator as v;

$app->post('/api/login', [AuthController::class, 'login']);

$app->group('/api', function (RouteCollectorProxy $group) use ($app) {
    
    $group->get('/profesores', [ProfesorController::class, 'index']); // pública
    $group->get('/profesores/{id}', [ProfesorController::class, 'show']); // pública

    $profesorValidation = new ValidationMiddleware([
        'nombre' => v::stringType()->notEmpty(),
        'apellido' => v::stringType()->notEmpty(),
        'email' => v::email(),
        'telefono' => v::stringType()->notEmpty()
    ]);
    $auth = new AuthMiddleware();

    $group->post('/profesores', [ProfesorController::class, 'store'])
        ->add($profesorValidation);

    $group->post('/profesores/{id}/disponibilidad', [DisponibilidadController::class, 'setDisponibilidad'])
        ->add($auth);

    $group->post('/profesores/{id}/cursos', [ProfesorCursoController::class, 'asignarCursos'])
        ->add($auth);
    $group->put('/profesores/{id}', [ProfesorController::class, 'update'])
        ->add($profesorValidation);
    $group->delete('/profesores/{id}', [ProfesorController::class, 'delete'])
        ->add($auth);

    $materiaValidation = new ValidationMiddleware([
        'nombre' => v::stringType()->notEmpty(),
        'codigo' => v::stringType()->notEmpty(),
    ]);
    $group->get('/materias', [MateriaController::class, 'index']);
    $group->get('/materias/{id}', [MateriaController::class, 'show']);
    $group->post('/materias', [MateriaController::class, 'store'])
        ->add($materiaValidation);
    $group->put('/materias/{id}', [MateriaController::class, 'update'])
        ->add($materiaValidation);
    $group->delete('/materias/{id}', [MateriaController::class, 'delete'])
        ->add($auth);

    $cursoValidation = new ValidationMiddleware([
        'nombre' => v::stringType()->notEmpty(),
        'codigo' => v::stringType()->notEmpty(),
        'materia_id' => v::intType()->positive()
    ]);
    $group->get('/cursos', [CursoController::class, 'index']);
    $group->get('/cursos/{id}', [CursoController::class, 'show']);
    $group->post('/cursos', [CursoController::class, 'store'])
        ->add($cursoValidation);
    $group->put('/cursos/{id}', [CursoController::class, 'update'])
        ->add($cursoValidation);
    $group->delete('/cursos/{id}', [CursoController::class, 'delete'])
        ->add($auth);

    $horarioValidation = new ValidationMiddleware([
        'profesor_id' => v::intType()->positive(),
        'dia' => v::stringType()->notEmpty(),
        'hora_inicio' => v::regex('/^([01]\d|2[0-3]):[0-5]\d$/'),
        'hora_fin' => v::regex('/^([01]\d|2[0-3]):[0-5]\d$/'),
    ]);
    $group->get('/horarios', [HorarioController::class, 'index']);
    $group->get('/horarios/{id}', [HorarioController::class, 'show']);
    $group->post('/horarios', [HorarioController::class, 'store'])
        ->add($horarioValidation);
    $group->put('/horarios/{id}', [HorarioController::class, 'update'])
        ->add($horarioValidation);
    $group->delete('/horarios/{id}', [HorarioController::class, 'delete'])
        ->add($auth);
        
    $conflictoValidation = new ValidationMiddleware([
        'accion' => v::in(['reasignar_profesor', 'cambiar_horario', 'cancelar_asignacion']),
    ]);
    
    $group->get('/conflictos', [ConflictoController::class, 'listarConflictos'])
        ->add($auth);
    $group->get('/profesores/{id}/conflictos', [ConflictoController::class, 'obtenerConflictosProfesor'])
        ->add($auth);
    $group->get('/cursos/{id}/conflictos', [ConflictoController::class, 'obtenerConflictosCurso'])
        ->add($auth);
        
    $group->post('/resolver-conflicto/{conflicto_id}', [ConflictoController::class, 'resolverConflicto'])
        ->add($conflictoValidation);
        
    $verificarConflictoValidation = new ValidationMiddleware([
        'profesor_id' => v::intType()->positive(),
        'curso_id' => v::intType()->positive(),
        'horario_id' => v::optional(v::intType()->positive())
    ]);
    $group->post('/verificar-conflicto', [ConflictoController::class, 'verificarConflicto'])
        ->add($verificarConflictoValidation);
})->add(new \App\Infrastructure\API\Middleware\AuthMiddleware());

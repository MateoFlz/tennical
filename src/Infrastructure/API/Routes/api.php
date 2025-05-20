<?php
use App\Application\Controllers\ProfesorController;

$profesorController = new ProfesorController();

$router->get('/api/profesores', [ProfesorController::class, 'index']);
$router->get('/api/profesores/{id}', [ProfesorController::class, 'show']);
$router->post('/api/profesores', [ProfesorController::class, 'store']);
$router->put('/api/profesores/{id}', [ProfesorController::class, 'update']);
$router->delete('/api/profesores/{id}', [ProfesorController::class, 'delete']);

use App\Application\Controllers\MateriaController;
$router->get('/api/materias', [MateriaController::class, 'index']);
$router->get('/api/materias/{id}', [MateriaController::class, 'show']);
$router->post('/api/materias', [MateriaController::class, 'store']);
$router->put('/api/materias/{id}', [MateriaController::class, 'update']);
$router->delete('/api/materias/{id}', [MateriaController::class, 'delete']);

use App\Application\Controllers\CursoController;
$router->get('/api/cursos', [CursoController::class, 'index']);
$router->get('/api/cursos/{id}', [CursoController::class, 'show']);
$router->post('/api/cursos', [CursoController::class, 'store']);
$router->put('/api/cursos/{id}', [CursoController::class, 'update']);
$router->delete('/api/cursos/{id}', [CursoController::class, 'delete']);

use App\Application\Controllers\HorarioController;
$router->get('/api/horarios', [HorarioController::class, 'index']);
$router->get('/api/horarios/{id}', [HorarioController::class, 'show']);
$router->post('/api/horarios', [HorarioController::class, 'store']);
$router->put('/api/horarios/{id}', [HorarioController::class, 'update']);
$router->delete('/api/horarios/{id}', [HorarioController::class, 'delete']);

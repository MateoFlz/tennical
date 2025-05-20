<?php
use DI\Container;
use App\Domain\Services\MateriaService;
use App\Domain\Services\CursoService;
use App\Domain\Services\ProfesorService;
use App\Domain\Services\HorarioService;
use App\Domain\Services\ConflictoService;
use App\Domain\Services\DisponibilidadService;
use App\Infrastructure\Persistence\Eloquent\MateriaRepository;
use App\Infrastructure\Persistence\Eloquent\CursoRepository;
use App\Infrastructure\Persistence\Eloquent\ProfesorRepository;
use App\Infrastructure\Persistence\Eloquent\HorarioRepository;
use App\Infrastructure\API\Response;
use App\Domain\Repositories\MateriaRepositoryInterface;
use App\Domain\Repositories\CursoRepositoryInterface;
use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Domain\Repositories\HorarioRepositoryInterface;

return [

    MateriaRepositoryInterface::class => DI\get(MateriaRepository::class),
    CursoRepositoryInterface::class => DI\get(CursoRepository::class),
    ProfesorRepositoryInterface::class => DI\get(ProfesorRepository::class),
    HorarioRepositoryInterface::class => DI\get(HorarioRepository::class),
    
   
    MateriaRepository::class => DI\autowire(),
    CursoRepository::class => DI\autowire(),
    ProfesorRepository::class => DI\autowire(),
    HorarioRepository::class => DI\autowire(),

    
    MateriaService::class => DI\autowire()->constructorParameter('materiaRepository', DI\get(MateriaRepositoryInterface::class)),
    CursoService::class => DI\autowire()->constructorParameter('cursoRepository', DI\get(CursoRepositoryInterface::class)),
    
    
    ConflictoService::class => DI\autowire()
        ->constructorParameter('horarioRepository', DI\get(HorarioRepositoryInterface::class))
        ->constructorParameter('profesorRepository', DI\get(ProfesorRepositoryInterface::class))
        ->constructorParameter('cursoRepository', DI\get(CursoRepositoryInterface::class)),
    
    
    DisponibilidadService::class => DI\autowire()
        ->constructorParameter('profesorRepository', DI\get(ProfesorRepositoryInterface::class))
        ->constructorParameter('horarioRepository', DI\get(HorarioRepositoryInterface::class)),
    
    ProfesorService::class => DI\autowire()
        ->constructorParameter('profesorRepository', DI\get(ProfesorRepositoryInterface::class))
        ->constructorParameter('cursoRepository', DI\get(CursoRepositoryInterface::class))
        ->constructorParameter('conflictoService', DI\get(ConflictoService::class)),
        
    HorarioService::class => DI\autowire()->constructorParameter('horarioRepository', DI\get(HorarioRepositoryInterface::class)),

    
    Response::class => DI\autowire(),

    
    App\Application\Controllers\MateriaController::class => DI\autowire(),
    App\Application\Controllers\CursoController::class => DI\autowire(),
    App\Application\Controllers\ProfesorController::class => DI\autowire(),
    App\Application\Controllers\HorarioController::class => DI\autowire(),
];

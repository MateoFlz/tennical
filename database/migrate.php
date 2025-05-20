<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

$migrations = [
    'create_users_table.php',
    'create_profesores_table.php',
    'create_materias_table.php',
    'create_cursos_table.php',
    'create_horarios_table.php',
    'create_profesor_curso_table.php',
    'add_documento_to_profesores_table.php'
];

foreach ($migrations as $migration) {
    require_once __DIR__ . '/migrations/' . $migration;
    echo "Migración ejecutada: $migration\n";
}
echo "Todas las migraciones completadas.\n";

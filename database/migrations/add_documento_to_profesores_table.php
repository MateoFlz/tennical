<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;


if (!Capsule::schema()->hasColumn('profesores', 'documento')) {
    Capsule::schema()->table('profesores', function ($table) {
        $table->string('documento')->after('telefono');
    });
    echo "Columna 'documento' agregada a 'profesores'.\n";
} else {
    echo "La columna 'documento' ya existe en 'profesores'.\n";
}

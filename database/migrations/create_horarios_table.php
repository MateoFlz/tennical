<?php
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('horarios');
Capsule::schema()->create('horarios', function ($table) {
    $table->id();
    $table->unsignedBigInteger('profesor_id');
    $table->enum('dia', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']);
    $table->time('hora_inicio');
    $table->time('hora_fin');
    $table->unsignedBigInteger('curso_id')->nullable();
    $table->timestamps();
    $table->foreign('profesor_id')->references('id')->on('profesores');
    $table->foreign('curso_id')->references('id')->on('cursos');
    $table->unique(['profesor_id', 'dia', 'hora_inicio', 'hora_fin']);
    $table->unique(['curso_id', 'dia', 'hora_inicio', 'hora_fin']);
});

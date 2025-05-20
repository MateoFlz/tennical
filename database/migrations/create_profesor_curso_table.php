<?php
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('profesor_curso');
Capsule::schema()->create('profesor_curso', function ($table) {
    $table->unsignedBigInteger('profesor_id');
    $table->unsignedBigInteger('curso_id');
    $table->timestamps();
    $table->primary(['profesor_id', 'curso_id']);
    $table->foreign('profesor_id')->references('id')->on('profesores');
    $table->foreign('curso_id')->references('id')->on('cursos');
});

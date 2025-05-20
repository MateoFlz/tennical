<?php
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('cursos');
Capsule::schema()->create('cursos', function ($table) {
    $table->id();
    $table->string('nombre');
    $table->string('codigo')->unique();
    $table->unsignedBigInteger('materia_id');
    $table->timestamps();
    $table->foreign('materia_id')->references('id')->on('materias');
});

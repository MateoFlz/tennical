<?php
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('materias');
Capsule::schema()->create('materias', function ($table) {
    $table->id();
    $table->string('nombre');
    $table->string('codigo')->unique();
    $table->text('descripcion')->nullable();
    $table->timestamps();
});

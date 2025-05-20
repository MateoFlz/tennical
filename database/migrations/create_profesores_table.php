<?php
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('profesores');
Capsule::schema()->create('profesores', function ($table) {
    $table->id();
    $table->string('nombre');
    $table->string('apellido');
    $table->string('email')->unique();
    $table->string('telefono');
    $table->timestamps();
});

<?php
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('users');
Capsule::schema()->create('users', function ($table) {
    $table->id();
    $table->string('username')->unique();
    $table->string('password');
    $table->string('role')->default('user');
    $table->timestamps();
});

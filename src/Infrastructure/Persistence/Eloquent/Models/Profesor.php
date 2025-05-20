<?php
namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profesor extends Model
{
    protected $table = 'profesores';
    protected $fillable = ['nombre', 'apellido', 'email', 'telefono', 'documento'];

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'profesor_curso')->withTimestamps();
    }
}

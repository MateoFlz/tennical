<?php
namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['nombre', 'codigo', 'materia_id'];

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function profesores(): BelongsToMany
    {
        return $this->belongsToMany(Profesor::class, 'profesor_curso')->withTimestamps();
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }
}

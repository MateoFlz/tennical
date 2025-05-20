<?php
namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $table = 'materias';
    protected $fillable = ['nombre', 'codigo', 'descripcion'];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}

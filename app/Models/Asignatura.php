<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Catálogo de asignaturas. Evita repetir el nombre en cada nota y en cada
 * bloque de horario, y permite reutilizar la misma materia en varios cursos.
 */
class Asignatura extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Catálogo de especialidades instrumentales oficiales de la EBIM.
 */
class Especialidad extends Model
{
    /**
     * El plural en español no coincide con el que deduce Eloquent.
     */
    protected $table = 'especialidades';

    protected $fillable = [
        'nombre',
        'orden',
    ];

    public function estudiantes(): HasMany
    {
        return $this->hasMany(User::class, 'especialidad_id');
    }
}

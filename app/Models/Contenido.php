<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    protected $fillable = [
        'clave',
        'titulo',
        'cuerpo',
        'foto',
    ];

    /**
     * Obtiene el contenido por su clave (historia, mision, vision, ...).
     * Devuelve null si no existe, para que las vistas puedan ocultar la sección.
     */
    public static function buscar(string $clave): ?self
    {
        return static::where('clave', $clave)->first();
    }
}

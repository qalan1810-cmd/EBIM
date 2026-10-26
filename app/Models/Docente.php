<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $fillable = [
        'nombre',
        'cargo',
        'especialidad',
        'formacion',
        'experiencia',
        'foto',
        'encuadre',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'encuadre' => 'integer',
    ];

    /**
     * Posición vertical de la fotografía dentro de la tarjeta, en formato
     * "background-position" (0 arriba, 50 centro, 100 abajo).
     */
    public function posicionFoto(): string
    {
        $valor = max(0, min(100, (int) ($this->encuadre ?? 50)));

        return 'center ' . $valor . '%';
    }

    /**
     * Docentes visibles para el público, en orden.
     */
    public function scopeVisibles($query)
    {
        return $query->where('activo', true)->orderBy('orden')->orderBy('nombre');
    }
}

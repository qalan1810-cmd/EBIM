<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $fillable = [
        'user_id',
        'asignatura_id',
        'asignatura',
        'gestion',
        'parcial',
        'nota',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'parcial' => 'integer',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Asignatura del catálogo. El nombre se expone como `asignatura` para que
     * las vistas sigan funcionando sin conocer la clave foránea.
     */
    public function asignaturaRel(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    public function getAsignaturaAttribute(): ?string
    {
        return $this->asignaturaRel?->nombre;
    }

    /**
     * Permite seguir creando notas con el nombre de la asignatura. Si la
     * asignatura no existe en el catálogo, se crea en ese momento.
     */
    public function setAsignaturaAttribute(?string $nombre): void
    {
        $nombre = trim((string) $nombre);

        if ($nombre === '') {
            $this->attributes['asignatura_id'] = null;

            return;
        }

        $this->attributes['asignatura_id'] = Asignatura::firstOrCreate([
            'nombre' => $nombre,
        ])->id;
    }

    /**
     * Ordena las notas por el nombre de la asignatura y por parcial.
     *
     * Se hace con un join porque el nombre dejó de estar duplicado en `notas`.
     */
    public function scopeOrdenadoPorAsignatura($query)
    {
        return $query
            ->join('asignaturas', 'asignaturas.id', '=', 'notas.asignatura_id')
            ->orderBy('asignaturas.nombre')
            ->orderBy('notas.parcial')
            ->select('notas.*');
    }
}

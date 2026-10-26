<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    protected $fillable = [
        'curso_id',
        'asignatura_id',
        'asignatura',
        'dia',
        'hora_inicio',
        'hora_fin',
        'docente',
        'ambiente',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Asignatura del catálogo. El nombre se expone con el accesor
     * `asignatura` para no cambiar las vistas ni los formularios existentes.
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
     * Al guardar desde un formulario se recibe el nombre; se enlaza con el
     * catálogo y se crea la asignatura si todavía no existe.
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
     * MySQL devuelve las columnas TIME como "08:30:00"; en la interfaz se
     * muestran siempre como "08:30".
     */
    public function getHoraInicioAttribute(?string $valor): ?string
    {
        return $valor === null ? null : substr($valor, 0, 5);
    }

    public function getHoraFinAttribute(?string $valor): ?string
    {
        return $valor === null ? null : substr($valor, 0, 5);
    }

    /**
     * Ordena el horario por día de la semana (lunes primero) y hora de inicio.
     */
    public function scopeOrdenado($query)
    {
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        return $query->orderByRaw(
            "CASE dia " . collect($dias)->map(fn ($d, $i) => "WHEN '{$d}' THEN {$i}")->implode(' ') . " ELSE 99 END"
        )->orderBy('hora_inicio');
    }
}

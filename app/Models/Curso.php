<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Curso de la escuela (por ejemplo 2 "A"). El horario de clases se organiza
 * por curso: todos los estudiantes del curso comparten el mismo horario.
 *
 * El horario tiene un estado de aprobación: el director académico lo elabora y
 * lo envía, y el rector lo aprueba o lo devuelve con observaciones.
 */
class Curso extends Model
{
    /**
     * Días que se muestran como columnas de la rejilla horaria.
     *
     * @var list<string>
     */
    public const DIAS = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

    /** El director académico todavía está armando el horario. */
    public const ESTADO_BORRADOR = 'borrador';

    /** Enviado al rector, a la espera de su revisión. */
    public const ESTADO_ENVIADO = 'enviado';

    /** Revisado y aprobado por el rector. */
    public const ESTADO_APROBADO = 'aprobado';

    /** Devuelto por el rector para que el director académico lo corrija. */
    public const ESTADO_RECHAZADO = 'rechazado';

    /**
     * @var array<string, string>
     */
    public const ESTADOS = [
        self::ESTADO_BORRADOR => 'En elaboración',
        self::ESTADO_ENVIADO => 'Pendiente de aprobación',
        self::ESTADO_APROBADO => 'Aprobado',
        self::ESTADO_RECHAZADO => 'Devuelto con observaciones',
    ];

    protected $fillable = [
        'nombre',
        'orden',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'horario_enviado_en' => 'datetime',
            'horario_revisado_en' => 'datetime',
        ];
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }

    public function estudiantes(): HasMany
    {
        return $this->hasMany(User::class, 'curso_id');
    }

    /** Director académico que envió el horario a aprobación. */
    public function enviadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'horario_enviado_por');
    }

    /** Rector que aprobó o devolvió el horario. */
    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'horario_revisado_por');
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    public function estadoEtiqueta(): string
    {
        return self::ESTADOS[$this->estado_horario] ?? $this->estado_horario;
    }

    /**
     * El director académico solo puede modificar el horario mientras esté en
     * elaboración o cuando el rector se lo haya devuelto.
     */
    public function esEditable(): bool
    {
        return in_array($this->estado_horario, [self::ESTADO_BORRADOR, self::ESTADO_RECHAZADO], true);
    }

    /**
     * Rejilla del horario semanal del curso: las filas son las franjas
     * horarias y las columnas los días de la semana.
     *
     * @return array{
     *     dias: list<string>,
     *     franjas: array<int, array{hora_inicio: string, hora_fin: string, celdas: array<string, Horario>}>
     * }
     */
    public function rejilla(): array
    {
        $franjas = [];

        foreach ($this->horarios()->with('asignaturaRel')->ordenado()->get() as $horario) {
            // Las asignaturas que comparten hora de inicio y de fin forman una
            // misma fila de la tabla.
            $clave = $horario->hora_inicio . '|' . $horario->hora_fin;

            $franjas[$clave] ??= [
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
                'celdas' => [],
            ];

            $franjas[$clave]['celdas'][$horario->dia] = $horario;
        }

        // "08:00|10:00" ordena cronológicamente como texto.
        ksort($franjas);

        return [
            'dias' => self::DIAS,
            'franjas' => array_values($franjas),
        ];
    }
}

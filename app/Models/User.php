<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'codigo',
        'carnet',
        'curso_id',
        'especialidad_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Curso al que pertenece el estudiante. El horario de clases se consulta
     * por curso, no por estudiante.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Especialidad instrumental del estudiante.
     */
    public function especialidadRel(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    /**
     * Nombre de la especialidad para vistas y formularios. En la base solo se
     * guarda la clave foránea.
     */
    public function getEspecialidadAttribute(): ?string
    {
        return $this->especialidadRel?->nombre;
    }

    /**
     * Contraseña inicial de un estudiante: carnet, primer nombre y primer
     * apellido, sin espacios, tildes ni signos. Ejemplo:
     * 7084785 + Alan + Quispe = 7084785alanquispe.
     */
    public static function generarPasswordEstudiante(string $carnet, string $nombre): string
    {
        $partes = preg_split('/\s+/u', trim($nombre)) ?: [];
        $indiceApellido = max(1, count($partes) - 2);
        $base = $carnet.($partes[0] ?? '').($partes[$indiceApellido] ?? '');
        $password = preg_replace('/[^a-z0-9]/', '', strtolower(Str::ascii($base)));

        return $password !== '' ? $password : $carnet;
    }

    /**
     * Rector(a) / administración del sistema. Aprueba los horarios.
     */
    public function esAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Director(a) académico. Elabora los horarios y los envía a aprobación.
     */
    public function esDirector(): bool
    {
        return $this->role === 'director';
    }

    public function esEstudiante(): bool
    {
        return $this->role === 'estudiante';
    }

    /**
     * Personal autorizado con acceso al panel (rector y director académico).
     */
    public function esPersonal(): bool
    {
        return $this->esAdmin() || $this->esDirector();
    }

    /**
     * @return array<string, string>
     */
    public static function roles(): array
    {
        return [
            'admin' => 'Rector(a) / Administración',
            'director' => 'Director(a) académico',
            'estudiante' => 'Estudiante',
        ];
    }

    public function etiquetaRol(): string
    {
        return self::roles()[$this->role] ?? $this->role;
    }
}

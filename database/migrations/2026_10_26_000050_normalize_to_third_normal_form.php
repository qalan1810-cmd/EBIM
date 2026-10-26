<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lleva el esquema a tercera forma normal (3FN).
 *
 * Cambios principales:
 *  - Las especialidades de los estudiantes dejan de ser texto repetido y pasan
 *    a un catálogo (`especialidades`).
 *  - Las asignaturas de horarios y notas dejan de ser texto repetido y pasan a
 *    un catálogo (`asignaturas`).
 *  - El campo `notas.bimestre` se llama `parcial`, que es el término que usa la
 *    escuela en el portal.
 *  - Se agregan claves foráneas, índices y restricciones únicas para que la
 *    integridad no dependa solo de la aplicación.
 *
 * La migración conserva los datos existentes: crea los catálogos a partir de
 * los valores que ya estaban cargados y luego enlaza las filas.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->crearCatalogos();
        $this->normalizarUsuarios();
        $this->normalizarHorarios();
        $this->normalizarNotas();
        $this->ajustarTiposEIndices();
    }

    private function crearCatalogos(): void
    {
        Schema::create('especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80)->unique();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();
        });

        $ahora = now();

        // La lista oficial vive en config/ebim.php. Se copia a la base para que
        // las claves foráneas y las consultas no dependan de un archivo.
        foreach (config('ebim.especialidades', []) as $indice => $nombre) {
            DB::table('especialidades')->insertOrIgnore([
                'nombre' => $nombre,
                'orden' => $indice + 1,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ]);
        }

        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120)->unique();
            $table->timestamps();
        });
    }

    private function normalizarUsuarios(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('especialidad_id')
                ->nullable()
                ->after('curso_id')
                ->constrained('especialidades')
                ->nullOnDelete();
        });

        if (! Schema::hasColumn('users', 'especialidad')) {
            return;
        }

        DB::table('users')
            ->whereNotNull('especialidad')
            ->orderBy('id')
            ->get(['id', 'especialidad'])
            ->each(function ($usuario) {
                $nombre = trim((string) $usuario->especialidad);

                if ($nombre === '') {
                    return;
                }

                $id = DB::table('especialidades')->where('nombre', $nombre)->value('id');

                if (! $id) {
                    $id = DB::table('especialidades')->insertGetId([
                        'nombre' => $nombre,
                        'orden' => ((int) DB::table('especialidades')->max('orden')) + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('users')->where('id', $usuario->id)->update([
                    'especialidad_id' => $id,
                ]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('especialidad');
        });
    }

    private function normalizarHorarios(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->unsignedBigInteger('asignatura_id')->nullable()->after('curso_id');
        });

        $this->crearAsignaturasDesdeTextos();

        if (Schema::hasColumn('horarios', 'asignatura')) {
            DB::table('horarios')
                ->orderBy('id')
                ->get(['id', 'asignatura'])
                ->each(function ($horario) {
                    $nombre = trim((string) $horario->asignatura);
                    $nombre = $nombre !== '' ? $nombre : 'Sin asignatura';

                    $id = DB::table('asignaturas')->where('nombre', $nombre)->value('id')
                        ?? DB::table('asignaturas')->insertGetId([
                            'nombre' => $nombre,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    DB::table('horarios')->where('id', $horario->id)->update([
                        'asignatura_id' => $id,
                    ]);
                });

            Schema::table('horarios', function (Blueprint $table) {
                $table->unsignedBigInteger('asignatura_id')->nullable(false)->change();
                $table->foreign('asignatura_id')
                    ->references('id')
                    ->on('asignaturas')
                    ->restrictOnDelete();
                $table->dropColumn('asignatura');
            });
        }

        Schema::table('horarios', function (Blueprint $table) {
            $table->unique(
                ['curso_id', 'dia', 'hora_inicio'],
                'horarios_curso_dia_inicio_unique'
            );
        });
    }

    private function normalizarNotas(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->unsignedBigInteger('asignatura_id')->nullable()->after('user_id');
        });

        $this->crearAsignaturasDesdeTextos();

        if (Schema::hasColumn('notas', 'asignatura')) {
            DB::table('notas')
                ->orderBy('id')
                ->get(['id', 'asignatura'])
                ->each(function ($nota) {
                    $nombre = trim((string) $nota->asignatura);
                    $nombre = $nombre !== '' ? $nombre : 'Sin asignatura';

                    $id = DB::table('asignaturas')->where('nombre', $nombre)->value('id')
                        ?? DB::table('asignaturas')->insertGetId([
                            'nombre' => $nombre,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    DB::table('notas')->where('id', $nota->id)->update([
                        'asignatura_id' => $id,
                    ]);
                });

            Schema::table('notas', function (Blueprint $table) {
                $table->unsignedBigInteger('asignatura_id')->nullable(false)->change();
                $table->foreign('asignatura_id')
                    ->references('id')
                    ->on('asignaturas')
                    ->restrictOnDelete();
                $table->dropColumn('asignatura');
            });
        }

        if (Schema::hasColumn('notas', 'bimestre')) {
            Schema::table('notas', function (Blueprint $table) {
                $table->renameColumn('bimestre', 'parcial');
            });
        }

        Schema::table('notas', function (Blueprint $table) {
            $table->unique(
                ['user_id', 'asignatura_id', 'gestion', 'parcial'],
                'notas_estudiante_asignatura_gestion_parcial_unique'
            );
            $table->index(['user_id', 'gestion'], 'notas_estudiante_gestion_index');
            $table->index('gestion', 'notas_gestion_index');
        });
    }

    /**
     * Copia a `asignaturas` los nombres que todavía estén guardados como texto
     * en horarios o notas. Es idempotente.
     */
    private function crearAsignaturasDesdeTextos(): void
    {
        $nombres = collect();

        if (Schema::hasColumn('horarios', 'asignatura')) {
            $nombres = $nombres->merge(
                DB::table('horarios')->whereNotNull('asignatura')->distinct()->pluck('asignatura')
            );
        }

        if (Schema::hasColumn('notas', 'asignatura')) {
            $nombres = $nombres->merge(
                DB::table('notas')->whereNotNull('asignatura')->distinct()->pluck('asignatura')
            );
        }

        $nombres
            ->map(fn ($nombre) => trim((string) $nombre))
            ->filter()
            ->unique()
            ->each(function ($nombre) {
                DB::table('asignaturas')->insertOrIgnore([
                    'nombre' => $nombre,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    private function ajustarTiposEIndices(): void
    {
        // Las horas se guardan como TIME, no como texto libre.
        Schema::table('horarios', function (Blueprint $table) {
            $table->time('hora_inicio')->change();
            $table->time('hora_fin')->change();
        });

        // 100.00 es una nota válida; decimal(4,2) solo llega a 99.99.
        Schema::table('notas', function (Blueprint $table) {
            $table->decimal('nota', 5, 2)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_index');
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->index('estado_horario', 'cursos_estado_horario_index');
        });

        Schema::table('noticias', function (Blueprint $table) {
            $table->index(['activo', 'fecha'], 'noticias_activo_fecha_index');
        });

        Schema::table('docentes', function (Blueprint $table) {
            $table->index(['activo', 'orden'], 'docentes_activo_orden_index');
        });
    }

    public function down(): void
    {
        // En MariaDB el índice único de notas y el de horarios también sirven
        // como índice de las claves foráneas de user_id y curso_id. Hay que
        // soltar esas claves antes de eliminar los índices y restablecerlas al
        // final del rollback.
        Schema::table('notas', function (Blueprint $table) {
            $table->dropForeign('notas_user_id_foreign');
            $table->dropUnique('notas_estudiante_asignatura_gestion_parcial_unique');
            $table->dropIndex('notas_estudiante_gestion_index');
            $table->dropIndex('notas_gestion_index');
        });

        Schema::table('horarios', function (Blueprint $table) {
            $table->dropForeign('horarios_curso_id_foreign');
            $table->dropUnique('horarios_curso_dia_inicio_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_index');
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->dropIndex('cursos_estado_horario_index');
        });

        Schema::table('noticias', function (Blueprint $table) {
            $table->dropIndex('noticias_activo_fecha_index');
        });

        Schema::table('docentes', function (Blueprint $table) {
            $table->dropIndex('docentes_activo_orden_index');
        });

        Schema::table('horarios', function (Blueprint $table) {
            $table->string('hora_inicio')->change();
            $table->string('hora_fin')->change();
        });

        Schema::table('notas', function (Blueprint $table) {
            $table->decimal('nota', 4, 2)->change();
            $table->string('asignatura', 120)->nullable()->after('user_id');
        });

        $this->volcarAsignaturas('notas');

        Schema::table('notas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('asignatura_id');
        });

        if (Schema::hasColumn('notas', 'parcial')) {
            Schema::table('notas', function (Blueprint $table) {
                $table->renameColumn('parcial', 'bimestre');
            });
        }

        Schema::table('notas', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('horarios', function (Blueprint $table) {
            $table->string('asignatura', 120)->nullable()->after('curso_id');
        });

        $this->volcarAsignaturas('horarios');

        Schema::table('horarios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('asignatura_id');
            $table->foreign('curso_id')->references('id')->on('cursos')->cascadeOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('especialidad', 120)->nullable()->after('curso_id');
        });

        DB::table('users')
            ->whereNotNull('especialidad_id')
            ->orderBy('id')
            ->get(['id', 'especialidad_id'])
            ->each(function ($usuario) {
                $nombre = DB::table('especialidades')->where('id', $usuario->especialidad_id)->value('nombre');
                DB::table('users')->where('id', $usuario->id)->update(['especialidad' => $nombre]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('especialidad_id');
        });

        Schema::dropIfExists('asignaturas');
        Schema::dropIfExists('especialidades');
    }

    private function volcarAsignaturas(string $tabla): void
    {
        DB::table($tabla)
            ->whereNotNull('asignatura_id')
            ->orderBy('id')
            ->get(['id', 'asignatura_id'])
            ->each(function ($fila) use ($tabla) {
                $nombre = DB::table('asignaturas')->where('id', $fila->asignatura_id)->value('nombre');
                DB::table($tabla)->where('id', $fila->id)->update(['asignatura' => $nombre]);
            });
    }
};

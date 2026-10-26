<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\Curso;
use App\Models\Horario;
use Illuminate\Database\Seeder;

/**
 * Horario de clases de los cinco grados de la Licenciatura en Música.
 *
 * La rejilla usa la jornada definida por la escuela: tres franjas diarias de
 * lunes a viernes. Como el sistema guarda una sola rejilla por curso, se
 * carga el primer semestre de cada año (semestres 1, 3, 5, 7 y 9); cuando
 * empiece el segundo semestre el director académico actualiza la rejilla con
 * las materias que correspondan.
 *
 * No se asigna un docente con nombre y apellido porque la escuela todavía no
 * definió la carga de cada profesor por sección; cada celda indica el área que
 * dicta la asignatura y se completa desde el panel de Horarios.
 */
class HorarioSeeder extends Seeder
{
    /**
     * Tres franjas diarias, de lunes a viernes, de 08:30 a 13:00.
     *
     * @var list<array{0: string, 1: string}>
     */
    private const FRANJAS = [
        ['08:30', '10:00'],
        ['10:00', '11:30'],
        ['11:30', '13:00'],
    ];

    /**
     * Franjas de la versión anterior de la demo. Se eliminan antes de cargar
     * la rejilla nueva para no duplicar filas.
     *
     * @var list<array{0: string, 1: string}>
     */
    private const FRANJAS_ANTERIORES = [
        ['08:00', '10:00'],
        ['10:30', '12:30'],
        ['14:00', '16:00'],
    ];

    /**
     * Rejilla semanal del primer semestre de cada año.
     *
     * @var array<int, array<string, list<string>>>
     */
    private const REJILLAS = [
        1 => [
            'Lunes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Sonido y Acústica'],
            'Martes' => ['Lenguaje Musical', 'Músicas y Culturas', 'Taller de Lenguaje'],
            'Miércoles' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Sonido y Acústica'],
            'Jueves' => ['Lenguaje Musical', 'Músicas y Culturas', 'Taller de Lenguaje'],
            'Viernes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Idioma Originario'],
        ],
        2 => [
            'Lunes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Organología y Lutería'],
            'Martes' => ['Lenguaje Musical', 'Músicas y Culturas', 'Metodología de la Investigación'],
            'Miércoles' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Organología y Lutería'],
            'Jueves' => ['Lenguaje Musical', 'Músicas y Culturas', 'Metodología de la Investigación'],
            'Viernes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Idioma Originario'],
        ],
        3 => [
            'Lunes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Psicopedagogía de la Música'],
            'Martes' => ['Músicas y Culturas', 'Armonía y Teclado', 'Formas e Identidades Musicales Bolivianas'],
            'Miércoles' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Psicopedagogía de la Música'],
            'Jueves' => ['Músicas y Culturas', 'Armonía y Teclado', 'Formas e Identidades Musicales Bolivianas'],
            'Viernes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Gestión Cultural'],
        ],
        4 => [
            'Lunes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Formas e Identidades Musicales Bolivianas'],
            'Martes' => ['Músicas y Culturas', 'Nuevas Tecnologías e Inglés Técnico', 'Composición, Análisis y Arreglos'],
            'Miércoles' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Formas e Identidades Musicales Bolivianas'],
            'Jueves' => ['Músicas y Culturas', 'Nuevas Tecnologías e Inglés Técnico', 'Composición, Análisis y Arreglos'],
            'Viernes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Gestión Cultural'],
        ],
        5 => [
            'Lunes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Nuevas Tecnologías e Inglés Técnico'],
            'Martes' => ['Músicas y Culturas', 'Composición, Análisis y Arreglos', 'Literatura, Artes Escénicas y Audiovisuales'],
            'Miércoles' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Nuevas Tecnologías e Inglés Técnico'],
            'Jueves' => ['Músicas y Culturas', 'Composición, Análisis y Arreglos', 'Literatura, Artes Escénicas y Audiovisuales'],
            'Viernes' => ['Instrumento de Especialidad', 'Práctica Colectiva Instrumental', 'Seminario'],
        ],
    ];

    /**
     * Área responsable de cada asignatura. Se usa como docente mientras la
     * dirección no asigne un profesor concreto a cada sección.
     *
     * @var array<string, string>
     */
    private const DOCENTES = [
        'Instrumento de Especialidad' => 'Docente de la especialidad',
        'Práctica Colectiva Instrumental' => 'Docente de Práctica Colectiva',
        'Lenguaje Musical' => 'Docente de Lenguaje Musical',
        'Taller de Lenguaje' => 'Docente de Taller de Lenguaje',
        'Músicas y Culturas' => 'Docente de Músicas y Culturas',
        'Idioma Originario' => 'Docente de Idioma Originario',
        'Sonido y Acústica' => 'Docente de Sonido y Acústica',
        'Organología y Lutería' => 'Docente de Organología y Lutería',
        'Metodología de la Investigación' => 'Docente de Metodología',
        'Psicopedagogía de la Música' => 'Docente de Psicopedagogía',
        'Armonía y Teclado' => 'Docente de Armonía y Teclado',
        'Formas e Identidades Musicales Bolivianas' => 'Docente de Formas Musicales',
        'Nuevas Tecnologías e Inglés Técnico' => 'Docente de Nuevas Tecnologías',
        'Gestión Cultural' => 'Docente de Gestión Cultural',
        'Composición, Análisis y Arreglos' => 'Docente de Composición',
        'Literatura, Artes Escénicas y Audiovisuales' => 'Docente de Literatura y Artes',
        'Seminario' => 'Docente de Seminario',
    ];

    public function run(): void
    {
        $cursos = [
            '1 "A"' => 1,
            '1 "B"' => 1,
            '2 "A"' => 2,
            '2 "B"' => 2,
            '3 "A"' => 3,
            '3 "B"' => 3,
            '4 "A"' => 4,
            '5 "A"' => 5,
        ];

        $orden = 0;

        $asignaturas = Asignatura::pluck('id', 'nombre');

        foreach ($cursos as $nombre => $anio) {
            $orden++;

            $curso = Curso::firstOrCreate(
                ['nombre' => $nombre],
                ['orden' => $orden]
            );

            // Un horario enviado o aprobado no se toca desde un seeder.
            if (! $curso->esEditable()) {
                continue;
            }

            // Retira la rejilla de la demo anterior, si todavía está cargada.
            foreach (self::FRANJAS_ANTERIORES as [$inicioAnterior, $finAnterior]) {
                $curso->horarios()
                    ->where('hora_inicio', $inicioAnterior)
                    ->where('hora_fin', $finAnterior)
                    ->delete();
            }

            foreach (self::REJILLAS[$anio] as $dia => $asignaturasDelDia) {
                foreach ($asignaturasDelDia as $indice => $asignatura) {
                    [$horaInicio, $horaFin] = self::FRANJAS[$indice];

                    if (! isset($asignaturas[$asignatura])) {
                        $asignaturas[$asignatura] = Asignatura::create([
                            'nombre' => $asignatura,
                        ])->id;
                    }

                    Horario::updateOrCreate(
                        [
                            'curso_id' => $curso->id,
                            'dia' => $dia,
                            'hora_inicio' => $horaInicio,
                        ],
                        [
                            'hora_fin' => $horaFin,
                            'asignatura_id' => $asignaturas[$asignatura],
                            'docente' => self::DOCENTES[$asignatura],
                        ]
                    );
                }
            }
        }
    }
}

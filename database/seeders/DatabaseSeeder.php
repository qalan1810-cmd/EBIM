<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\Contenido;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Especialidad;
use App\Models\Noticia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Datos de ejemplo para el prototipo de la EBIM.
     * El contenido institucional está basado en información pública de la
     * Escuela Boliviana Intercultural de Música (Oruro, Bolivia).
     */
    public function run(): void
    {
        $this->call(EspecialidadSeeder::class);

        $this->usuarios();
        $this->cursos();
        $this->contenidos();
        $this->docentes();
        $this->noticias();
        $this->informacionAcademica();
    }

    /**
     * Cursos de ejemplo. El horario de clases se organiza por curso.
     */
    private function cursos(): void
    {
        $cursos = [
            ['nombre' => '1 "A"', 'orden' => 1],
            ['nombre' => '1 "B"', 'orden' => 2],
            ['nombre' => '2 "A"', 'orden' => 3],
            ['nombre' => '2 "B"', 'orden' => 4],
            ['nombre' => '3 "A"', 'orden' => 5],
            ['nombre' => '3 "B"', 'orden' => 6],
            ['nombre' => '4 "A"', 'orden' => 7],
            ['nombre' => '5 "A"', 'orden' => 8],
        ];

        foreach ($cursos as $curso) {
            Curso::updateOrCreate(['nombre' => $curso['nombre']], $curso);
        }
    }

    private function usuarios(): void
    {
        // Si la base ya tiene usuarios, no se vuelven a crear las cuentas de
        // demostración. Así `db:seed` no reemplaza ni duplica a los estudiantes
        // reales que se administran desde el panel.
        if (User::query()->exists()) {
            return;
        }

        User::updateOrCreate(
            ['email' => 'rector@ebim.edu.bo'],
            [
                'name' => 'Rectoría EBIM',
                'password' => Hash::make('ebim2026'),
                'role' => 'admin',
            ]
        );

        // El director académico elabora los horarios y los envía a aprobación.
        User::updateOrCreate(
            ['email' => 'director@ebim.edu.bo'],
            [
                'name' => 'Dirección Académica EBIM',
                'password' => Hash::make('ebim2026'),
                'role' => 'director',
            ]
        );

        User::updateOrCreate(
            ['email' => 'estudiante@ebim.edu.bo'],
            [
                'name' => 'María Condori Mamani',
                'password' => Hash::make(User::generarPasswordEstudiante('7000001', 'María Condori Mamani')),
                'role' => 'estudiante',
                'codigo' => '6001',
                'carnet' => '7000001',
            ]
        );

        User::updateOrCreate(
            ['email' => 'estudiante2@ebim.edu.bo'],
            [
                'name' => 'Juan Quispe Flores',
                'password' => Hash::make(User::generarPasswordEstudiante('7000002', 'Juan Quispe Flores')),
                'role' => 'estudiante',
                'codigo' => '6002',
                'carnet' => '7000002',
            ]
        );
    }

    private function contenidos(): void
    {
        $contenidos = [
            'historia' => [
                'titulo' => 'Nuestra historia',
                'cuerpo' => "La Escuela Boliviana Intercultural de Música (EBIM) fue creada mediante Decreto Supremo N.º 0009 del 14 de febrero de 2009, durante el gobierno del presidente Evo Morales Ayma, e inaugurada el 8 de octubre de 2009. Nació a solicitud de la Asociación de Bandas de Músicos de Oruro y es la primera institución de su género en Latinoamérica.\n\nLa escuela tiene como finalidad revalorizar y recuperar los saberes musicales de las 36 nacionalidades y pueblos del Estado Plurinacional de Bolivia, formando músicos con identidad intercultural. Se encuentra bajo tuición del Ministerio de Educación, en coordinación con el Ministerio de Culturas.\n\nInició sus actividades con 80 estudiantes y 15 docentes en instalaciones del centro educativo Marcelo Quiroga Santa Cruz, y actualmente funciona en su sede de la Zona de Capachos, Carretera Oruro – Cochabamba Km 13.",
            ],
            'historia_resumen' => [
                'titulo' => 'Nuestra historia',
                'cuerpo' => "La EBIM nació a solicitud de la Asociación de Bandas de Músicos Profesionales de Oruro (ABMPO), que agrupa a más de 5.000 músicos y necesitaba una formación académica con grados y bases curriculares para su desempeño profesional.\n\nEl 14 de febrero de 2009, en el Acto Central del VII Festival de Bandas en la Av. Cívica de Oruro, se determinó mediante D.S. 0009 su creación, para formar músicos profesionales a partir de la música originaria, tradicional, popular y de tradición escrita.",
            ],
            'cronologia' => [
                'titulo' => 'Nuestra historia',
                'cuerpo' => "2009 | Creación | La EBIM fue creada el 14 de febrero mediante Decreto Supremo N.º 0009, a solicitud de la Asociación de Bandas de Músicos Profesionales de Oruro (ABMPO).\n2009 | Inauguración | El 8 de octubre se inaugura como la primera escuela de formación musical intercultural de Latinoamérica.\n2009 | Primeras clases | Inició sus actividades con 80 estudiantes y 15 docentes en el centro educativo Marcelo Quiroga Santa Cruz.\n2011 | Terreno propio | La Ley N.º 085 autoriza la transferencia gratuita de 40.000 m² en Moxuma Capachos para construir la infraestructura de la escuela.\n2013 | Resolución Ministerial | La Resolución Ministerial N.º 606/2013 consolida la EBIM dentro de las Escuelas Bolivianas Interculturales del Ministerio de Educación.\n2016 | Título de Licenciatura | Se valida la formación en Técnico Medio, Técnico Superior y Licenciatura en Música, con las menciones de Intérprete Instrumentista e Investigación Musical.\n2026 | Nueva etapa | Tras 17 años de actividad ininterrumpida, la escuela inicia una nueva administración con la designación del rector Arturo César Herrera Camargo.",
            ],
            'sobre_intro' => [
                'titulo' => 'Sobre EBIM',
                'cuerpo' => 'La primera institución de formación musical intercultural de Latinoamérica: conoce su historia, su misión y los valores que la sostienen.',
            ],
            'rector' => [
                // Datos verificados con fuentes públicas (biografía en
                // historia.com.bo y cobertura de La Patria sobre su designación
                // en julio de 2026). Se editan desde Panel -> Contenido.
                'titulo' => 'Arturo César Herrera Camargo',
                'cuerpo' => "Músico, compositor y antropólogo orureño. Charanguista desde los 8 años, especializado en el charango k'alanpeador y en el charango estándar.\n\nDesde 1982 es charanguista y Director Artístico del Grupo Andino, con el que grabó 20 discos y realizó giras por Europa, Estados Unidos y Latinoamérica. Obtuvo la medalla Charango de Bronce en Aiquile y en 2016 la Cámara de Diputados le otorgó la distinción Honor al Mérito Cultural.\n\nEs docente de la especialidad de Charango y de Gestión Cultural en la EBIM, y miembro de SOBODAYCOM, ABAIEM y la Sociedad Boliviana del Charango.",
                'foto' => '/assets/ebim/rectores/arturo-herrera.jpg',
            ],
            'mision' => [
                'titulo' => 'Misión',
                'cuerpo' => 'Formar profesionales de la música con identidad intercultural, revalorizando y recuperando los saberes musicales de las naciones y pueblos indígena originario campesinos del Estado Plurinacional de Bolivia, contribuyendo a la descolonización y a la integración cultural del país.',
            ],
            'vision' => [
                'titulo' => 'Visión',
                'cuerpo' => 'Consolidarnos como la principal institución de formación musical intercultural de Bolivia, reconocida nacional e internacionalmente por la excelencia académica y por la valoración de la diversidad cultural y musical boliviana.',
            ],
            'valores' => [
                'titulo' => 'Valores',
                'cuerpo' => "Interculturalidad y respeto por la diversidad cultural.\nIdentidad y valoración de los saberes musicales bolivianos.\nExcelencia académica y disciplina artística.\nCompromiso social con las bandas y comunidades de Oruro y de Bolivia.\nTrabajo en equipo y práctica de conjunto.",
            ],
            'requisitos' => [
                'titulo' => 'Requisitos de inscripción',
                'cuerpo' => "Solicitud de inscripción dirigida a la dirección de la escuela.\nCertificado de nacimiento original y copia.\nCédula de identidad original y copia.\nDos fotografías tamaño 4x4 (fondo rojo).\nCertificado de estudios del nivel anterior (según el nivel al que postula).\nFormulario de inscripción completo.\nComprobante original del depósito bancario a la cuenta de la EBIM.\nAprobar la evaluación de aptitud musical.",
            ],
            'pasos_inscripcion' => [
                'titulo' => 'Pasos del proceso de inscripción',
                'cuerpo' => "Completa la solicitud y el formulario | Imprime los dos documentos, llénalos a mano con letra legible y fírmalos.\nRealiza el depósito bancario | Deposita en la cuenta de la EBIM y guarda el comprobante original: se entrega junto con el resto de los documentos.\nPrepara la evaluación de aptitud musical | Repasa el material teórico y práctico de tu instrumento antes de la audición.\nPresenta tu carpeta en secretaría | Entrega en la sede de Capachos la solicitud, el formulario, los documentos de la lista y el comprobante de depósito, en horario de oficina.\nRinde la evaluación de aptitud musical | La audición se coordina con secretaría y con su resultado se define tu admisión.\nConfirma tu inscripción | Con la documentación completa y la aptitud aprobada, secretaría confirma tu inscripción y te asigna curso y horario.",
            ],
        ];

        foreach ($contenidos as $clave => $datos) {
            Contenido::updateOrCreate(['clave' => $clave], $datos);
        }
    }

    private function docentes(): void
    {
        // Nombres, fotografías y cargos indicados por la dirección de la
        // escuela. La formación y la trayectoria provienen de fuentes públicas
        // (publicaciones y notas de prensa que mencionan a cada docente en la
        // EBIM); lo que no pudo confirmarse se deja como texto a completar por
        // la dirección, sin inventar datos.
        $docentes = [
            [
                'nombre' => 'Lic. Denis Pérez Garnica',
                'cargo' => 'Director Académico',
                'especialidad' => 'Dirección académica',
                'formacion' => 'Formación profesional a completar por la dirección de la escuela.',
                'experiencia' => 'Director Académico de la EBIM. Coordina el desarrollo académico de la escuela y sus especialidades instrumentales.',
                'foto' => '/assets/ebim/docentes/dennis.jpg',
                'orden' => 1,
            ],
            [
                'nombre' => 'Lic. Jesús Elías Lucero',
                'cargo' => 'Docente de Práctica Coral',
                'especialidad' => 'Práctica coral y dirección de coro',
                'formacion' => 'Licenciado en Música.',
                'experiencia' => 'Docente de práctica coral en la EBIM y director de su Orquesta de Cámara. Ha dirigido presentaciones de la escuela y de agrupaciones musicales de Oruro.',
                'foto' => '/assets/ebim/docentes/jesus-elias.jpg',
                'orden' => 2,
            ],
            [
                'nombre' => 'Mstro. Juan Carlos Machicado',
                'cargo' => 'Docente de Guitarra',
                'especialidad' => 'Guitarra clásica',
                'formacion' => 'Maestro guitarrista y catedrático de música.',
                'experiencia' => 'Reconocido guitarrista y formador de varias generaciones de guitarristas bolivianos. Dirigió la Orquesta Juvenil de Guitarras y ha participado en festivales internacionales de guitarra.',
                'foto' => '/assets/ebim/docentes/juan-carlos-machicado.jpg',
                'encuadre' => 0,
                'orden' => 3,
            ],
            [
                'nombre' => 'Lic. Karen Villca',
                'cargo' => 'Docente de Lenguaje Musical',
                'especialidad' => 'Lenguaje musical y formación auditiva',
                'formacion' => 'Licenciada en Música.',
                'experiencia' => 'Docente de lenguaje musical en la EBIM, a cargo de la formación teórica y auditiva de los estudiantes.',
                'foto' => '/assets/ebim/docentes/karen-villca.jpg',
                'encuadre' => 0,
                'orden' => 4,
            ],
            [
                'nombre' => 'Lic. Ricardo Choque',
                'cargo' => 'Docente de Práctica Colectiva',
                'especialidad' => 'Charango',
                'formacion' => 'Licenciado en Música.',
                'experiencia' => 'Docente de práctica colectiva en la EBIM, dedicado al trabajo de conjunto y al repertorio de las bandas y agrupaciones de Oruro. Intérprete de charango.',
                'foto' => '/assets/ebim/docentes/ricardo-choque.jpg',
                'encuadre' => 40,
                'orden' => 5,
            ],
            [
                'nombre' => 'Lic. Ramiro Gutiérrez Condori',
                'cargo' => 'Antropólogo · Docente',
                'especialidad' => 'Antropología de la música e investigación cultural',
                'formacion' => 'Licenciado en Antropología, músico e investigador de la música boliviana.',
                'experiencia' => 'Docente de la EBIM, intérprete de instrumentos de viento nativo y autor de investigaciones sobre la música andina y boliviana.',
                'foto' => '/assets/ebim/docentes/ramiro-gutierrez.jpg',
                'orden' => 6,
            ],
        ];

        foreach ($docentes as $docente) {
            Docente::updateOrCreate(
                ['nombre' => $docente['nombre']],
                $docente + ['activo' => true]
            );
        }

        // Nombres del prototipo que ya no corresponden: se retiran si existían.
        Docente::whereIn('nombre', [
            'Dennis',
            'Mario Bustamante Montaño',
            'Jesús Elías',
            'Guido Mamani Quispe',
            'Rosario Copa Aguilar',
            'Freddy Huanca Mamani',
            'Elena Flores Vargas',
            'Ronald Tejedor Rojas',
            'Maribel Condori Choque',
        ])->delete();
    }

    /**
     * Contenido de prueba del prototipo: NO son noticias reales de la escuela.
     * Solo muestran cómo funciona la sección de la portada y deben
     * reemplazarse desde el panel de administración.
     */
    private function noticias(): void
    {
        $texto = 'Contenido de prueba del prototipo. Sustituye este texto por la publicación real que desee difundir la escuela.';

        // Publicaciones reales tomadas de la página oficial de Facebook de la
        // EBIM. El enlace apunta a cada publicación: Facebook sólo permite ver
        // las publicaciones dentro de Facebook, así que aquí se muestra el
        // título, el texto y el enlace "Ver en Facebook".
        $noticias = [
            [
                'titulo' => 'La música de la EBIM en EXPOTECO 2026',
                'tipo' => Noticia::TIPO_NOTICIA,
                'resumen' => 'El poder de la música de la EBIM en el pabellón "Supay" de EXPOTECO 2026 y en el pabellón Pariguana, donde está el stand de nuestra institución.',
                'cuerpo' => 'Invitamos a la comunidad orureña a visitar nuestro espacio y acompañar a los estudiantes y docentes durante la feria.',
                'enlace' => 'https://www.facebook.com/100063251977207/posts/el-poder-de-la-m%C3%BAsica-de-la-ebim-en-el-pabell%C3%B3n-supay-expoteco-2026-y-en-el-pabe/1380524360732617/',
                'fecha' => '2026-10-15',
                'orden' => 1,
            ],
            [
                'titulo' => '17 años formando talento, cultura y tradición',
                'tipo' => Noticia::TIPO_NOTICIA,
                'resumen' => 'La Escuela Boliviana Intercultural de Música celebra con orgullo 17 años de trayectoria, dedicados a la formación artística y a la preservación de nuestra música.',
                'cuerpo' => 'Un aniversario que reconoce el trabajo de estudiantes, docentes y de toda la comunidad que acompaña a la escuela desde su creación.',
                'enlace' => 'https://www.facebook.com/100063251977207/posts/-17-a%C3%B1os-formando-talento-cultura-y-tradici%C3%B3n-la-escuela-boliviana-intercultural/1375630397888680/',
                'fecha' => '2026-10-09',
                'orden' => 2,
            ],
            [
                'titulo' => 'Concierto por el 17.º aniversario',
                'tipo' => Noticia::TIPO_NOTICIA,
                'resumen' => 'La EBIM invita a la comunidad orureña a participar del Concierto por el 17.º aniversario, una celebración de la música, la formación y la interculturalidad.',
                'cuerpo' => 'El concierto reúne a las especialidades de la escuela y muestra el trabajo de sus estudiantes y docentes.',
                'enlace' => 'https://www.facebook.com/100063251977207/posts/-celebramos-17-a%C3%B1os-de-m%C3%BAsica-formaci%C3%B3n-e-interculturalidadla-escuela-boliviana-/1373917964726590/',
                'fecha' => '2026-10-08',
                'orden' => 3,
            ],
            [
                'titulo' => 'Jornadas académicas EBIM: ingreso libre',
                'tipo' => Noticia::TIPO_NOTICIA,
                'resumen' => 'Invitamos a toda la población orureña a compartir una jornada dedicada a la música, la investigación cultural, las nuevas tecnologías y los derechos culturales.',
                'cuerpo' => 'Las jornadas académicas están abiertas al público en general, con ingreso libre.',
                'enlace' => 'https://www.facebook.com/100063251977207/posts/-jornadas-acad%C3%A9micas-ebim-ingreso-libreinvitamos-a-toda-la-poblaci%C3%B3n-orure%C3%B1a-a-c/1372261478225572/',
                'fecha' => '2026-10-05',
                'orden' => 4,
            ],
            [
                'titulo' => 'Comunicado: Acto Académico por el 17.º aniversario',
                'tipo' => Noticia::TIPO_COMUNICADO,
                'resumen' => 'La Escuela Boliviana Intercultural de Música invita a la comunidad orureña a participar del Acto Académico por su 17.º aniversario.',
                'cuerpo' => 'Se convoca a estudiantes, docentes, personal administrativo y a toda la comunidad a acompañar este acto institucional.',
                'enlace' => 'https://www.facebook.com/100063251977207/posts/-celebramos-17-a%C3%B1os-de-m%C3%BAsica-formaci%C3%B3n-e-interculturalidadla-escuela-boliviana-/1373436024774784/',
                'fecha' => '2026-10-08',
                'orden' => 1,
            ],
            [
                'titulo' => 'Comunicado: firma de convenio con el Campo Ferial 3 de Julio',
                'tipo' => Noticia::TIPO_COMUNICADO,
                'resumen' => 'El Campo Ferial 3 de Julio firmó un convenio con la Escuela Boliviana Intercultural de Música-EBIM, fortaleciendo la presencia de la escuela en EXPOTECO 2026.',
                'cuerpo' => 'La firma del convenio refuerza el trabajo conjunto entre la escuela y el campo ferial de la ciudad de Oruro.',
                'enlace' => 'https://www.facebook.com/campoferial3dejuliooficial/posts/convenio-ebim-la-m%C3%BAsica-tambi%C3%A9n-se-vive-en-expoteco-2026el-campo-ferial-3-de-jul/1125382059827359/',
                'fecha' => '2026-10-14',
                'orden' => 2,
            ],
        ];

        foreach ($noticias as $noticia) {
            Noticia::updateOrCreate(
                ['titulo' => $noticia['titulo']],
                $noticia + ['activo' => true]
            );
        }

        // Publicaciones del prototipo que ya no corresponden a la versión con
        // contenido real: se retiran si existían.
        Noticia::whereIn('titulo', [
            'Comunicado de ejemplo: convocatoria de inscripciones',
            'Noticia de ejemplo: taller de iniciación musical',
            'Noticia de ejemplo: ensayo de la banda estudiantil',
            'Noticia de ejemplo: jornada de puertas abiertas',
        ])->delete();

        unset($texto);
    }

    private function informacionAcademica(): void
    {
        // Cada estudiante pertenece a un curso y consulta el horario de ese curso.
        // La especialidad es dato de prueba para la lista de notas del rectorado.
        $miembros = [
            'estudiante@ebim.edu.bo' => ['curso' => '2 "A"', 'especialidad' => 'Guitarra'],
            'estudiante2@ebim.edu.bo' => ['curso' => '3 "A"', 'especialidad' => 'Canto'],
        ];

        foreach ($miembros as $correo => $datos) {
            User::where('email', $correo)->update([
                'curso_id' => Curso::where('nombre', $datos['curso'])->value('id'),
                'especialidad_id' => Especialidad::where('nombre', $datos['especialidad'])->value('id'),
            ]);
        }

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->first();

        if (! $estudiante) {
            return;
        }

        $notas = [
            ['asignatura' => 'Instrumento principal', 'parcial' => 1, 'nota' => 88],
            ['asignatura' => 'Lenguaje musical', 'parcial' => 1, 'nota' => 75],
            ['asignatura' => 'Práctica de conjunto', 'parcial' => 1, 'nota' => 92],
            ['asignatura' => 'Historia de la música boliviana', 'parcial' => 1, 'nota' => 68],
            ['asignatura' => 'Instrumento principal', 'parcial' => 2, 'nota' => 90],
            ['asignatura' => 'Lenguaje musical', 'parcial' => 2, 'nota' => 80],
            ['asignatura' => 'Práctica de conjunto', 'parcial' => 2, 'nota' => 95],
            ['asignatura' => 'Historia de la música boliviana', 'parcial' => 2, 'nota' => 72],
        ];

        foreach ($notas as $nota) {
            $asignaturaId = Asignatura::firstOrCreate([
                'nombre' => $nota['asignatura'],
            ])->id;

            $estudiante->notas()->updateOrCreate(
                [
                    'asignatura_id' => $asignaturaId,
                    'gestion' => '2026',
                    'parcial' => $nota['parcial'],
                ],
                ['nota' => $nota['nota']]
            );
        }

    }
}

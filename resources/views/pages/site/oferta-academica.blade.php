@extends('layouts.app')

@section('title', 'Oferta Académica | EBIM Oruro')
@section('description', 'Licenciatura, Técnico Superior y Técnico Medio en Música. Planes de estudio, duración, modalidad y menciones de la Escuela Boliviana Intercultural de Música.')

@php
    /*
     * Los planes de estudio de esta página provienen íntegramente de la Malla
     * Curricular oficial de la EBIM (archivo PLAN.pdf de la escuela). Cada
     * materia lista sus horas semanales por semestre; el 0 significa que en ese
     * semestre no se dicta. La suma por semestre es siempre 30 horas y los
     * totales coinciden con los de la malla: 2.400 h (Técnico Medio), 3.600 h
     * (Técnico Superior) y 6.000 h (Licenciatura).
     *
     * El orden es del nivel mayor al menor: Licenciatura, Técnico Superior y
     * Técnico Medio, como una sola pantalla por nivel.
     */
    $niveles = [
        [
            'clave' => 'licenciatura',
            'orden' => 'Nivel 3',
            'nombre' => 'Licenciatura en Música',
            'lema' => 'Formación superior',
            'descripcion' => 'El grado académico más alto de la escuela. Profundiza la interpretación, la creación y la investigación musical, y cierra con un trabajo de grado.',
            'duracion' => '5 años',
            'horas' => '6.000 horas académicas',
            'modalidad' => 'Presencial',
            'resolucion' => 'R.M. N.º 606/2013',
            'semestres' => 10,
            'menciones' => [
                'Instrumento de Especialidad',
                'Investigación Musical',
                'Composición Musical',
                'Producción Musical',
            ],
            'malla' => [
                'Lenguaje Musical' => [4, 4, 4, 4, 0, 0, 0, 0, 0, 0],
                'Músicas y Culturas' => [4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                'Idioma Originario' => [2, 2, 2, 2, 0, 0, 0, 0, 0, 0],
                'Sonido y Acústica' => [4, 4, 0, 0, 0, 0, 0, 0, 0, 0],
                'Taller de Lenguaje' => [4, 4, 0, 0, 0, 0, 0, 0, 0, 0],
                'Instrumento de Especialidad' => [6, 6, 6, 6, 6, 6, 6, 6, 6, 6],
                'Práctica Colectiva Instrumental' => [6, 6, 6, 6, 6, 6, 6, 6, 6, 6],
                'Organología y Lutería' => [0, 0, 4, 4, 0, 0, 0, 0, 0, 0],
                'Metodología de la Investigación' => [0, 0, 4, 0, 0, 0, 0, 0, 0, 0],
                'Psicopedagogía de la Música' => [0, 0, 0, 4, 4, 0, 0, 0, 0, 0],
                'Armonía y Teclado' => [0, 0, 0, 0, 4, 4, 0, 0, 0, 0],
                'Formas e Identidades Musicales Bolivianas' => [0, 0, 0, 0, 4, 0, 4, 0, 0, 0],
                'Nuevas Tecnologías e Inglés Técnico' => [0, 0, 0, 0, 0, 4, 4, 4, 4, 4],
                'Didáctica Musical' => [0, 0, 0, 0, 0, 4, 0, 0, 0, 0],
                'Gestión Cultural' => [0, 0, 0, 0, 2, 2, 2, 2, 0, 0],
                'Composición, Análisis y Arreglos' => [0, 0, 0, 0, 0, 0, 4, 4, 4, 0],
                'Producción Musical' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 4],
                'Literatura, Artes Escénicas y Audiovisuales' => [0, 0, 0, 0, 0, 0, 0, 4, 4, 4],
                'Seminario' => [0, 0, 0, 0, 0, 0, 0, 0, 2, 2],
            ],
        ],
        [
            'clave' => 'tecnico-superior',
            'orden' => 'Nivel 2',
            'nombre' => 'Técnico Superior',
            'lema' => 'Oficio y escenario',
            'descripcion' => 'Formación técnica especializada en interpretación instrumental, lenguaje musical y práctica de conjunto, con las herramientas de gestión y didáctica del oficio musical.',
            'duracion' => '3 años',
            'horas' => '3.600 horas académicas',
            'modalidad' => 'Presencial',
            'resolucion' => 'R.M. N.º 606/2013',
            'semestres' => 6,
            'menciones' => ['Intérprete Instrumentista'],
            'malla' => [
                'Lenguaje Musical' => [4, 4, 4, 4, 0, 0],
                'Músicas y Culturas' => [4, 4, 4, 4, 4, 4],
                'Idioma Originario' => [2, 2, 2, 2, 0, 0],
                'Sonido y Acústica' => [4, 4, 0, 0, 0, 0],
                'Taller de Lenguaje' => [4, 4, 0, 0, 0, 0],
                'Instrumento de Especialidad' => [6, 6, 6, 6, 6, 6],
                'Práctica Colectiva Instrumental' => [6, 6, 6, 6, 6, 6],
                'Organología y Lutería' => [0, 0, 4, 4, 0, 0],
                'Metodología de la Investigación' => [0, 0, 4, 0, 0, 0],
                'Psicopedagogía de la Música' => [0, 0, 0, 4, 4, 0],
                'Armonía y Teclado' => [0, 0, 0, 0, 4, 4],
                'Formas e Identidades Musicales Bolivianas' => [0, 0, 0, 0, 4, 0],
                'Nuevas Tecnologías e Inglés Técnico' => [0, 0, 0, 0, 0, 4],
                'Didáctica Musical' => [0, 0, 0, 0, 0, 4],
                'Gestión Cultural' => [0, 0, 0, 0, 2, 2],
            ],
        ],
        [
            'clave' => 'tecnico-medio',
            'orden' => 'Nivel 1',
            'nombre' => 'Técnico Medio',
            'lema' => 'El primer paso',
            'descripcion' => 'Formación inicial para quienes concluyen la educación secundaria y quieren comenzar su camino en la música con un título de técnico medio.',
            'duracion' => '2 años',
            'horas' => '2.400 horas académicas',
            'modalidad' => 'Presencial',
            'resolucion' => 'R.M. N.º 606/2013',
            'semestres' => 4,
            'menciones' => ['Intérprete Instrumentista'],
            'malla' => [
                'Lenguaje Musical' => [4, 4, 4, 4],
                'Músicas y Culturas' => [4, 4, 4, 4],
                'Idioma Originario' => [2, 2, 2, 2],
                'Sonido y Acústica' => [4, 4, 0, 0],
                'Taller de Lenguaje' => [4, 4, 0, 0],
                'Instrumento de Especialidad' => [6, 6, 6, 6],
                'Práctica Colectiva Instrumental' => [6, 6, 6, 6],
                'Organología y Lutería' => [0, 0, 4, 4],
                'Metodología de la Investigación' => [0, 0, 4, 0],
                'Psicopedagogía de la Música' => [0, 0, 0, 4],
            ],
        ],
    ];

    // Instrumentos oficiales de la EBIM. 'foto' es el archivo dentro de
    // public/assets/ebim/instrumentos/. 'pos' ajusta el recorte (object-position)
    // según dónde queda el instrumento en cada foto (medido por centro de masa).
    $especialidades = [
        ['titulo' => 'Charango', 'foto' => 'charango', 'pos' => 'center 40%'],
        ['titulo' => 'Guitarra', 'foto' => 'guitarra', 'pos' => 'center 40%'],
        ['titulo' => 'Piano', 'foto' => 'piano', 'pos' => 'center 30%'],
        ['titulo' => 'Clarinete', 'foto' => 'clarinete', 'pos' => 'center 40%'],
        ['titulo' => 'Saxofón', 'foto' => 'saxofon', 'pos' => 'center 35%'],
        ['titulo' => 'Trompeta', 'foto' => 'trompeta', 'pos' => 'center 38%'],
        ['titulo' => 'Trombón', 'foto' => 'trombon', 'pos' => 'center 30%'],
        ['titulo' => 'Barítono-Bajo', 'foto' => 'baritono', 'pos' => 'center 38%'],
        ['titulo' => 'Tuba', 'foto' => 'tuba', 'pos' => 'center 40%'],
        ['titulo' => 'Aerófonos andinos', 'foto' => 'aerofonos', 'pos' => 'center 45%'],
        ['titulo' => 'Batería y Percusión', 'foto' => 'bateria-percusion', 'pos' => 'center 45%'],
        ['titulo' => 'Canto', 'foto' => 'canto', 'pos' => 'center 25%'],
    ];

    // Campos laborales (contenido proporcionado por la dirección de la EBIM).
    $camposLaborales = [
        ['titulo' => 'Músico intérprete instrumentista', 'detalle' => 'Profesional, solista y de grupos, bandas, orquestas y otros.'],
        ['titulo' => 'Músico e investigador cultural', 'detalle' => 'Estudio y difusión de las músicas bolivianas y sus contextos culturales.'],
        ['titulo' => 'Asesor musical, arreglista y compositor', 'detalle' => 'Composición, arreglos y asesoría musical para agrupaciones y proyectos.'],
        ['titulo' => 'Gestor cultural', 'detalle' => 'En instituciones públicas y privadas dedicadas a la música y la cultura.'],
        ['titulo' => 'Docente de Música', 'detalle' => 'En institutos fiscales y privados de formación musical.'],
    ];
@endphp

@section('content')

    <x-page-hero title="Oferta Académica">
        <p>Elige tu instrumento entre <strong>{{ count($especialidades) }} especialidades</strong> y conoce los tres
            niveles que te forman como músico profesional.</p>
    </x-page-hero>

    {{-- Especialidades instrumentales --}}
    <section class="bg-white py-[60px] max-md:py-[45px]">
        <div class="ebim-container">
            <h2 class="text-center text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]" data-reveal>
                Especialidades instrumentales
            </h2>

            {{-- Rejilla simétrica: los elementos se reparten y la última fila
                 queda centrada (no alineada a la izquierda).
                 Cada tarjeta entra por separado (data-reveal-grupo): el
                 JavaScript las escalona para que aparezcan una tras otra. --}}
            <div class="mt-10 flex flex-wrap justify-center gap-[22px] max-md:mt-7 max-md:gap-[14px]">
                @foreach ($especialidades as $especialidad)
                    <article data-reveal data-reveal-grupo
                        class="relative w-[calc((100%-66px)/4)] overflow-hidden rounded-[25px] border border-ink/10 bg-ink max-lg:w-[calc((100%-44px)/3)] max-md:w-[calc((100%-14px)/2)]">
                        {{-- Foto del instrumento, más baja, con el nombre encima --}}
                        <div class="relative aspect-[5/4] w-full overflow-hidden">
                            <img src="/assets/ebim/instrumentos/{{ $especialidad['foto'] }}-400.jpg"
                                srcset="/assets/ebim/instrumentos/{{ $especialidad['foto'] }}-400.jpg 400w,
                                        /assets/ebim/instrumentos/{{ $especialidad['foto'] }}-800.jpg 800w"
                                sizes="(min-width: 1024px) 266px, (min-width: 768px) 33vw, 50vw"
                                alt="{{ $especialidad['titulo'] }}"
                                style="object-position: {{ $especialidad['pos'] }};"
                                loading="lazy"
                                decoding="async"
                                width="400"
                                height="320"
                                class="h-full w-full object-cover">

                            {{-- Velo oscuro general: garantiza que el nombre se lea en el centro --}}
                            <span class="pointer-events-none absolute inset-0 bg-ink/45" aria-hidden="true"></span>

                            {{-- Nombre del instrumento, centrado sobre la foto --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center px-[16px] text-center">
                                <span class="block h-[3px] w-[34px] bg-brand" aria-hidden="true"></span>

                                <h3 class="mt-3 text-[18px] font-black uppercase leading-[1.2] tracking-[0.5px] text-white drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)] max-md:text-[15px]">
                                    {{ $especialidad['titulo'] }}
                                </h3>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Campos laborales --}}
    <section class="py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="flex items-end justify-between gap-6 max-md:flex-col max-md:items-start">
                <h2 class="text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">Campos laborales</h2>
            </div>

            {{-- Ficha: filas separadas por reglas finas, sin tarjetas repetidas --}}
            <div class="mt-10 grid grid-cols-2 gap-x-[45px] max-md:mt-7 max-md:grid-cols-1 max-md:gap-y-[30px]">
                @foreach ($camposLaborales as $campo)
                    <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                        <p class="text-[26px] font-bold leading-[1.2] text-ink max-md:text-[22px]">
                            {{ $campo['titulo'] }}
                        </p>
                        <p class="mt-3 text-[17px] leading-[1.5] text-ink-soft">
                            {{ $campo['detalle'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Niveles: Licenciatura, Técnico Superior y Técnico Medio --}}
    @foreach ($niveles as $indice => $nivel)
        @include('pages.site.partials.nivel-oferta', [
            'nivel' => $nivel,
            'conFondo' => $indice % 2 === 0,
        ])
    @endforeach

    {{-- Llamada final --}}
    <section class="pb-[70px] max-md:pb-[50px]">
        <div class="ebim-container" data-reveal>
            <div class="flex flex-wrap items-center justify-between gap-6 rounded-[25px] bg-ink p-[40px] max-md:p-[30px]">
                <div>
                    <h2 class="text-[32px] font-bold leading-[1.3] text-white max-md:text-[26px]">¿Listo para postularte?</h2>
                    <p class="mt-3 max-w-[640px] text-[19px] text-white/90">Revisa los requisitos y pasos del proceso de inscripción 2027.</p>
                </div>
                <a href="{{ url('/inscripcion') }}" class="btn-ss">Ver requisitos</a>
            </div>
        </div>
    </section>

@endsection

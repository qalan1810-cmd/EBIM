@extends('layouts.app')

@section('title', 'Escuela Boliviana Intercultural de Música | EBIM Oruro')
@section('description', 'La EBIM forma técnicos medios, técnicos superiores y licenciados en música en Oruro, Bolivia. Conoce la escuela, la oferta académica, los requisitos de inscripción y el plantel docente.')

@php
    $contact = config('ebim.contact');

    // Fotografías del carrusel de portada (sección hero).
    $heroSlides = [
        '/assets/ebim/home/HOME1.jpg',
        '/assets/ebim/home/HOME2.jpg',
        '/assets/ebim/home/HOME3.jpg',
        '/assets/ebim/home/HOME4.jpg',
    ];

    // Fotografías de los cuatro accesos principales. Son imágenes de música y
    // formación musical con licencia libre, porque no hay material propio
    // disponible para estas tarjetas. Procedencia y licencia de cada una:
    //   sobre-ebim       -> NYO-China y NYO-USA, ensayo conjunto (CC BY-SA 4.0)
    //   oferta-academica -> Festival de cuerdas PMEA 2026 (CC0)
    //   inscripcion      -> Ensayo de la WYSO Concert Orchestra (CC BY-SA 4.0)
    //   docentes         -> Clase de música en McNary High School (dominio público)
    $accesos = [
        ['title' => 'Sobre EBIM', 'url' => '/sobre-ebim', 'image' => 'sobre-ebim.jpg'],
        ['title' => 'Oferta Académica', 'url' => '/oferta-academica', 'image' => 'oferta-academica.jpg'],
        ['title' => 'Inscripción', 'url' => '/inscripcion', 'image' => 'inscripcion.jpg'],
        ['title' => 'Docentes', 'url' => '/docentes', 'image' => 'docentes.jpg'],
    ];

    // Especialidades instrumentales oficiales de la EBIM, tal como las
    // proporcionó la dirección de la escuela.
    $especialidades = [
        'Charango',
        'Guitarra',
        'Piano',
        'Clarinete',
        'Saxofón',
        'Trompeta',
        'Trombón',
        'Barítono-Bajo',
        'Tuba',
        'Aerófonos andinos',
        'Batería y Percusión',
        'Canto',
    ];

    $preguntas = [
        ['¿Dónde está ubicada la EBIM?', 'La Escuela Boliviana Intercultural de Música se encuentra en la Carretera Oruro – Cochabamba Km 13, Zona Capachos, en la ciudad de Oruro, Bolivia.'],
        ['¿Qué niveles ofrece la escuela?', 'La EBIM ofrece formación en Música en tres niveles: Técnico Medio, Técnico Superior y Licenciatura, bajo un régimen anual.'],
        ['¿Cuáles son los requisitos de inscripción?', 'Los requisitos y pasos del proceso de inscripción se detallan en la página de <a href="' . url('/inscripcion') . '">Inscripción</a>. La modalidad de inscripción es presencial, en la sede de la escuela.'],
        ['¿La escuela tiene algún costo?', 'La EBIM es una institución fiscal de carácter público, bajo tuición del Ministerio de Educación. Consulta los aranceles vigentes en la secretaría de la escuela al momento de tu inscripción.'],
    ];
@endphp

@section('content')

    {{-- ============================================================
         Portada
    ============================================================= --}}
    <section class="ss-hero relative flex min-h-[100dvh] flex-col justify-center overflow-hidden pt-[5%] pb-[5%] max-md:pt-[35%] max-md:pb-[35%]" data-carousel-hero>
        @foreach ($heroSlides as $index => $slide)
            {{-- Carrusel: la foto activa hace el acercamiento lento y la deriva
                 alterna de lado foto por foto (ver .page-hero-slide en app.css). --}}
            <div data-hero-slide
                @class([
                    'page-hero-slide',
                    'is-active' => $index === 0,
                    'page-hero-slide--izq' => $index % 2 === 0,
                    'page-hero-slide--der' => $index % 2 !== 0,
                ])
                style="background-image: url('{{ $slide }}'); background-position: center center;"
                aria-hidden="true"></div>
        @endforeach

        <div class="absolute inset-0 opacity-[0.82]"
            style="background-image: linear-gradient(90deg, #1d1d1d 35%, #FFFFFF00 100%);" aria-hidden="true"></div>

        {{-- Desvanecido: la foto se disuelve suavemente en el fondo de la página. --}}
        <div class="ss-hero-fade" aria-hidden="true"></div>

        <div class="ebim-container relative z-10">
            <div class="w-1/2 max-md:w-full">
                <h1 class="text-[60px] font-extrabold leading-[64px] text-white max-md:text-[40px] max-md:leading-[1.05]">
                    Escuela Boliviana Intercultural de Música
                </h1>
                <p class="mt-5 max-w-[620px] text-[24px] font-medium text-white max-md:text-[19px]">
                    Formando músicos, cultivando identidad y conectando culturas.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ url('/inscripcion') }}" class="btn-ss">Requisitos de inscripción</a>
                    <a href="{{ url('/sobre-ebim') }}" class="btn-ss">Conoce la escuela</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         Accesos principales.
         La banda es negra para que el desvanecido del hero (también negro)
         se funda con ella sin costura. Cada cuadro es sólo la fotografía: sin
         texto encima, para que se lea como un acceso visual y limpio. El
         nombre de la sección queda igual en el atributo alt, para lectores de
         pantalla y para quien navegue con las imágenes desactivadas.

         Arriba va el lema de la escuela: grande y centrado, con el adorno de
         líneas y rombo que usan las demás secciones, pero con aire alrededor
         para que no se sienta cargado.
    ============================================================= --}}
    <section class="ss-banda-accesos bg-ink pt-[120px] pb-[120px] max-md:pt-[90px] max-md:pb-[90px]">
        <div class="ebim-container" data-reveal>
            {{-- Lema institucional --}}
            <div class="mx-auto max-w-[900px] text-center">
                <span class="block h-[4px] w-[70px] bg-brand mx-auto max-md:w-[54px]" aria-hidden="true"></span>

                <p class="mt-8 text-[44px] font-extrabold leading-[1.25] text-white max-lg:text-[38px] max-md:mt-6 max-md:text-[27px] max-md:leading-[1.3]">
                    &iexcl;Somos talento, somos pasi&oacute;n, <span class="text-[#e8535f]">EBIM</span> se lleva en el coraz&oacute;n!
                </p>

                {{-- Adorno: dos líneas y un rombo guindo, como en las demás secciones --}}
                <span class="mt-9 flex items-center justify-center gap-3 max-md:mt-7" aria-hidden="true">
                    <span class="block h-[1px] w-[70px] bg-white/25 max-md:w-[46px]"></span>
                    <span class="block h-[8px] w-[8px] rotate-45 bg-brand"></span>
                    <span class="block h-[1px] w-[70px] bg-white/25 max-md:w-[46px]"></span>
                </span>
            </div>

            <div class="ss-banda-rejilla mt-[70px] grid grid-cols-4 gap-[10px] max-lg:grid-cols-2 max-md:mt-[45px] max-md:grid-cols-1">
                @foreach ($accesos as $acceso)
                    @php($base = pathinfo($acceso['image'], PATHINFO_FILENAME))
                    <a href="{{ url($acceso['url']) }}" aria-label="{{ $acceso['title'] }}"
                        class="relative block min-h-[300px] overflow-hidden rounded-[25px]">
                        <img src="/assets/ebim/accesos/{{ $base }}-400.jpg"
                            srcset="/assets/ebim/accesos/{{ $base }}-400.jpg 400w,
                                    /assets/ebim/accesos/{{ $base }}-800.jpg 800w"
                            sizes="(min-width: 1024px) 25vw, (min-width: 768px) 50vw, 100vw"
                            alt="{{ $acceso['title'] }}"
                            loading="lazy" decoding="async"
                            class="absolute inset-0 h-full w-full object-cover">
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- El desvanecido vive fuera de la banda, justo debajo. Así la banda negra
         ocupa la pantalla completa y el negro sólo empieza a apagarse cuando el
         usuario sigue bajando. --}}
    <div class="ss-banda-fade" aria-hidden="true"></div>

    {{-- ============================================================
         Especialidades instrumentales y nuestra historia.
    ============================================================= --}}
    <section class="py-[70px] max-md:py-[50px]">
        <div class="ebim-container" data-reveal>
            {{-- Especialidades: la animación vuelve a ser la de antes (fichas que
                 se desplazan en bucle), con un desvanecido a los lados para que
                 entren y salgan del marco en lugar de cortarse de golpe. --}}
            <h2 class="text-center text-[32px] font-semibold text-ink">Especialidades instrumentales</h2>

            <div class="ss-marquee-mask mt-5 overflow-hidden">
                <div class="ss-marquee-track flex w-max items-center">
                    @foreach ([...$especialidades, ...$especialidades] as $especialidad)
                        <div class="flex h-[70px] shrink-0 items-center justify-center px-3">
                            <span class="whitespace-nowrap rounded-[20px] border-2 border-ink px-6 py-3 text-[18px] font-semibold text-ink">{{ $especialidad }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Historia --}}
            <div class="mt-[60px] flex flex-row items-stretch gap-[50px] max-md:mt-[45px] max-md:flex-col max-md:gap-6">
                <div class="flex flex-1 flex-col justify-center">
                    <h2 class="text-[40px] font-extrabold leading-[1.4] text-ink max-md:text-[30px]">
                        {{ $historiaResumen->titulo ?? 'Nuestra historia' }}
                    </h2>
                    <div class="page-prose pt-3">
                        @if ($historiaResumen)
                            {!! nl2br(e($historiaResumen->cuerpo)) !!}
                        @else
                            <p>La Escuela Boliviana Intercultural de Música fue creada por Decreto Supremo N.º 0009 del 14 de febrero de 2009: la primera escuela de formación musical intercultural de Latinoamérica.</p>
                        @endif
                    </div>

                    <p class="mt-6">
                        <a href="{{ url('/sobre-ebim') }}"
                            class="inline-flex items-center gap-2 rounded-[15px] border-2 border-ink px-6 py-[10px] text-[18px] font-bold text-ink hover:bg-ink hover:text-white">
                            Conoce más sobre la EBIM <span aria-hidden="true">&rarr;</span>
                        </a>
                    </p>
                </div>
                {{-- Galería de la presentación: cada foto es una sola pieza con
                     su leyenda debajo, así que imagen y texto se desplazan
                     juntos hacia la izquierda para dar paso a la siguiente.
                     Sin desvanecidos: la foto se ve entera, de borde a borde. --}}
                <div class="flex flex-1 flex-col">
                    <div class="ss-galeria-envoltorio flex-1" data-galeria>
                        <div class="ss-galeria">
                            <div class="ss-galeria-pista" data-galeria-pista>
                                @foreach ($galeriaHistoria as $foto)
                                    <figure class="ss-galeria-slide">
                                        <div class="ss-galeria-foto">
                                            <img src="{{ $foto['src'] }}"
                                                @if ($foto['srcset'] !== '')
                                                    srcset="{{ $foto['srcset'] }}"
                                                    sizes="(min-width: 1024px) 50vw, 100vw"
                                                @endif
                                                alt="{{ $foto['texto'] }}"
                                                loading="eager" fetchpriority="low" decoding="async">
                                        </div>

                                        <figcaption class="ss-galeria-texto">
                                            {{ $foto['texto'] }}
                                        </figcaption>
                                    </figure>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tres niveles de formación --}}
            <h2 class="mt-[60px] text-[40px] font-extrabold leading-[1.4] text-ink max-md:mt-[45px] max-md:text-[30px]">
                Tres niveles de formación
            </h2>
            <p class="mt-3 max-w-[760px] text-[18px] leading-[1.5] text-ink">
                Formación en Música bajo régimen anual, con titulación reconocida por el Ministerio de Educación.
            </p>

            <div class="mt-6 grid grid-cols-3 gap-[20px] max-md:grid-cols-1">
                @foreach ([
                    ['nivel' => 'Técnico Medio', 'detalle' => 'Formación inicial en Música, con bases técnicas y lenguaje musical.'],
                    ['nivel' => 'Técnico Superior', 'detalle' => 'Profundización instrumental y práctica de conjunto.'],
                    ['nivel' => 'Licenciatura', 'detalle' => 'Formación profesional completa en Música.'],
                ] as $indice => $nivel)
                    <div class="rounded-[25px] border-2 border-ink/10 bg-white p-[28px]">
                        <span class="flex h-[42px] w-[42px] items-center justify-center rounded-full bg-brand text-[18px] font-extrabold text-white">
                            {{ $indice + 1 }}
                        </span>
                        <h3 class="mt-4 text-[24px] font-bold leading-[1.2] text-ink">{{ $nivel['nivel'] }}</h3>
                        <p class="mt-2 text-[17px] leading-[1.5] text-ink">{{ $nivel['detalle'] }}</p>
                    </div>
                @endforeach
            </div>

            <p class="mt-6">
                <a href="{{ url('/oferta-academica') }}" class="btn-ss">Consulta la oferta académica completa</a>
            </p>
        </div>
    </section>

    {{-- ============================================================
         Docentes destacados
    ============================================================= --}}
    @if ($docentes->isNotEmpty())
        <section class="bg-white py-[70px] max-md:py-[50px]">
            <div class="ebim-container" data-reveal>
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 class="text-[40px] font-extrabold leading-[1.4] text-ink max-md:text-[30px]">Nuestro plantel docente</h2>
                    <a href="{{ url('/docentes') }}" class="btn-ss">Ver todos los docentes</a>
                </div>

                <div class="mt-8 grid grid-cols-4 gap-[20px] max-lg:grid-cols-2 max-md:grid-cols-1">
                    @foreach ($docentes as $docente)
                        <x-docente-card :docente="$docente" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         Noticias y comunicados
    ============================================================= --}}
    @if ($noticias->isNotEmpty() || $comunicados->isNotEmpty())
        <section class="py-[70px] max-md:py-[50px]">
            <div class="ebim-container">
                @if ($noticias->isNotEmpty())
                    <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                        <h2 class="text-[40px] font-extrabold leading-[1.4] text-ink max-md:text-[30px]">Noticias</h2>
                        <div class="max-w-[560px]">
                            <p>
                                <a href="{{ config('ebim.contact.facebook') }}" target="_blank" rel="noopener" class="btn-ss">
                                    Seguir en Facebook <span aria-hidden="true">&rarr;</span>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-3 gap-[20px] max-lg:grid-cols-2 max-md:grid-cols-1" data-reveal>
                        @foreach ($noticias as $noticia)
                            <x-publicacion-card :noticia="$noticia" />
                        @endforeach
                    </div>
                @endif

                @if ($comunicados->isNotEmpty())
                    <div class="mt-[60px] flex flex-wrap items-end justify-between gap-4 max-md:mt-[45px]" data-reveal>
                        <h2 class="text-[40px] font-extrabold leading-[1.4] text-ink max-md:text-[30px]">Comunicados</h2>
                    </div>

                    <div class="mt-8 rounded-[20px] border-2 border-ink p-5" data-reveal>
                        @foreach ($comunicados as $index => $comunicado)
                            <div class="{{ $index > 0 ? 'border-t-2 border-mist' : '' }} py-4">
                                <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">
                                    {{ optional($comunicado->fecha)->format('d/m/Y') }}
                                </p>
                                <h3 class="mt-2 text-[24px] font-bold leading-[1.2] text-ink">{{ $comunicado->titulo }}</h3>
                                @if ($comunicado->resumen)
                                    <p class="mt-2 text-[17px] leading-[1.5] text-ink">{!! nl2br(e($comunicado->resumen)) !!}</p>
                                @endif

                                @if ($comunicado->urlIncrustable())
                                    <div class="relative mt-4 w-full overflow-hidden rounded-[15px] pt-[56.25%]">
                                        <iframe src="{{ $comunicado->urlIncrustable() }}" title="{{ $comunicado->titulo }}"
                                            class="absolute inset-0 h-full w-full" style="border:0" loading="lazy"
                                            scrolling="no" frameborder="0"
                                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                            allowfullscreen></iframe>
                                    </div>
                                @elseif ($comunicado->imagenUrl())
                                    <div class="mt-4 h-[260px] w-full rounded-[15px] bg-cover bg-center"
                                        style="background-image: url('{{ $comunicado->imagenUrl() }}');"
                                        role="img" aria-label="{{ $comunicado->titulo }}"></div>
                                @endif

                                @if ($comunicado->cuerpo)
                                    <div class="mt-3 text-[17px] leading-[1.5] text-ink">{!! nl2br(e($comunicado->cuerpo)) !!}</div>
                                @endif

                                @if ($comunicado->enlace && ! $comunicado->urlIncrustable())
                                    <p class="mt-3">
                                        <a href="{{ $comunicado->enlace }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-2 text-[16px] font-bold text-brand underline">
                                            Ver en Facebook <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ============================================================
         Preguntas frecuentes
    ============================================================= --}}
    <section class="py-[70px] max-md:py-[50px]">
        <div class="ebim-container" data-reveal>
            <h2 class="text-[40px] font-extrabold leading-[1.4] text-ink max-md:text-[30px]">Preguntas frecuentes</h2>

            <div class="mt-8 rounded-[20px] border-2 border-ink p-5 [&_a]:underline" data-accordion>
                @foreach ($preguntas as $index => [$pregunta, $respuesta])
                    <div data-accordion-item data-open="false" class="{{ $index > 0 ? 'border-t-2 border-mist' : '' }}">
                        <button type="button" data-accordion-title
                            class="flex w-full items-center justify-between gap-4 py-4 text-left text-[22px] font-bold leading-[1.2] text-ink">
                            <span>{{ $pregunta }}</span>
                            <span class="shrink-0 text-[22px] leading-none" data-accordion-icon>+</span>
                        </button>
                        <div data-accordion-content class="hidden pb-4 text-[18px] leading-[1.5] text-ink">
                            {!! $respuesta !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         Portal del estudiante
    ============================================================= --}}
    <section class="bg-ink py-[70px] max-md:py-[50px]">
        <div class="ebim-container text-center" data-reveal>
            <h2 class="text-[36px] font-extrabold leading-[1.3] text-white max-md:text-[28px]">Portal del Estudiante</h2>
            <p class="mx-auto mt-4 max-w-[760px] text-[20px] font-medium text-white/90 max-md:text-[18px]">
                Consulta tus notas por gestión y parcial, y revisa tu horario de clases establecido por la dirección de la escuela.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ url('/portal') }}" class="btn-ss">Ingresar al portal</a>
            </div>
        </div>
    </section>

@endsection

@extends('layouts.app')

@section('title', 'Sobre EBIM | EBIM Oruro')
@section('description', 'Historia, misión, visión y valores de la Escuela Boliviana Intercultural de Música, institución fiscal bajo tuición del Ministerio de Educación en Oruro, Bolivia.')

@section('content')

    {{-- Encabezado: cambia solo cuando existen fotos en public/assets/ebim/paginas/sobre-ebim/ --}}
    <x-page-hero title="Sobre EBIM" slug="sobre-ebim">
        @if ($intro)
            @foreach (preg_split('/\n\s*\n/', trim($intro->cuerpo)) as $parrafo)
                @if (trim($parrafo) !== '')
                    <p>{{ trim($parrafo) }}</p>
                @endif
            @endforeach
        @else
            <p>La Escuela Boliviana Intercultural de Música forma músicos con identidad intercultural en la ciudad de Oruro, Bolivia.</p>
        @endif
    </x-page-hero>

    {{-- Misión y visión --}}
    <section class="py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="grid grid-cols-2 gap-[30px] max-md:grid-cols-1">
                {{-- Misión --}}
                <article class="group relative overflow-hidden rounded-[25px] border-2 border-ink bg-white p-[40px] max-md:p-[30px]">
                    <h2 class="relative text-[32px] font-bold leading-[1.3] text-ink max-md:text-[26px]">
                        {{ $mision->titulo ?? 'Misión' }}
                    </h2>

                    <span class="mt-4 block h-[4px] w-[60px] bg-brand" aria-hidden="true"></span>

                    <div class="page-prose relative mt-5">
                        @if ($mision)
                            {!! nl2br(e($mision->cuerpo)) !!}
                        @else
                            <p>Formar profesionales de la música con identidad intercultural, revalorizando y recuperando los saberes musicales de los pueblos y naciones del Estado Plurinacional de Bolivia.</p>
                        @endif
                    </div>
                </article>

                {{-- Visión --}}
                <article class="group relative overflow-hidden rounded-[25px] bg-brand p-[40px] text-white max-md:p-[30px]">
                    <h2 class="relative text-[32px] font-bold leading-[1.3] text-white max-md:text-[26px]">
                        {{ $vision->titulo ?? 'Visión' }}
                    </h2>

                    <span class="mt-4 block h-[4px] w-[60px] bg-white" aria-hidden="true"></span>

                    <div class="page-prose relative mt-5 text-white [&_p]:text-white">
                        @if ($vision)
                            {!! nl2br(e($vision->cuerpo)) !!}
                        @else
                            <p>Consolidarnos como la principal institución de formación musical intercultural de Bolivia, reconocida por la excelencia académica y por la integración de la diversidad cultural del país.</p>
                        @endif
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Valores --}}
    <section class="bg-white py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="mx-auto max-w-[680px] text-center">
                <span class="text-[14px] font-bold uppercase tracking-[3px] text-brand">Lo que nos define</span>

                <h2 class="mt-3 text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">
                    {{ $valores->titulo ?? 'Valores' }}
                </h2>

                {{-- Adorno: dos líneas y un punto central --}}
                <span class="mx-auto mt-5 flex items-center justify-center gap-3" aria-hidden="true">
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                    <span class="block h-[8px] w-[8px] rotate-45 bg-brand"></span>
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                </span>
            </div>

            @if ($valoresItems !== [])
                @php($total = count($valoresItems))

                {{-- Cada valor ocupa una columna; en escritorio las tarjetas
                     impares bajan un poco para romper la línea recta.
                     En móvil la rejilla se pliega a una sola columna. --}}
                <div class="mt-[45px] grid gap-x-[24px] gap-y-[24px] valores-rejilla max-md:mt-8 max-md:gap-y-4"
                    style="--columnas-valores: {{ $total }};">
                    @foreach ($valoresItems as $indice => $valor)
                        <article
                            class="group relative flex flex-col items-center rounded-[25px] border border-ink/10 bg-mist px-[22px] pb-[34px] pt-[38px] text-center max-md:flex-row max-md:gap-5 max-md:px-[24px] max-md:py-[22px] max-md:text-left {{ $loop->even ? 'valores-bajada' : '' }}">

                            {{-- Escudo: círculo guindo que se rellena al pasar el cursor --}}
                            <span
                                class="relative z-10 flex h-[56px] w-[56px] shrink-0 items-center justify-center rounded-full border-2 border-brand bg-white text-[22px] font-black text-brand max-md:h-[46px] max-md:w-[46px] max-md:text-[19px]">
                                {{ $indice + 1 }}
                            </span>

                            <span class="relative z-10 mt-5 block h-[3px] w-[34px] bg-brand/30 max-md:hidden"
                                aria-hidden="true"></span>

                            <p class="relative z-10 mt-5 text-[18px] font-semibold leading-[1.45] text-ink max-md:mt-0 max-md:text-[17px]">
                                {{ $valor }}
                            </p>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="page-prose mt-8 text-center">
                    <p>No hay valores registrados por el momento.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Datos institucionales --}}
    <section class="py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="flex items-end justify-between gap-6 max-md:flex-col max-md:items-start">
                <h2 class="text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">Datos institucionales</h2>

                <span class="mb-[14px] hidden h-[1px] flex-1 bg-ink/15 md:block" aria-hidden="true"></span>

                <p class="mb-[10px] text-[15px] font-bold uppercase tracking-[3px] text-brand">Oruro · Bolivia</p>
            </div>

            {{-- Ficha: columnas separadas por reglas finas, sin tarjetas repetidas --}}
            <div class="mt-10 grid grid-cols-3 gap-x-[45px] max-md:mt-7 max-md:grid-cols-1 max-md:gap-y-[30px]">
                {{-- Formación --}}
                <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">Formación</p>
                    <p class="mt-3 text-[26px] font-bold leading-[1.2] text-ink max-md:text-[22px]">Tres niveles</p>
                    <p class="mt-3 text-[17px] leading-[1.5] text-ink-soft">
                        Técnico Medio, Técnico Superior y Licenciatura en música.
                    </p>
                </div>

                {{-- Tuición --}}
                <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">Tuición</p>
                    <p class="mt-3 text-[26px] font-bold leading-[1.2] text-ink max-md:text-[22px]">Ministerio de Educación</p>
                    <p class="mt-3 text-[17px] leading-[1.5] text-ink-soft">
                        Coordinación con el Ministerio de Culturas.
                    </p>
                </div>

                {{-- Ubicación --}}
                <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">Ubicación</p>
                    <p class="mt-3 text-[26px] font-bold leading-[1.2] text-ink max-md:text-[22px]">Zona Capachos</p>
                    <p class="mt-3 text-[17px] leading-[1.5] text-ink-soft">
                        Carretera Oruro – Cochabamba Km 13, Oruro.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Rectoría --}}
    <section class="py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <h2 class="text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">Rectoría 2026</h2>

            <div class="mt-8 flex items-center gap-[45px] rounded-[25px] bg-mist p-[35px] max-md:flex-col max-md:gap-6">
                <div class="h-[300px] w-[225px] shrink-0 overflow-hidden rounded-[25px] bg-white shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:h-[260px] max-md:w-[195px]">
                    @if ($rector && $rector->foto)
                        <img src="{{ $rector->foto }}" alt="Retrato del rector de la EBIM" class="h-full w-full object-cover">
                    @else
                        <img src="/assets/ebim/escudo-ebim.png" alt="Escudo de la EBIM" class="h-full w-full object-contain p-5">
                    @endif
                </div>

                <div class="flex-1">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Rector</p>

                    <h3 class="mt-2 text-[32px] font-bold leading-[1.2] text-ink max-md:text-[26px]">
                        {{ $rector->titulo ?? 'Nombre del rector' }}
                    </h3>

                    <p class="mt-1 text-[17px] font-medium text-ink-soft">Músico, compositor y antropólogo</p>

                    @if ($rector && trim($rector->cuerpo) !== '')
                        <div class="mt-5 flex flex-col gap-4 border-l-[3px] border-brand/25 pl-5">
                            @foreach (preg_split('/\n\s*\n/', trim($rector->cuerpo)) as $parrafo)
                                @if (trim($parrafo) !== '')
                                    <p class="text-[17px] leading-[1.55] text-ink">{{ trim($parrafo) }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Nuestra historia: línea de tiempo vertical centrada --}}
    <section class="bg-white py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <h2 class="text-center text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">
                {{ $cronologia->titulo ?? 'Nuestra historia' }}
            </h2>

            @if ($hitos !== [])
                <ol class="relative mx-auto mt-[50px] max-w-[1000px] max-md:mt-8">
                    {{-- Línea vertical central (sólo escritorio) --}}
                    <span class="absolute left-1/2 top-0 h-full w-[2px] -translate-x-1/2 bg-ink/15 max-md:hidden"
                        aria-hidden="true"></span>

                    {{-- Línea vertical lateral (sólo móvil) --}}
                    <span class="absolute left-[9px] top-0 hidden h-full w-[2px] bg-ink/15 max-md:block"
                        aria-hidden="true"></span>

                    @foreach ($hitos as $hito)
                        @php($izquierda = $loop->even)

                        <li class="relative pb-[45px] last:pb-0 max-md:pb-[35px]">
                            {{-- Escritorio: contenido alternado a los lados --}}
                            <div class="grid grid-cols-2 items-center gap-x-[60px] max-md:hidden">
                                @if ($izquierda)
                                    <div class="text-right">
                                        <p class="text-[15px] font-bold uppercase tracking-[1.5px] text-brand">{{ $hito['anio'] }}</p>
                                        <h3 class="mt-1 text-[24px] font-bold leading-[1.25] text-ink">{{ $hito['titulo'] }}</h3>
                                        @if ($hito['detalle'] !== '')
                                            <p class="mt-2 text-[17px] leading-[1.5] text-ink-soft">{{ $hito['detalle'] }}</p>
                                        @endif
                                    </div>
                                    <div></div>
                                @else
                                    <div></div>
                                    <div>
                                        <p class="text-[15px] font-bold uppercase tracking-[1.5px] text-brand">{{ $hito['anio'] }}</p>
                                        <h3 class="mt-1 text-[24px] font-bold leading-[1.25] text-ink">{{ $hito['titulo'] }}</h3>
                                        @if ($hito['detalle'] !== '')
                                            <p class="mt-2 text-[17px] leading-[1.5] text-ink-soft">{{ $hito['detalle'] }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Punto sobre la línea central --}}
                            <span class="absolute left-1/2 top-[10px] z-10 h-[18px] w-[18px] -translate-x-1/2 rounded-full bg-brand ring-4 ring-white max-md:hidden"
                                aria-hidden="true"></span>

                            {{-- Móvil: todo a la derecha de la línea lateral --}}
                            <div class="pl-[40px] md:hidden">
                                <p class="text-[15px] font-bold uppercase tracking-[1.5px] text-brand">{{ $hito['anio'] }}</p>
                                <h3 class="mt-1 text-[21px] font-bold leading-[1.25] text-ink">{{ $hito['titulo'] }}</h3>
                                @if ($hito['detalle'] !== '')
                                    <p class="mt-2 text-[17px] leading-[1.5] text-ink-soft">{{ $hito['detalle'] }}</p>
                                @endif
                            </div>

                            {{-- Punto sobre la línea lateral --}}
                            <span class="absolute left-[1px] top-[6px] z-10 hidden h-[18px] w-[18px] rounded-full bg-brand ring-4 ring-white max-md:block"
                                aria-hidden="true"></span>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="mt-6 text-center text-[19px] text-ink">La historia de la escuela estará disponible próximamente.</p>
            @endif
        </div>
    </section>

@endsection

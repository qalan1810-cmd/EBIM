@extends('layouts.app')

@section('title', 'Inscripción | EBIM Oruro')
@section('description', 'Requisitos y pasos para inscribirse en la Escuela Boliviana Intercultural de Música, Zona Capachos, Oruro. Modalidad presencial, régimen anual.')

@php
    $contact = config('ebim.contact');

    // Número de cuenta que se muestra en el requisito del depósito bancario.
    $cuentaBancaria = config('ebim.inscripcion.cuenta_bancaria');

    $listaRequisitos = $requisitos
        ? array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $requisitos->cuerpo)))
        : [];

    // Cada paso puede venir como "Título | Detalle"; si no hay barra,
    // la línea completa se muestra como un paso simple.
    $listaPasos = $pasos
        ? array_map(function (string $paso) {
            $partes = array_map('trim', explode('|', $paso, 2));

            return count($partes) === 2
                ? ['titulo' => $partes[0], 'detalle' => $partes[1]]
                : ['titulo' => null, 'detalle' => $partes[0]];
        }, array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $pasos->cuerpo)))))
        : [];

    // Solo se dibuja la ventana emergente si algún requisito tiene ejemplo.
    $hayEjemplos = collect($enlacesRequisitos)->contains('tipo', 'ejemplo');
@endphp

@section('content')

    <x-page-hero title="Inscripción" posicion-y="top">
        <p>Da el primer paso: revisa los requisitos, prepara tu audición y asegura tu lugar en la <strong>EBIM</strong>.</p>
    </x-page-hero>

    {{-- Requisitos --}}
    <section class="pt-[110px] pb-[60px] max-md:pt-[70px] max-md:pb-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="mx-auto max-w-[680px] text-center">
                <h2 class="text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">
                    {{ $requisitos->titulo ?? 'Requisitos de inscripción' }}
                </h2>

                {{-- Adorno: dos líneas y un punto central --}}
                <span class="mx-auto mt-5 flex items-center justify-center gap-3" aria-hidden="true">
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                    <span class="block h-[8px] w-[8px] rotate-45 bg-brand"></span>
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                </span>
            </div>

            @if (! empty($listaRequisitos))
                {{-- Ficha: columnas separadas por una regla guindo, sin tarjetas
                     repetidas, igual que los datos institucionales de Sobre EBIM. --}}
                <ul class="mx-auto mt-12 grid max-w-[1100px] grid-cols-2 gap-x-[45px] gap-y-[34px] max-md:mt-9 max-md:grid-cols-1 max-md:gap-y-[26px]">
                    @foreach ($listaRequisitos as $requisito)
                        <li class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                            <span class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">
                                Requisito {{ $loop->iteration }}
                            </span>
                            <p class="mt-3 text-[19px] leading-[1.5] text-ink">
                                {{ $requisito }}
                            </p>

                            @if ($cuentaBancaria && stripos($requisito, 'depósito bancario') !== false)
                                <p class="mt-2 text-[18px] font-bold tracking-[1px] text-brand max-md:text-[17px]">
                                    Cuenta N.º {{ $cuentaBancaria }}
                                </p>
                            @endif

                            @php $enlace = $enlacesRequisitos[$requisito] ?? null; @endphp

                            @if ($enlace)
                                <x-enlace-recurso
                                    :href="$enlace['href']"
                                    :tipo="$enlace['tipo']"
                                    :texto="$enlace['texto']"
                                    :alt="'Ejemplo del requisito: '.$requisito" />
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="page-prose mx-auto mt-8 max-w-[680px] text-center">
                    <p>Consulta los requisitos vigentes en la secretaría de la escuela.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Pasos --}}
    <section class="bg-white py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="mx-auto max-w-[680px] text-center">
                <span class="text-[14px] font-bold uppercase tracking-[3px] text-brand">Paso a paso</span>

                <h2 class="mt-3 text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">
                    {{ $pasos->titulo ?? 'Pasos del proceso de inscripción' }}
                </h2>

                <span class="mx-auto mt-5 flex items-center justify-center gap-3" aria-hidden="true">
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                    <span class="block h-[8px] w-[8px] rotate-45 bg-brand"></span>
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                </span>
            </div>

            @if (! empty($listaPasos))
                {{-- Línea de pasos: el número va en un círculo guindo y el texto
                     se cuelga de él, como una ruta clara de principio a fin.
                     Cada línea puede venir como "Título | Detalle": el título
                     se destaca y el detalle explica qué se hace en ese paso. --}}
                <ol class="relative mx-auto mt-12 max-w-[840px] max-md:mt-9">
                    {{-- Hilo vertical que une los pasos --}}
                    <span class="absolute left-[25px] top-[26px] h-[calc(100%-52px)] w-[2px] bg-ink/12 max-md:left-[21px] max-md:top-[22px] max-md:h-[calc(100%-44px)]" aria-hidden="true"></span>

                    @foreach ($listaPasos as $paso)
                        <li class="relative flex items-start gap-5 pb-[32px] last:pb-0">
                            <span class="relative z-10 flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-brand text-[20px] font-black text-white shadow-[0_8px_18px_rgba(188,28,44,0.28)] max-md:h-[44px] max-md:w-[44px] max-md:text-[18px]">
                                {{ $loop->iteration }}
                            </span>

                            <div class="pt-[6px] max-md:pt-[2px]">
                                @if ($paso['titulo'])
                                    <h3 class="text-[21px] font-bold leading-[1.3] text-ink max-md:text-[18px]">
                                        {{ $paso['titulo'] }}
                                    </h3>
                                    <p class="mt-[7px] text-[17px] leading-[1.55] text-ink-soft max-md:text-[16px]">
                                        {{ $paso['detalle'] }}
                                    </p>
                                @else
                                    <p class="text-[19px] leading-[1.55] text-ink max-md:text-[17px]">
                                        {{ $paso['detalle'] }}
                                    </p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @else
                <div class="page-prose mx-auto mt-8 max-w-[680px] text-center">
                    <p>El proceso de inscripción se detalla a continuación en secretaría.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Fechas y contacto: un par que contrasta, como Misión y Visión --}}
    <section class="py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="grid grid-cols-2 gap-[30px] max-md:grid-cols-1">
                {{-- Fechas y modalidad: tarjeta blanca con borde de tinta --}}
                <article class="relative overflow-hidden rounded-[25px] border-2 border-ink bg-white p-[40px] max-md:p-[30px]">
                    <h2 class="relative text-[32px] font-bold leading-[1.3] text-ink max-md:text-[26px]">
                        Fechas y modalidad
                    </h2>

                    <span class="mt-4 block h-[4px] w-[60px] bg-brand" aria-hidden="true"></span>

                    <ul class="relative mt-5 flex flex-col gap-[14px]">
                        <li class="flex items-start gap-3 text-[17px] leading-[1.5] text-ink">
                            <span class="mt-[9px] h-[8px] w-[8px] shrink-0 rotate-45 bg-brand" aria-hidden="true"></span>
                            <span>Las inscripciones se reciben hasta el
                                <strong>{{ config('ebim.inscripcion.fecha_limite') }}</strong>.</span>
                        </li>
                        <li class="flex items-start gap-3 text-[17px] leading-[1.5] text-ink">
                            <span class="mt-[9px] h-[8px] w-[8px] shrink-0 rotate-45 bg-brand" aria-hidden="true"></span>
                            La recepción de documentos se realiza en la secretaría de la sede, en horario de oficina.
                        </li>
                        <li class="flex items-start gap-3 text-[17px] leading-[1.5] text-ink">
                            <span class="mt-[9px] h-[8px] w-[8px] shrink-0 rotate-45 bg-brand" aria-hidden="true"></span>
                            Los cupos por especialidad instrumental están sujetos a disponibilidad.
                        </li>
                        <li class="flex items-start gap-3 text-[17px] leading-[1.5] text-ink">
                            <span class="mt-[9px] h-[8px] w-[8px] shrink-0 rotate-45 bg-brand" aria-hidden="true"></span>
                            <span>La inscripción es <strong>presencial</strong>.</span>
                        </li>
                    </ul>
                </article>

                {{-- Contacto: tarjeta guindo sólida, como la Visión --}}
                <article class="relative overflow-hidden rounded-[25px] bg-brand p-[40px] text-white max-md:p-[30px]">
                    <span class="absolute -right-[40px] -top-[40px] h-[150px] w-[150px] rounded-full bg-white/10" aria-hidden="true"></span>

                    <div class="relative">
                        <h2 class="text-[32px] font-bold leading-[1.3] text-white max-md:text-[26px]">
                            ¿Tienes dudas?
                        </h2>

                        <span class="mt-4 block h-[4px] w-[60px] bg-white" aria-hidden="true"></span>

                        <p class="mt-5 text-[17px] leading-[1.5] text-white/90">
                            Comunícate con la escuela para consultar por la oferta vigente, los aranceles y la disponibilidad de cupos.
                        </p>

                        <ul class="mt-5 flex flex-col gap-[10px] text-[17px] text-white">
                            <li><strong>Teléfono:</strong> <a href="{{ $contact['phone_href'] }}" class="underline">{{ $contact['phone'] }}</a></li>
                            <li><strong>Correo:</strong> <a href="mailto:{{ $contact['email'] }}" class="underline">{{ $contact['email'] }}</a></li>
                            <li><strong>Dirección:</strong> {{ $contact['address_short'] }}</li>
                        </ul>

                        <div class="mt-7">
                            <a href="{{ url('/contacto') }}" class="btn-claro">
                                Ir a contacto
                                <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14"></path>
                                    <path d="M13 6l6 6-6 6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    @if ($hayEjemplos)
        {{-- Ventana emergente de los ejemplos: contiene únicamente la imagen
             del documento, sin lector de PDF y sin salir de la página. --}}
        <div id="modal-ejemplo" class="ss-modal" hidden>
            <div class="ss-modal-fondo" data-modal-cerrar></div>

            <div class="ss-modal-caja" role="dialog" aria-modal="true"
                aria-label="Ejemplo del documento solicitado">
                <button type="button" class="ss-modal-cerrar" data-modal-cerrar
                    aria-label="Cerrar el ejemplo">
                    <svg class="h-[16px] w-[16px]" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                        aria-hidden="true">
                        <path d="M6 6l12 12"></path>
                        <path d="M18 6L6 18"></path>
                    </svg>
                </button>

                <img id="modal-ejemplo-img" class="ss-modal-imagen" alt="" decoding="async">
            </div>
        </div>
    @endif

@endsection

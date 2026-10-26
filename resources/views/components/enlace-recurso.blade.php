@props([
    'href',
    'tipo' => 'descarga',
    'texto',
    'alt' => '',
])

{{--
    Enlace que acompaña a un requisito de inscripción:

    - descarga: PDF que se abre en una pestaña nueva para imprimir.
    - ejemplo:  imagen del documento que se abre en una ventana emergente,
                sin salir de la página ni descargar nada.
    - drive:    carpeta externa con el material de estudio.
--}}
@php
    $clases = 'mt-[14px] inline-flex items-center gap-[9px] text-[12px] font-bold uppercase tracking-[2px] text-brand no-underline hover:text-ink hover:underline hover:underline-offset-[4px]';
@endphp

@if ($tipo === 'ejemplo')
    <button type="button"
        data-ejemplo-src="{{ $href }}"
        data-ejemplo-alt="{{ $alt ?: $texto }}"
        aria-haspopup="dialog"
        class="{{ $clases }} cursor-pointer border-0 bg-transparent p-0 text-left">

        {{-- Ojo: ver el ejemplo --}}
        <svg class="h-[14px] w-[14px] shrink-0" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>

        {{ $texto }}
    </button>
@else
    <a href="{{ $href }}"
        target="_blank"
        rel="noopener noreferrer"
        class="{{ $clases }}">

        @if ($tipo === 'drive')
            {{-- Carpeta externa --}}
            <svg class="h-[14px] w-[14px] shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <path d="M15 3h6v6"></path>
                <path d="M10 14 21 3"></path>
            </svg>
        @else
            {{-- Flecha hacia abajo: descargar --}}
            <svg class="h-[14px] w-[14px] shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="M12 3v12"></path>
                <path d="m7 10 5 5 5-5"></path>
                <path d="M5 21h14"></path>
            </svg>
        @endif

        {{ $texto }}
    </a>
@endif

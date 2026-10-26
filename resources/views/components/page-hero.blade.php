@props(['title', 'slug' => null, 'posicionY' => 'center'])

{{--
    Encabezado de página con fondo fotográfico.

    Las fotos se toman de public/assets/ebim/paginas/<slug>/ y se van cambiando
    solas cada 5 s (mismo motor que el hero de inicio). Se aceptan .jpg .jpeg
    .png .webp .avif, en cualquier orden; se ordenan de forma natural para que
    1.jpg 2.jpg 10.jpg cambien siempre en ese orden. Si no se pasa $slug se usa
    el segmento de la URL (por ejemplo /oferta-academica → "oferta-academica").

    Si la carpeta no existe o está vacía, la página conserva exactamente su
    encabezado claro original (sin fotos inventadas).

    $title     -> título de la pestaña.
    $posicionY -> opcional, anclaje vertical del recorte (cover). "center" por
                  defecto; "top" muestra más la parte alta de la foto y "bottom"
                  más el pie. Sirve para ajustar foto por foto sin tocar CSS.
--}}
@php
    $slug = $slug ?: (request()->segment(1) ?? '');
    $relativa = 'assets/ebim/paginas/'.$slug;
    $carpeta = public_path($relativa);

    $slides = [];

    if ($slug !== '' && is_dir($carpeta)) {
        $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
        $archivos = [];

        foreach (scandir($carpeta) as $archivo) {
            if (is_file($carpeta.'/'.$archivo)
                && in_array(strtolower(pathinfo($archivo, PATHINFO_EXTENSION)), $permitidas, true)) {
                $archivos[] = $archivo;
            }
        }

        // 1.jpg, 2.jpg, 10.jpg — orden natural, sin distinguir mayúsculas.
        sort($archivos, SORT_NATURAL | SORT_FLAG_CASE);

        foreach ($archivos as $archivo) {
            $slides[] = '/'.$relativa.'/'.$archivo;
        }
    }

    $conFotos = $slides !== [];
@endphp

@if ($conFotos)
    <section class="page-hero overflow-hidden" data-carousel-hero>
        @foreach ($slides as $indice => $slide)
            {{-- La primera foto arranca visible; las demas esperan ocultas. La
                 deriva del movimiento alterna de lado foto por foto. --}}
            <div data-hero-slide
                @class([
                    'page-hero-slide',
                    'is-active' => $indice === 0,
                    'page-hero-slide--izq' => $indice % 2 === 0,
                    'page-hero-slide--der' => $indice % 2 !== 0,
                ])
                style="background-image: url('{{ $slide }}'); background-position: center {{ $posicionY }};"
                aria-hidden="true"></div>
        @endforeach

        {{-- Velo: deja la foto visible y garantiza contraste bajo el texto. --}}
        <div class="page-hero-veil" aria-hidden="true"></div>

        <div class="ebim-container relative z-10">
            <h1 class="text-[64px] font-black leading-[1] text-white max-lg:text-[48px] max-md:text-[36px]">{{ $title }}</h1>

            <div class="page-prose mt-6 max-w-[900px]">
                {{ $slot }}
            </div>
        </div>
    </section>
@else
    <section class="pt-[150px] pb-[40px] max-md:pt-[110px]">
        <div class="ebim-container">
            <h1 class="text-[64px] font-black leading-[1] text-ink max-lg:text-[48px] max-md:text-[36px]">{{ $title }}</h1>

            <div class="page-prose mt-6 max-w-[900px]">
                {{ $slot }}
            </div>
        </div>
    </section>
@endif

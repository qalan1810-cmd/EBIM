@props(['noticia', 'destacada' => false])

@php
    $esComunicado = $noticia->tipo === \App\Models\Noticia::TIPO_COMUNICADO;
    $video = $noticia->urlIncrustable();
    $imagen = $noticia->imagenUrl();
@endphp

<article {{ $attributes->merge(['class' => 'flex h-full flex-col overflow-hidden rounded-[25px] bg-white shadow-[0_0_12px_rgba(0,0,0,0.12)]']) }}>

    {{-- Multimedia: el video o la fotografía se muestra dentro de la propia
         página, sin que el visitante tenga que abrir el enlace. --}}
    @if ($video)
        <div class="relative w-full overflow-hidden bg-ink/5 pt-[56.25%]">
            <iframe
                src="{{ $video }}"
                title="{{ $noticia->titulo }}"
                class="absolute inset-0 h-full w-full"
                style="border:0"
                loading="lazy"
                scrolling="no"
                frameborder="0"
                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                allowfullscreen></iframe>
        </div>
    @elseif ($imagen)
        <div class="h-[220px] w-full bg-cover bg-center" style="background-image: url('{{ $imagen }}');"
            role="img" aria-label="{{ $noticia->titulo }}"></div>
    @endif

    <div class="flex flex-1 flex-col p-[28px]">
        <p class="flex flex-wrap items-center gap-3 text-[14px] font-bold uppercase tracking-[1.5px]">
            <span class="{{ $esComunicado ? 'text-brand' : 'text-slate' }}">{{ $noticia->etiquetaTipo() }}</span>
            <span class="text-ink/40">·</span>
            <span class="text-brand">{{ optional($noticia->fecha)->format('d/m/Y') }}</span>
        </p>

        <h3 class="mt-3 text-[{{ $destacada ? '30' : '24' }}px] font-bold leading-[1.2] text-ink">{{ $noticia->titulo }}</h3>

        @if ($noticia->resumen)
            <p class="mt-3 text-[17px] leading-[1.5] text-ink">{!! nl2br(e($noticia->resumen)) !!}</p>
        @endif

        @if ($noticia->cuerpo)
            <div class="mt-3 text-[17px] leading-[1.5] text-ink">{!! nl2br(e($noticia->cuerpo)) !!}</div>
        @endif

        {{-- Cuando el enlace no se puede incrustar (Facebook no permite ver sus
             publicaciones fuera de Facebook) se ofrece como enlace claro. --}}
        @if ($noticia->enlace && ! $video)
            <p class="mt-4">
                <a href="{{ $noticia->enlace }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 rounded-[15px] border-2 border-ink px-5 py-[8px] text-[16px] font-bold text-ink hover:bg-ink hover:text-white">
                    Ver en Facebook
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </p>
        @endif
    </div>
</article>

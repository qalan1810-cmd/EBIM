@props(['docente'])

@php
    $iniciales = collect(explode(' ', trim($docente->nombre)))
        ->filter()
        ->take(2)
        ->map(fn ($parte) => mb_strtoupper(mb_substr($parte, 0, 1)))
        ->implode('');
@endphp

<article {{ $attributes->merge(['class' => 'flex h-full flex-col overflow-hidden rounded-[25px] bg-white shadow-[0_0_12px_rgba(0,0,0,0.12)]']) }}>
    @if ($docente->foto)
        <div class="h-[240px] w-full bg-cover"
            style="background-image: url('{{ asset($docente->foto) }}'); background-position: {{ $docente->posicionFoto() }}"
            role="img" aria-label="{{ $docente->nombre }}"></div>
    @else
        <div class="flex h-[240px] w-full items-center justify-center bg-ink">
            <span class="text-[54px] font-extrabold tracking-[2px] text-white">{{ $iniciales }}</span>
        </div>
    @endif

    <div class="flex flex-1 flex-col p-[28px]">
        <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">{{ $docente->cargo }}</p>
        <h3 class="mt-2 text-[24px] font-bold leading-[1.2] text-ink">{{ $docente->nombre }}</h3>
        <p class="mt-2 text-[17px] font-semibold text-ink-soft">{{ $docente->especialidad }}</p>

        <div class="mt-4 text-[16px] leading-[1.5] text-ink">
            <p class="font-bold">Formación</p>
            <p>{{ $docente->formacion }}</p>
        </div>

        <div class="mt-4 text-[16px] leading-[1.5] text-ink">
            <p class="font-bold">Experiencia</p>
            <p>{{ $docente->experiencia }}</p>
        </div>
    </div>
</article>

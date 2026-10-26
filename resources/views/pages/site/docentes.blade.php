@extends('layouts.app')

@section('title', 'Plantel Docente | EBIM Oruro')
@section('description', 'Conoce a los docentes de la Escuela Boliviana Intercultural de Música: su formación profesional, especialidad y trayectoria.')

@section('content')

    {{-- La pestaña se llama "Docentes"; el encabezado conserva "Plantel Docente". --}}
    <x-page-hero title="Plantel Docente" posicion-y="top">
        <p>Los músicos <strong>profesionales</strong> que forman a las nuevas generaciones: conoce su <strong>formación</strong>, su <strong>especialidad</strong> y su <strong>trayectoria</strong>.</p>
    </x-page-hero>

    <section class="pt-[70px] pb-[80px] max-md:pt-[50px] max-md:pb-[60px]">
        <div class="ebim-container">
            @if ($docentes->isEmpty())
                <p class="text-[20px] text-ink" data-reveal>La información del plantel docente estará disponible próximamente.</p>
            @else
                {{-- Cada tarjeta entra por separado, igual que las fotos de las
                     especialidades: el JavaScript las escalona para que
                     aparezcan una tras otra. --}}
                <div class="grid grid-cols-3 gap-[20px] max-lg:grid-cols-2 max-md:grid-cols-1">
                    @foreach ($docentes as $docente)
                        <x-docente-card :docente="$docente" data-reveal data-reveal-grupo />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection

@extends('layouts.app')

@section('title', 'Panel de Administración | EBIM Oruro')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[52px] font-black leading-[1] text-white max-lg:text-[42px] max-md:text-[32px]">Panel de Administración</h1>
        <p class="mt-4 text-[20px] text-white max-md:text-[18px]">
            Bienvenido, <strong>{{ auth()->user()->name }}</strong>.
            {{ $esRector
                ? 'Desde aquí administra el sitio y aprueba los horarios elaborados por el director académico.'
                : 'Desde aquí elabora el horario de clases de cada curso y envíalo al rector para su aprobación.' }}
        </p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        @if ($esRector)
            <div class="grid grid-cols-3 gap-[20px] max-lg:grid-cols-2 max-md:grid-cols-1">
                <a href="{{ route('admin.docentes.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Docentes</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $estadisticas['docentes'] }}</p>
                </a>
                <a href="{{ route('admin.noticias.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Noticias</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $estadisticas['noticias'] }}</p>
                </a>
                <a href="{{ route('admin.usuarios.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Estudiantes</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $estadisticas['estudiantes'] }}</p>
                </a>
                <a href="{{ route('admin.notas.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Notas</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $estadisticas['notas'] }}</p>
                </a>
                <a href="{{ route('admin.horarios.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Horarios</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $horarios['asignaturas'] }}</p>
                    <p class="mt-1 text-[15px] text-slate">en {{ $horarios['cursos'] }} cursos</p>
                </a>
                <a href="{{ route('admin.contenidos.edit') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Contenidos</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $estadisticas['contenidos'] }}</p>
                </a>
            </div>
        @else
            <div class="grid grid-cols-4 gap-[20px] max-lg:grid-cols-2 max-md:grid-cols-1">
                <a href="{{ route('admin.horarios.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">En elaboración</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $horarios['borrador'] }}</p>
                </a>
                <a href="{{ route('admin.horarios.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Con el rector</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $horarios['enviado'] }}</p>
                </a>
                <a href="{{ route('admin.horarios.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Devueltos</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $horarios['rechazado'] }}</p>
                </a>
                <a href="{{ route('admin.horarios.index') }}" class="rounded-[20px] bg-white p-[28px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <p class="text-[14px] font-bold uppercase tracking-[1.5px] text-brand">Aprobados</p>
                    <p class="mt-2 text-[44px] font-black leading-none text-ink">{{ $horarios['aprobado'] }}</p>
                </a>
            </div>
        @endif

        {{-- Horarios pendientes de aprobación (rector) --}}
        @if ($esRector && $pendientes->isNotEmpty())
            <div class="mt-[30px] rounded-[20px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                <h2 class="text-[24px] font-bold text-ink">Horarios pendientes de aprobación</h2>
                <p class="mt-2 text-[16px] text-slate">El director académico envió estos horarios para su revisión.</p>

                <ul class="mt-5 flex flex-col gap-3">
                    @foreach ($pendientes as $pendiente)
                        <li class="flex flex-wrap items-center justify-between gap-3 rounded-[15px] bg-mist p-4">
                            <div>
                                <p class="text-[18px] font-bold text-ink">Curso {{ $pendiente->nombre }}</p>
                                <p class="text-[15px] text-slate">
                                    Enviado{{ $pendiente->enviadoPor ? ' por ' . $pendiente->enviadoPor->name : '' }}
                                    @if ($pendiente->horario_enviado_en)
                                        el {{ $pendiente->horario_enviado_en->format('d/m/Y H:i') }}
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('admin.horarios.index', ['curso' => $pendiente->id]) }}" class="btn-ss">Revisar horario</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Horarios devueltos al director académico --}}
        @unless ($esRector)
            @if ($devueltos->isNotEmpty())
                <div class="mt-[30px] rounded-[20px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <h2 class="text-[24px] font-bold text-ink">Horarios devueltos por el rector</h2>
                    <p class="mt-2 text-[16px] text-slate">Corrija lo observado y vuelva a enviarlos a aprobación.</p>

                    <ul class="mt-5 flex flex-col gap-3">
                        @foreach ($devueltos as $devuelto)
                            <li class="rounded-[15px] bg-mist p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <p class="text-[18px] font-bold text-ink">Curso {{ $devuelto->nombre }}</p>
                                    <a href="{{ route('admin.horarios.index', ['curso' => $devuelto->id]) }}" class="btn-ss">Corregir horario</a>
                                </div>
                                @if ($devuelto->horario_observacion)
                                    <p class="mt-2 text-[16px] leading-[1.5] text-ink">
                                        <strong class="text-brand">Observación:</strong> {{ $devuelto->horario_observacion }}
                                    </p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endunless

        <div class="mt-[30px] grid grid-cols-2 gap-[20px] max-md:grid-cols-1">
            @if ($esRector)
                <div class="rounded-[20px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <h2 class="text-[24px] font-bold text-ink">Información institucional</h2>
                    <p class="mt-3 text-[17px] leading-[1.5] text-ink">
                        Actualiza la historia, misión, visión, valores, requisitos y pasos de inscripción que se muestran en el sitio público.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('admin.contenidos.edit') }}" class="btn-ss">Editar contenidos</a>
                    </div>
                </div>
            @endif

            <div class="rounded-[20px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                <h2 class="text-[24px] font-bold text-ink">{{ $esRector ? 'Información académica' : 'Horario de clases' }}</h2>
                <p class="mt-3 text-[17px] leading-[1.5] text-ink">
                    {{ $esRector
                        ? 'Revise los horarios que envía el director académico: apruébelos o devuélvalos con observaciones.'
                        : 'Arme el horario de cada curso en una tabla con los días como columnas y las horas como filas, y envíelo al rector.' }}
                </p>
                <div class="mt-5 flex flex-wrap gap-3">
                    @if ($esRector)
                        <a href="{{ route('admin.notas.index') }}" class="btn-ss">Ver notas</a>
                    @endif
                    <a href="{{ route('admin.horarios.index') }}" class="btn-ss">
                        {{ $esRector ? 'Ver horarios por curso' : 'Elaborar horarios' }}
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

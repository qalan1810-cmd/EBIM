@extends('layouts.app')

@php
    $esRector = auth()->user()->esAdmin();
    $esDirector = auth()->user()->esDirector();

    // El director académico solo puede tocar la rejilla mientras el horario
    // esté en elaboración o cuando el rector se lo haya devuelto.
    $editable = $esDirector && $curso && $curso->esEditable();

    $badges = [
        \App\Models\Curso::ESTADO_BORRADOR => 'bg-mist text-ink',
        \App\Models\Curso::ESTADO_ENVIADO => 'bg-[#fdecee] text-brand',
        \App\Models\Curso::ESTADO_APROBADO => 'bg-ink text-white',
        \App\Models\Curso::ESTADO_RECHAZADO => 'bg-brand text-white',
    ];
@endphp

@section('title', 'Horarios — Administración | EBIM Oruro')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Horarios</h1>
        <p class="mt-3 text-[19px] text-white">
            Horario de clases por curso: los días son las columnas y las horas, las filas.
            {{ $esRector
                ? 'Revise el horario que envía el director académico y apruébelo o devuélvalo con observaciones.'
                : 'Arme el horario de cada curso y envíelo al rector para su aprobación.' }}
        </p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">

        {{-- Horarios que esperan la firma del rector --}}
        @if ($esRector && $pendientes->isNotEmpty())
            <div class="mb-[30px] rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
                <h2 class="text-[26px] font-bold leading-[1.3] text-ink">Horarios pendientes de aprobación</h2>
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

        {{-- Rejilla del curso seleccionado --}}
        <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    @forelse ($cursos as $opcion)
                        <a href="{{ route('admin.horarios.index', ['curso' => $opcion->id]) }}"
                            class="rounded-[15px] px-5 py-3 text-[16px] font-bold {{ $curso && $curso->id === $opcion->id ? 'bg-ink text-white' : 'border-2 border-ink/20 text-ink hover:border-ink' }}">
                            {{ $opcion->nombre }}
                        </a>
                    @empty
                        <p class="text-[17px] text-ink">Todavía no hay cursos registrados. Crea el primero aquí abajo.</p>
                    @endforelse
                </div>

                @if ($editable)
                    <a href="{{ route('admin.horarios.create', ['curso' => $curso->id]) }}" class="btn-ss">Nuevo horario</a>
                @endif
            </div>

            @if ($curso)
                <div class="mt-6 rounded-[20px] border-2 border-ink/10 bg-mist p-[22px]">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full px-4 py-1 text-[14px] font-bold {{ $badges[$curso->estado_horario] ?? 'bg-mist text-ink' }}">
                                {{ $curso->estadoEtiqueta() }}
                            </span>

                            @php
                                $detalle = match ($curso->estado_horario) {
                                    \App\Models\Curso::ESTADO_ENVIADO => 'Enviado' . ($curso->enviadoPor ? ' por ' . $curso->enviadoPor->name : '') . ($curso->horario_enviado_en ? ' el ' . $curso->horario_enviado_en->format('d/m/Y H:i') : '') . '. ' . ($esRector ? 'Revise el horario y apruébelo o devuélvalo con observaciones.' : 'Esperando la aprobación del rector.'),
                                    \App\Models\Curso::ESTADO_APROBADO => 'Aprobado' . ($curso->revisadoPor ? ' por ' . $curso->revisadoPor->name : '') . ($curso->horario_revisado_en ? ' el ' . $curso->horario_revisado_en->format('d/m/Y H:i') : '') . '. ' . ($esDirector ? 'Para modificarlo, el rector debe devolverlo primero.' : 'El curso ya lo consulta en su portal.'),
                                    \App\Models\Curso::ESTADO_RECHAZADO => 'Devuelto' . ($curso->horario_revisado_en ? ' el ' . $curso->horario_revisado_en->format('d/m/Y H:i') : '') . '. ' . ($esDirector ? 'Corrija lo observado y vuelva a enviarlo a aprobación.' : 'A la espera de que el director académico lo corrija.'),
                                    default => $esDirector
                                        ? 'En elaboración. Cuando esté completo, envíelo al rector para su aprobación.'
                                        : 'El director académico todavía está elaborando este horario.',
                                };
                            @endphp

                            <p class="text-[16px] text-ink">{{ $detalle }}</p>
                        </div>

                        @if ($editable)
                            <form method="POST" action="{{ route('admin.horarios.enviar', $curso) }}"
                                onsubmit="return confirm('¿Enviar el horario del curso {{ $curso->nombre }} al rector para su aprobación? Mientras lo revise no podrá modificarlo.');">
                                @csrf
                                <button type="submit" class="btn-ss">Enviar al rector para aprobación</button>
                            </form>
                        @elseif ($esRector && $curso->estado_horario === \App\Models\Curso::ESTADO_ENVIADO)
                            <form method="POST" action="{{ route('admin.horarios.aprobar', $curso) }}"
                                onsubmit="return confirm('¿Aprobar el horario del curso {{ $curso->nombre }}?');">
                                @csrf
                                <button type="submit" class="btn-ss">Aprobar horario</button>
                            </form>
                        @endif
                    </div>

                    @if ($curso->horario_observacion && $curso->estado_horario === \App\Models\Curso::ESTADO_RECHAZADO)
                        <p class="mt-4 rounded-[15px] bg-white p-4 text-[16px] leading-[1.5] text-ink">
                            <strong class="text-brand">Observación del rector:</strong> {{ $curso->horario_observacion }}
                        </p>
                    @endif

                    @if ($esRector && in_array($curso->estado_horario, [\App\Models\Curso::ESTADO_ENVIADO, \App\Models\Curso::ESTADO_APROBADO], true))
                        <div class="mt-5 border-t border-ink/10 pt-5">
                            @if ($curso->estado_horario === \App\Models\Curso::ESTADO_APROBADO)
                                <p class="mb-4 text-[15px] leading-[1.5] text-slate">
                                    Si necesita corregir un horario ya aprobado, devuélvalo al director académico.
                                    El curso dejará de verlo hasta que se apruebe de nuevo.
                                </p>
                            @endif

                            <form method="POST" action="{{ route('admin.horarios.rechazar', $curso) }}" class="flex flex-wrap items-end gap-4">
                                @csrf

                                <div class="grow">
                                    <label for="observacion" class="mb-2 block text-[15px] font-bold text-ink">
                                        Observación para el director académico
                                    </label>
                                    <input id="observacion" name="observacion" type="text" required maxlength="500"
                                        value="{{ old('observacion') }}"
                                        placeholder="Ej. Falta cargar las horas de Lenguaje musical"
                                        class="w-full min-w-[280px] rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                                    @error('observacion') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                                </div>

                                <button type="submit"
                                    class="rounded-[15px] border-2 border-brand px-6 py-[10px] text-[18px] font-bold text-brand hover:bg-brand hover:text-white">
                                    {{ $curso->estado_horario === \App\Models\Curso::ESTADO_APROBADO ? 'Devolver para corrección' : 'Devolver con observaciones' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <p class="mt-6 text-[16px] text-slate">
                    Horario del curso <strong class="text-ink">{{ $curso->nombre }}</strong>.
                    @unless ($editable)
                        <span class="font-semibold text-ink">Solo lectura.</span>
                    @endunless
                </p>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[1080px] border-collapse">
                        <thead>
                            <tr class="bg-ink text-left text-white">
                                <th class="w-[130px] px-4 py-3 text-[16px] font-bold">Hora</th>
                                @foreach ($rejilla['dias'] as $dia)
                                    <th class="px-4 py-3 text-[16px] font-bold">{{ $dia }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rejilla['franjas'] as $franja)
                                <tr class="border-b border-mist align-top">
                                    <th class="px-4 py-3 text-left align-top">
                                        <span class="block text-[16px] font-bold text-ink">{{ $franja['hora_inicio'] }}</span>
                                        <span class="block text-[14px] font-semibold text-slate">a {{ $franja['hora_fin'] }}</span>
                                    </th>

                                    @foreach ($rejilla['dias'] as $dia)
                                        @php($celda = $franja['celdas'][$dia] ?? null)

                                        <td class="px-2 py-2 align-top">
                                            @if ($celda)
                                                <div class="rounded-[15px] border-2 border-ink/10 bg-mist p-3">
                                                    <p class="text-[16px] font-bold leading-[1.3] text-ink">{{ $celda->asignatura }}</p>
                                                    <p class="mt-1 text-[14px] leading-[1.3] text-slate">{{ $celda->docente }}</p>

                                                    @if ($celda->ambiente)
                                                        <p class="mt-1 text-[13px] font-bold uppercase tracking-[1px] text-brand">{{ $celda->ambiente }}</p>
                                                    @endif

                                                    @if ($editable)
                                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                                            <a href="{{ route('admin.horarios.edit', $celda) }}"
                                                                class="rounded-[10px] bg-ink px-3 py-1 text-[13px] font-bold text-white hover:bg-brand">Editar</a>
                                                            <form method="POST" action="{{ route('admin.horarios.destroy', $celda) }}"
                                                                onsubmit="return confirm('¿Quitar esta asignatura del horario?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="rounded-[10px] bg-brand px-3 py-1 text-[13px] font-bold text-white hover:bg-ink">Quitar</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif ($editable)
                                                <a href="{{ route('admin.horarios.create', ['curso' => $curso->id, 'dia' => $dia, 'hora_inicio' => $franja['hora_inicio'], 'hora_fin' => $franja['hora_fin']]) }}"
                                                    class="flex h-full min-h-[76px] items-center justify-center rounded-[15px] border-2 border-dashed border-ink/15 text-[15px] font-semibold text-slate hover:border-brand hover:text-brand">
                                                    + Añadir
                                                </a>
                                            @else
                                                <div class="flex h-full min-h-[76px] items-center justify-center rounded-[15px] bg-mist/60 text-[15px] font-semibold text-slate">
                                                    Libre
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-[17px] text-ink">
                                        {{ $editable
                                            ? 'Este curso todavía no tiene horario. Usa "Nuevo horario" para registrar la primera asignatura.'
                                            : 'Este curso todavía no tiene horario registrado.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Cursos: solo el director académico crea y elimina grupos. La
             rectoría ya tiene los cursos como pestañas arriba, así que no
             necesita esta tabla. --}}
        @if ($esDirector)
            <div class="mt-[30px] rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
                <h2 class="text-[26px] font-bold leading-[1.3] text-ink">Cursos</h2>
                <p class="mt-2 text-[16px] text-slate">Crea un curso por cada grupo (por ejemplo 2 "A") y asígnale su horario.</p>

                <form method="POST" action="{{ route('admin.cursos.store') }}" class="mt-5 flex flex-wrap items-end gap-4">
                    @csrf

                    <div>
                        <label for="nombre" class="mb-2 block text-[15px] font-bold text-ink">Nombre del curso</label>
                        <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required
                            placeholder='Ej. 2 "A"'
                            class="w-[220px] rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('nombre') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="orden" class="mb-2 block text-[15px] font-bold text-ink">Orden</label>
                        <input id="orden" name="orden" type="number" min="0" max="999" value="{{ old('orden', 0) }}"
                            class="w-[110px] rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('orden') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-ss">Crear curso</button>
                </form>

                @if ($cursos->isNotEmpty())
                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full min-w-[820px] border-collapse">
                            <thead>
                                <tr class="bg-ink text-left text-white">
                                    <th class="px-4 py-3 text-[16px] font-bold">Curso</th>
                                    <th class="px-4 py-3 text-[16px] font-bold">Orden</th>
                                    <th class="px-4 py-3 text-[16px] font-bold">Asignaturas</th>
                                    <th class="px-4 py-3 text-[16px] font-bold">Estudiantes</th>
                                    <th class="px-4 py-3 text-[16px] font-bold">Estado del horario</th>
                                    <th class="px-4 py-3 text-[16px] font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cursos as $opcion)
                                    <tr class="border-b border-mist">
                                        <td class="px-4 py-3 text-[16px] font-semibold text-ink">{{ $opcion->nombre }}</td>
                                        <td class="px-4 py-3 text-[16px] text-ink">{{ $opcion->orden }}</td>
                                        <td class="px-4 py-3 text-[16px] text-ink">{{ $opcion->horarios_count }}</td>
                                        <td class="px-4 py-3 text-[16px] text-ink">{{ $opcion->estudiantes_count }}</td>
                                        <td class="px-4 py-3">
                                            <span class="rounded-full px-3 py-1 text-[14px] font-bold {{ $badges[$opcion->estado_horario] ?? 'bg-mist text-ink' }}">
                                                {{ $opcion->estadoEtiqueta() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a href="{{ route('admin.horarios.index', ['curso' => $opcion->id]) }}"
                                                    class="rounded-[10px] bg-ink px-3 py-2 text-[14px] font-bold text-white hover:bg-brand">Ver horario</a>
                                                <form method="POST" action="{{ route('admin.cursos.destroy', $opcion) }}"
                                                    onsubmit="return confirm('¿Eliminar el curso {{ $opcion->nombre }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="rounded-[10px] bg-brand px-3 py-2 text-[14px] font-bold text-white hover:bg-ink">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </section>

@endsection

@extends('layouts.app')

@section('title', 'Portal del Estudiante | EBIM Oruro')
@section('description', 'Consulta tus notas por gestión y parciales, y revisa tu horario de clases en la Escuela Boliviana Intercultural de Música.')

@section('content')

    <section class="pt-[40px] pb-[30px] max-md:pt-[30px]">
        <div class="ebim-container" data-reveal>
            <h1 class="text-[56px] font-black leading-[1] text-white max-lg:text-[44px] max-md:text-[34px]">
                Hola, {{ $estudiante->primerNombre() }}
            </h1>
        </div>
    </section>

    {{-- Perfil --}}
    <section id="perfil" class="scroll-mt-[130px] pb-[30px]">
        <div class="ebim-container" data-reveal>
            <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
                <h2 class="text-[32px] font-bold leading-[1.3] text-ink max-md:text-[26px]">Mi perfil</h2>

                <dl class="mt-6 grid grid-cols-1 gap-[20px] md:grid-cols-2 lg:grid-cols-[1.15fr_1.25fr_0.65fr_1.6fr_1.15fr]">
                    <div class="min-w-0">
                        <dt class="flex items-end whitespace-nowrap text-[12px] font-bold uppercase tracking-[0.5px] text-brand md:h-[60px] lg:h-[44px]">Nombre completo</dt>
                        <dd class="mt-1 break-words text-[18px] text-ink">{{ $estudiante->name }}</dd>
                    </div>
                    <div class="min-w-0">
                        <dt class="flex items-end whitespace-nowrap text-[12px] font-bold uppercase tracking-[0.5px] text-brand md:h-[60px] lg:h-[44px]">Código de estudiante</dt>
                        <dd class="mt-1 break-words text-[18px] text-ink">{{ $estudiante->codigo ?: 'No asignado' }}</dd>
                    </div>
                    <div class="min-w-0">
                        <dt class="flex items-end whitespace-nowrap text-[12px] font-bold uppercase tracking-[0.5px] text-brand md:h-[60px] lg:h-[44px]">Curso</dt>
                        <dd class="mt-1 break-words text-[18px] text-ink">{{ $curso->nombre ?? 'No asignado' }}</dd>
                    </div>
                    <div class="min-w-0">
                        <dt class="flex items-end whitespace-nowrap text-[12px] font-bold uppercase tracking-[0.5px] text-brand md:h-[60px] lg:h-[44px]">Instrumento de especialidad</dt>
                        <dd class="mt-1 break-words text-[18px] text-ink">{{ $estudiante->especialidad ?: 'No asignado' }}</dd>
                    </div>
                    <div class="min-w-0">
                        <dt class="flex items-end whitespace-nowrap text-[12px] font-bold uppercase tracking-[0.5px] text-brand md:h-[60px] lg:h-[44px]">Carnet de identidad</dt>
                        <dd class="mt-1 break-words text-[18px] text-ink">{{ $estudiante->carnet ?: 'No asignado' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    {{-- Notas --}}
    <section id="notas" class="scroll-mt-[130px] pb-[30px]">
        <div class="ebim-container" data-reveal>
            <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="text-[32px] font-bold leading-[1.3] text-ink max-md:text-[26px]">Mis notas</h2>

                    @if ($gestiones->isNotEmpty())
                        <form method="GET" action="{{ route('portal.index') }}" class="flex items-center gap-3">
                            <label for="gestion" class="text-[16px] font-semibold text-ink">Gestión:</label>
                            <select id="gestion" name="gestion" onchange="this.form.submit()"
                                class="rounded-[15px] border-2 border-ink/20 bg-white px-4 py-2 text-[17px] text-ink outline-none focus:border-ink">
                                @foreach ($gestiones as $gestion)
                                    <option value="{{ $gestion }}" @selected($gestion == $gestionSeleccionada)>{{ $gestion }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

                @if ($filasNotas->isEmpty())
                    <p class="mt-6 text-[18px] text-ink">Todavía no se registraron notas para este estudiante.</p>
                @else
                    <div id="tabla-notas" class="mt-6 overflow-x-auto scroll-mt-[130px]">
                        <table class="w-full min-w-[820px] border-collapse">
                            <thead>
                                <tr class="bg-ink text-left text-white">
                                    <th class="px-4 py-3 text-[16px] font-bold">Asignatura</th>
                                    @foreach ($parciales as $etiqueta)
                                        <th class="px-4 py-3 text-[16px] font-bold">{{ $etiqueta }}</th>
                                    @endforeach
                                    <th class="px-4 py-3 text-[16px] font-bold">Nota final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filasNotas as $fila)
                                    <tr class="border-b border-mist">
                                        <td class="px-4 py-3 text-[17px] text-ink">{{ $fila['asignatura'] }}</td>

                                        @foreach ($fila['notas'] as $nota)
                                            @if ($nota)
                                                <td class="px-4 py-3 text-[17px] font-bold {{ $nota->nota >= 51 ? 'text-[#1f7a44]' : 'text-brand' }}">
                                                    {{ number_format((float) $nota->nota, 2) }}
                                                </td>
                                            @else
                                                <td class="px-4 py-3 text-[17px] text-slate">—</td>
                                            @endif
                                        @endforeach

                                        <td class="px-4 py-3 text-[17px] font-bold {{ $fila['final'] >= 51 ? 'text-[#1f7a44]' : 'text-brand' }}">
                                            {{ number_format($fila['final'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($promedio !== null)
                        <p class="mt-5 text-[20px] font-bold text-ink">Promedio de la gestión: <span class="text-brand">{{ number_format($promedio, 2) }}</span></p>
                    @endif

                    {{-- Dos documentos imprimibles, centrados y simétricos. --}}
                    <div class="mt-8 border-t border-mist pt-6">
                        <div class="mx-auto grid max-w-[560px] grid-cols-2 gap-8 max-md:grid-cols-1 max-md:gap-5">
                            <div class="flex flex-col items-center gap-2 text-center">
                                <a href="{{ route('portal.calificaciones', $gestionSeleccionada ? ['gestion' => $gestionSeleccionada] : []) }}"
                                    target="_blank" rel="noopener" class="btn-ss w-full max-w-[240px] whitespace-nowrap text-[14px]">
                                    Imprimir calificaciones
                                </a>
                                <p class="max-w-[235px] text-[13px] leading-[1.45] text-slate">
                                    Para tu conocimiento: consulta tus notas actuales.
                                </p>
                            </div>

                            <div class="flex flex-col items-center gap-2 text-center">
                                <a href="{{ route('portal.carta', $gestionSeleccionada ? ['gestion' => $gestionSeleccionada] : []) }}"
                                    target="_blank" rel="noopener" class="btn-ss w-full max-w-[240px] whitespace-nowrap text-[14px]">
                                    Imprimir carta
                                </a>
                                <p class="max-w-[235px] text-[13px] leading-[1.45] text-slate">
                                    Para trámites: solicita la emisión de tus notas.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Horario --}}
    <section id="horario" class="scroll-mt-[130px] pb-[40px] max-md:pb-[30px]">
        <div class="ebim-container" data-reveal>
            <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
                <h2 class="text-[32px] font-bold leading-[1.3] text-ink max-md:text-[26px]">Mi horario</h2>

                @if (! $curso)
                    <p class="mt-6 text-[18px] text-ink">Todavía no se asignó un curso a tu cuenta. Consulta en secretaría de la escuela.</p>
                @else
                    <p class="mt-2 text-[17px] text-ink">Horario del curso <strong class="text-brand">{{ $curso->nombre }}</strong></p>

                    @if (! $horarioAprobado)
                        <p class="mt-6 text-[18px] text-ink">
                            El horario de tu curso todavía no fue publicado. La dirección académica lo está elaborando o está pendiente de la aprobación del rector.
                        </p>
                    @elseif ($rejilla['franjas'] === [])
                        <p class="mt-6 text-[18px] text-ink">Tu curso todavía no tiene un horario registrado.</p>
                    @else
                        <div class="mt-6 overflow-x-auto">
                            <table class="w-full min-w-[1000px] border-collapse">
                                <thead>
                                    <tr class="bg-ink text-left text-white">
                                        <th class="w-[120px] px-4 py-3 text-[16px] font-bold">Hora</th>
                                        @foreach ($rejilla['dias'] as $dia)
                                            <th class="px-4 py-3 text-[16px] font-bold">{{ $dia }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rejilla['franjas'] as $franja)
                                        <tr class="border-b border-mist align-top">
                                            <th class="px-4 py-3 text-left align-top">
                                                <span class="block text-[16px] font-bold text-ink">{{ $franja['hora_inicio'] }}</span>
                                                <span class="block text-[14px] font-semibold text-slate">a {{ $franja['hora_fin'] }}</span>
                                            </th>

                                            @foreach ($rejilla['dias'] as $dia)
                                                @php($celda = $franja['celdas'][$dia] ?? null)

                                                <td class="px-2 py-2 align-top">
                                                    @if ($celda)
                                                        <div class="h-full rounded-[15px] bg-mist p-3">
                                                            <p class="text-[16px] font-bold leading-[1.3] text-ink">{{ $celda->asignatura }}</p>
                                                            <p class="mt-1 text-[14px] leading-[1.3] text-slate">{{ $celda->docente }}</p>

                                                            @if ($celda->ambiente)
                                                                <p class="mt-1 text-[13px] font-bold uppercase tracking-[1px] text-brand">{{ $celda->ambiente }}</p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

@endsection

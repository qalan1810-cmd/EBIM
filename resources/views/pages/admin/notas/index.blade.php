@extends('layouts.app')

@section('title', 'Notas — Administración | EBIM Oruro')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Notas</h1>
        <p class="mt-3 text-[19px] text-white">Promedio de los estudiantes por curso.</p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <form method="GET" action="{{ route('admin.notas.index') }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="curso" class="mb-1.5 block text-[12px] font-bold uppercase tracking-[1.2px] text-slate">Curso</label>
                        <select id="curso" name="curso" onchange="this.form.submit()"
                            class="min-w-[170px] rounded-[12px] border-2 border-ink/15 bg-white px-4 py-2.5 text-[16px] font-semibold text-ink outline-none transition-colors focus:border-brand">
                            <option value="">Todos</option>
                            @foreach ($cursos as $curso)
                                <option value="{{ $curso->id }}" @selected((string) $cursoId === (string) $curso->id)>{{ $curso->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="gestion" class="mb-1.5 block text-[12px] font-bold uppercase tracking-[1.2px] text-slate">Gestión</label>
                        <select id="gestion" name="gestion" onchange="this.form.submit()"
                            class="min-w-[110px] rounded-[12px] border-2 border-ink/15 bg-white px-4 py-2.5 text-[16px] font-semibold text-ink outline-none transition-colors focus:border-brand">
                            @foreach ($gestiones as $gestionItem)
                                <option value="{{ $gestionItem }}" @selected((string) $gestion === (string) $gestionItem)>{{ $gestionItem }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <span class="inline-flex h-12 items-center gap-2 rounded-full bg-mist px-4 text-[14px] font-bold text-ink">
                    <svg class="h-4 w-4 text-slate" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    {{ $estudiantes->count() }} {{ $estudiantes->count() === 1 ? 'estudiante' : 'estudiantes' }}
                </span>
            </div>

            <div class="mt-6 overflow-hidden rounded-[16px] border border-mist">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[780px] border-collapse">
                        <thead>
                            <tr class="bg-ink text-left text-white">
                                <th class="px-5 py-3.5 text-[13px] font-bold uppercase tracking-[0.8px]">Estudiante</th>
                                <th class="px-5 py-3.5 text-[13px] font-bold uppercase tracking-[0.8px]">Especialidad</th>
                                <th class="px-5 py-3.5 text-center text-[13px] font-bold uppercase tracking-[0.8px]">Nota promedio</th>
                                <th class="px-5 py-3.5 text-[13px] font-bold uppercase tracking-[0.8px]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($estudiantes as $estudiante)
                                @php($promedio = $promedios->get($estudiante->id))

                                <tr class="border-b border-mist transition-colors last:border-0 hover:bg-[#fcf8f8]">
                                    <td class="px-5 py-4">
                                        <p class="text-[16px] font-semibold leading-[1.25] text-ink">{{ $estudiante->name }}</p>
                                        <p class="mt-0.5 text-[13px] text-slate">{{ $estudiante->curso?->nombre ?? 'Sin curso' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($estudiante->especialidad)
                                            <span class="inline-flex rounded-full border border-mist bg-white px-3 py-1 text-[13px] font-semibold text-ink">
                                                {{ $estudiante->especialidad }}
                                            </span>
                                        @else
                                            <span class="text-[15px] text-slate">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if ($promedio !== null)
                                            <span class="inline-flex min-w-[76px] items-center justify-center rounded-[10px] bg-[#fdecee] px-3 py-1.5 text-[17px] font-black text-brand">
                                                {{ number_format((float) $promedio, 2) }}
                                            </span>
                                        @else
                                            <span class="text-[15px] text-slate">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('admin.notas.estudiante', ['estudiante' => $estudiante->id, 'gestion' => $gestion] + ($cursoId ? ['curso' => $cursoId] : [])) }}"
                                                class="inline-flex items-center gap-1.5 rounded-[10px] border-2 border-ink bg-ink px-3 py-2 text-[13px] font-bold text-white transition-colors hover:border-brand hover:bg-brand">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                Ver notas
                                            </a>
                                            <a href="{{ route('admin.notas.create', ['estudiante' => $estudiante->id]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-[10px] border-2 border-ink/15 px-3 py-2 text-[13px] font-bold text-ink transition-colors hover:border-ink hover:bg-ink hover:text-white">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 5v14M5 12h14" />
                                                </svg>
                                                Agregar nota
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-[16px] text-slate">
                                        No hay estudiantes registrados para este filtro.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection

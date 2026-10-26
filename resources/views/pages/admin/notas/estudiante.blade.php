@extends('layouts.app')

@section('title', 'Notas de ' . $estudiante->name . ' — Administración | EBIM Oruro')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Notas</h1>
        <p class="mt-3 text-[19px] text-white">Registra y actualiza las notas del estudiante.</p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
            <a href="{{ route('admin.notas.index', $cursoId ? ['curso' => $cursoId] : []) }}"
                class="inline-flex items-center gap-1.5 text-[14px] font-bold text-slate transition-colors hover:text-brand">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Volver a notas
            </a>

            <div class="mt-5 flex flex-wrap items-center justify-between gap-4 border-b border-mist pb-6">
                <div>
                    <h2 class="text-[26px] font-bold leading-[1.2] text-ink max-md:text-[22px]">{{ $estudiante->name }}</h2>
                    <div class="mt-1.5 flex flex-wrap items-center gap-2">
                        <span class="text-[14px] text-slate">{{ $estudiante->curso?->nombre ?? 'Sin curso' }}</span>
                        @if ($estudiante->especialidad)
                            <span class="inline-flex rounded-full border border-mist px-2.5 py-0.5 text-[12px] font-semibold text-ink">
                                {{ $estudiante->especialidad }}
                            </span>
                        @endif
                        @if ($estudiante->codigo)
                            <span class="text-[13px] text-slate">Código {{ $estudiante->codigo }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap items-end gap-3">
                    <form method="GET" action="{{ route('admin.notas.estudiante', $estudiante) }}" class="flex items-end gap-2">
                        @if ($cursoId)
                            <input type="hidden" name="curso" value="{{ $cursoId }}">
                        @endif

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

                    <a href="{{ route('admin.notas.create', ['estudiante' => $estudiante->id]) }}" class="btn-ss">Nueva nota</a>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-[17px] font-bold text-ink">Notas registradas</h3>

                @if ($promedio !== null)
                    <div class="flex items-center gap-3 rounded-[12px] bg-[#fcf8f8] px-4 py-2">
                        <span class="text-[11px] font-bold uppercase tracking-[1px] text-slate">Promedio de la gestión</span>
                        <span class="text-[22px] font-black leading-none text-brand">{{ number_format($promedio, 2) }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-3 overflow-hidden rounded-[16px] border border-mist">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] border-collapse">
                        <thead>
                            <tr class="bg-ink text-left text-white">
                                <th class="px-5 py-3.5 text-[13px] font-bold uppercase tracking-[0.8px]">Asignatura</th>
                                <th class="px-5 py-3.5 text-[13px] font-bold uppercase tracking-[0.8px]">Parcial</th>
                                <th class="px-5 py-3.5 text-[13px] font-bold uppercase tracking-[0.8px]">Nota</th>
                                <th class="px-5 py-3.5 text-[13px] font-bold uppercase tracking-[0.8px]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notas as $nota)
                                <tr class="border-b border-mist transition-colors last:border-0 hover:bg-[#fcf8f8]">
                                    <td class="px-5 py-4 text-[16px] font-semibold text-ink">{{ $nota->asignatura }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-mist px-2 text-[13px] font-bold text-ink">
                                            {{ $nota->parcial }}.º
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-[17px] font-bold text-ink">{{ number_format((float) $nota->nota, 2) }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('admin.notas.edit', $nota) }}"
                                                class="inline-flex items-center gap-1.5 rounded-[10px] border-2 border-ink bg-ink px-3 py-2 text-[13px] font-bold text-white transition-colors hover:border-brand hover:bg-brand">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                                </svg>
                                                Editar
                                            </a>
                                            <form method="POST" action="{{ route('admin.notas.destroy', $nota) }}"
                                                onsubmit="return confirm('¿Eliminar esta nota?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-[10px] border-2 border-brand/20 px-3 py-2 text-[13px] font-bold text-brand transition-colors hover:border-brand hover:bg-brand hover:text-white">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-[16px] text-slate">
                                        No hay notas registradas en esta gestión.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $notas->links() }}
            </div>
        </div>
    </section>

@endsection

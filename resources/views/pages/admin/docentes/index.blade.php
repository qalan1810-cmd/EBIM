@extends('layouts.app')

@section('title', 'Docentes — Administración | EBIM Oruro')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Docentes</h1>
        <p class="mt-3 text-[19px] text-white">Registra, actualiza o elimina docentes del plantel.</p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-[28px] font-bold text-ink">Listado de docentes</h2>
                <a href="{{ route('admin.docentes.create') }}" class="btn-ss">Nuevo docente</a>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-[920px] border-collapse">
                    <thead>
                        <tr class="bg-ink text-left text-white">
                            <th class="px-4 py-3 text-[16px] font-bold">Foto</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Orden</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Nombre</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Cargo</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Especialidad</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Estado</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($docentes as $docente)
                            <tr class="border-b border-mist align-top">
                                <td class="px-4 py-3">
                                    @if ($docente->foto)
                                        <div class="h-[70px] w-[110px] rounded-[10px] bg-cover bg-mist"
                                            style="background-image: url('{{ asset($docente->foto) }}'); background-position: {{ $docente->posicionFoto() }}"
                                            role="img" aria-label="{{ $docente->nombre }}"></div>
                                    @else
                                        <span class="text-[15px] text-slate">Sin foto</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-[16px] text-ink">{{ $docente->orden }}</td>
                                <td class="px-4 py-3 text-[16px] font-semibold text-ink">{{ $docente->nombre }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">{{ $docente->cargo }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">{{ $docente->especialidad }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">
                                    <span class="rounded-full px-3 py-1 text-[14px] font-bold {{ $docente->activo ? 'bg-[#e7f6ec] text-[#1f7a44]' : 'bg-mist text-slate' }}">
                                        {{ $docente->activo ? 'Visible' : 'Oculto' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.docentes.edit', $docente) }}"
                                            class="rounded-[10px] bg-ink px-3 py-2 text-[14px] font-bold text-white hover:bg-brand">Editar</a>
                                        <form method="POST" action="{{ route('admin.docentes.destroy', $docente) }}"
                                            onsubmit="return confirm('¿Eliminar este docente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-[10px] bg-brand px-3 py-2 text-[14px] font-bold text-white hover:bg-ink">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-[17px] text-ink">No hay docentes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $docentes->links() }}
            </div>
        </div>
    </section>

@endsection

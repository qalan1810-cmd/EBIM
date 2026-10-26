@extends('layouts.app')

@php
    $editando = $nota->exists;
    $estudianteSeleccionado = old('user_id', $nota->user_id);

    $parametrosVolver = [];

    if ($estudianteSeleccionado) {
        $parametrosVolver['estudiante'] = $estudianteSeleccionado;

        if (old('gestion', $nota->gestion)) {
            $parametrosVolver['gestion'] = old('gestion', $nota->gestion);
        }
    }

    $volver = $estudianteSeleccionado
        ? route('admin.notas.estudiante', $parametrosVolver)
        : route('admin.notas.index');
@endphp

@section('title', ($editando ? 'Editar nota' : 'Nueva nota') . ' | Administración EBIM')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">
            {{ $editando ? 'Editar nota' : 'Nueva nota' }}
        </h1>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="max-w-[820px] rounded-[25px] bg-white p-[35px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
            <form method="POST" action="{{ $editando ? route('admin.notas.update', $nota) : route('admin.notas.store') }}">
                @csrf
                @if ($editando)
                    @method('PUT')
                @endif

                <div>
                    <label for="user_id" class="mb-2 block text-[15px] font-bold text-ink">Estudiante</label>
                    <select id="user_id" name="user_id" required
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        <option value="">Seleccione un estudiante</option>
                        @foreach ($estudiantes as $estudiante)
                            <option value="{{ $estudiante->id }}" @selected(old('user_id', $nota->user_id) == $estudiante->id)>
                                {{ $estudiante->name }}{{ $estudiante->codigo ? ' (' . $estudiante->codigo . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5 grid grid-cols-2 gap-[20px] max-md:grid-cols-1">
                    <div>
                        <label for="asignatura" class="mb-2 block text-[15px] font-bold text-ink">Asignatura</label>
                        <input id="asignatura" name="asignatura" type="text" value="{{ old('asignatura', $nota->asignatura) }}" required
                            placeholder="Ej. Instrumento principal, Solfeo"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('asignatura') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="gestion" class="mb-2 block text-[15px] font-bold text-ink">Gestión</label>
                        <input id="gestion" name="gestion" type="text" value="{{ old('gestion', $nota->gestion ?? date('Y')) }}" required
                            placeholder="Ej. 2026"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('gestion') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="parcial" class="mb-2 block text-[15px] font-bold text-ink">Parcial</label>
                        <select id="parcial" name="parcial" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                            @foreach (range(1, 4) as $parcial)
                                <option value="{{ $parcial }}" @selected(old('parcial', $nota->parcial) == $parcial)>{{ $parcial }}.º parcial</option>
                            @endforeach
                        </select>
                        @error('parcial') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nota" class="mb-2 block text-[15px] font-bold text-ink">Nota (0 a 100)</label>
                        <input id="nota" name="nota" type="number" step="0.01" min="0" max="100" value="{{ old('nota', $nota->nota) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('nota') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-7 flex flex-wrap gap-4">
                    <button type="submit" class="btn-ss">{{ $editando ? 'Guardar cambios' : 'Registrar nota' }}</button>
                    <a href="{{ $volver }}"
                        class="inline-block rounded-[15px] border-2 border-ink px-6 py-[10px] text-[18px] font-bold text-ink hover:bg-ink hover:text-white">Cancelar</a>
                </div>
            </form>
        </div>
    </section>

@endsection

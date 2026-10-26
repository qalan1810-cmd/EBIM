@extends('layouts.app')

@php($editando = $horario->exists)

@section('title', ($editando ? 'Editar horario' : 'Nuevo horario') . ' | Administración EBIM')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">
            {{ $editando ? 'Editar horario' : 'Nuevo horario' }}
        </h1>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="max-w-[900px] rounded-[25px] bg-white p-[35px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
            <p class="mb-6 text-[16px] text-slate">La asignatura se agrega al horario del curso elegido, en el día y la franja horaria indicados.</p>

            <form method="POST" action="{{ $editando ? route('admin.horarios.update', $horario) : route('admin.horarios.store') }}">
                @csrf
                @if ($editando)
                    @method('PUT')
                @endif

                <div>
                    <label for="curso_id" class="mb-2 block text-[15px] font-bold text-ink">Curso</label>
                    <select id="curso_id" name="curso_id" required
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        <option value="">Seleccione un curso</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso->id }}" @selected(old('curso_id', $horario->curso_id) == $curso->id)>
                                {{ $curso->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('curso_id') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    @if ($cursos->isEmpty())
                        <p class="mt-2 text-[15px] text-slate">
                            Todavía no hay cursos. Créalos en la sección de <a href="{{ route('admin.horarios.index') }}" class="font-semibold text-brand underline">Horarios</a>.
                        </p>
                    @endif
                </div>

                <div class="mt-5 grid grid-cols-3 gap-[20px] max-md:grid-cols-1">
                    <div>
                        <label for="dia" class="mb-2 block text-[15px] font-bold text-ink">Día</label>
                        <select id="dia" name="dia" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                            @foreach (\App\Models\Curso::DIAS as $dia)
                                <option value="{{ $dia }}" @selected(old('dia', $horario->dia) == $dia)>{{ $dia }}</option>
                            @endforeach
                        </select>
                        @error('dia') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hora_inicio" class="mb-2 block text-[15px] font-bold text-ink">Hora de inicio</label>
                        <input id="hora_inicio" name="hora_inicio" type="time" value="{{ old('hora_inicio', $horario->hora_inicio) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('hora_inicio') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hora_fin" class="mb-2 block text-[15px] font-bold text-ink">Hora de fin</label>
                        <input id="hora_fin" name="hora_fin" type="time" value="{{ old('hora_fin', $horario->hora_fin) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('hora_fin') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-[20px] max-md:grid-cols-1">
                    <div>
                        <label for="asignatura" class="mb-2 block text-[15px] font-bold text-ink">Asignatura</label>
                        <input id="asignatura" name="asignatura" type="text" value="{{ old('asignatura', $horario->asignatura) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('asignatura') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="docente" class="mb-2 block text-[15px] font-bold text-ink">Docente</label>
                        <input id="docente" name="docente" type="text" value="{{ old('docente', $horario->docente) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('docente') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="ambiente" class="mb-2 block text-[15px] font-bold text-ink">Ambiente (opcional)</label>
                    <input id="ambiente" name="ambiente" type="text" value="{{ old('ambiente', $horario->ambiente) }}"
                        placeholder="Ej. Aula 3, Sala de ensayo"
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    @error('ambiente') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-7 flex flex-wrap gap-4">
                    <button type="submit" class="btn-ss">{{ $editando ? 'Guardar cambios' : 'Registrar horario' }}</button>
                    <a href="{{ route('admin.horarios.index') }}"
                        class="inline-block rounded-[15px] border-2 border-ink px-6 py-[10px] text-[18px] font-bold text-ink hover:bg-ink hover:text-white">Cancelar</a>
                </div>
            </form>
        </div>
    </section>

@endsection

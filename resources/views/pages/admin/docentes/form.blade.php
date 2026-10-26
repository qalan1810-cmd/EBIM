@extends('layouts.app')

@php($editando = $docente->exists)

@section('title', ($editando ? 'Editar docente' : 'Nuevo docente') . ' | Administración EBIM')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">
            {{ $editando ? 'Editar docente' : 'Nuevo docente' }}
        </h1>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="max-w-[900px] rounded-[25px] bg-white p-[35px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
            <form method="POST"
                action="{{ $editando ? route('admin.docentes.update', $docente) : route('admin.docentes.store') }}">
                @csrf
                @if ($editando)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-2 gap-[20px] max-md:grid-cols-1">
                    <div>
                        <label for="nombre" class="mb-2 block text-[15px] font-bold text-ink">Nombre completo</label>
                        <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $docente->nombre) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('nombre') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cargo" class="mb-2 block text-[15px] font-bold text-ink">Cargo</label>
                        <input id="cargo" name="cargo" type="text" value="{{ old('cargo', $docente->cargo) }}" required
                            placeholder="Ej. Director Académico, Docente de Metales"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('cargo') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="especialidad" class="mb-2 block text-[15px] font-bold text-ink">Especialidad</label>
                        <input id="especialidad" name="especialidad" type="text" value="{{ old('especialidad', $docente->especialidad) }}" required
                            placeholder="Ej. Aerófonos de metal"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('especialidad') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="orden" class="mb-2 block text-[15px] font-bold text-ink">Orden de aparición</label>
                        <input id="orden" name="orden" type="number" min="0" value="{{ old('orden', $docente->orden ?? 0) }}"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('orden') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="foto" class="mb-2 block text-[15px] font-bold text-ink">Fotografía</label>
                    <input id="foto" name="foto" type="text" value="{{ old('foto', $docente->foto) }}"
                        placeholder="Ej. /assets/ebim/docentes/nombre.jpg"
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    <p class="mt-1 text-[14px] text-ink-soft">Ruta de la imagen dentro de <code>public/</code>.</p>
                    @error('foto') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="encuadre" class="mb-2 block text-[15px] font-bold text-ink">
                        Encuadre de la foto <span class="font-normal text-ink-soft">(0 = arriba, 100 = abajo)</span>
                    </label>
                    <input id="encuadre" name="encuadre" type="number" min="0" max="100" step="5"
                        value="{{ old('encuadre', $docente->encuadre ?? 50) }}"
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    <p class="mt-1 text-[14px] text-ink-soft">Baja el número para mostrar más de la parte superior de la fotografía o súbelo para mostrar más de la parte inferior.</p>
                    @error('encuadre') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="formacion" class="mb-2 block text-[15px] font-bold text-ink">Formación profesional</label>
                    <textarea id="formacion" name="formacion" rows="3" required
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">{{ old('formacion', $docente->formacion) }}</textarea>
                    @error('formacion') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="experiencia" class="mb-2 block text-[15px] font-bold text-ink">Experiencia y trayectoria</label>
                    <textarea id="experiencia" name="experiencia" rows="4" required
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">{{ old('experiencia', $docente->experiencia) }}</textarea>
                    @error('experiencia') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <label class="mt-5 flex items-center gap-2 text-[17px] text-ink">
                    <input type="checkbox" name="activo" value="1" @checked(old('activo', $docente->activo ?? true)) class="h-4 w-4">
                    <span>Mostrar este docente en el sitio público</span>
                </label>

                <div class="mt-7 flex flex-wrap gap-4">
                    <button type="submit" class="btn-ss">{{ $editando ? 'Guardar cambios' : 'Registrar docente' }}</button>
                    <a href="{{ route('admin.docentes.index') }}"
                        class="inline-block rounded-[15px] border-2 border-ink px-6 py-[10px] text-[18px] font-bold text-ink hover:bg-ink hover:text-white">Cancelar</a>
                </div>
            </form>
        </div>
    </section>

@endsection

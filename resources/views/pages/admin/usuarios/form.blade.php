@extends('layouts.app')

@php
    $editando = $usuario->exists;
    $rolActual = old('role', $usuario->role ?? 'estudiante');
    $esEstudiante = $rolActual === 'estudiante';
@endphp

@section('title', ($editando ? 'Editar usuario' : 'Nuevo usuario') . ' | Administración EBIM')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">
            {{ $editando ? 'Editar usuario' : 'Nuevo usuario' }}
        </h1>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="max-w-[860px] rounded-[25px] bg-white p-[35px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
            <form method="POST" action="{{ $editando ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}">
                @csrf
                @if ($editando)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-2 gap-[20px] max-md:grid-cols-1">
                    <div>
                        <label for="role" class="mb-2 block text-[15px] font-bold text-ink">Rol</label>
                        <select id="role" name="role" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                            @foreach (\App\Models\User::roles() as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected($rolActual === $valor)>{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="mb-2 block text-[15px] font-bold text-ink">Nombre completo</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $usuario->name) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('name') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div data-solo-estudiante @if (! $esEstudiante) hidden @endif>
                        <label for="codigo" class="mb-2 block text-[15px] font-bold text-ink">Código de estudiante</label>
                        <input id="codigo" name="codigo" type="text" value="{{ old('codigo', $usuario->codigo) }}"
                            inputmode="numeric" pattern="[0-9]+" data-requerido="1" @required($esEstudiante)
                            placeholder="Ej. 6509" autocomplete="off"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        <p class="mt-2 text-[15px] text-slate">Solo números, sin puntos ni guiones. Es el usuario para ingresar.</p>
                        @error('codigo') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div data-solo-estudiante @if (! $esEstudiante) hidden @endif>
                        <label for="carnet" class="mb-2 block text-[15px] font-bold text-ink">Carnet de identidad</label>
                        <input id="carnet" name="carnet" type="text" value="{{ old('carnet', $usuario->carnet) }}"
                            inputmode="numeric" pattern="[0-9]+" data-requerido="1" @required($esEstudiante)
                            placeholder="Ej. 7084785" autocomplete="off"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        <p class="mt-2 text-[15px] text-slate">Solo números. La contraseña inicial se genera con el carnet, el primer nombre y el primer apellido.</p>
                        @error('carnet') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div data-solo-personal @if ($esEstudiante) hidden @endif>
                        <label for="email" class="mb-2 block text-[15px] font-bold text-ink">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}"
                            data-requerido="1" @required(! $esEstudiante)
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('email') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div data-solo-personal @if ($esEstudiante) hidden @endif>
                        <label for="password" class="mb-2 block text-[15px] font-bold text-ink">
                            Contraseña {{ $editando ? '(dejar vacío para no cambiar)' : '' }}
                        </label>
                        <input id="password" name="password" type="password"
                            @if (! $editando) data-requerido="1" @endif
                            @required(! $esEstudiante && ! $editando) autocomplete="new-password"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('password') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="curso_id" class="mb-2 block text-[15px] font-bold text-ink">Curso (opcional)</label>
                        <select id="curso_id" name="curso_id"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                            <option value="">Sin curso asignado</option>
                            @foreach ($cursos as $curso)
                                <option value="{{ $curso->id }}" @selected(old('curso_id', $usuario->curso_id) == $curso->id)>
                                    {{ $curso->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-[15px] text-slate">El estudiante verá el horario del curso asignado. Los cursos se crean en la sección de Horarios.</p>
                        @error('curso_id') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="especialidad" class="mb-2 block text-[15px] font-bold text-ink">Especialidad (opcional)</label>
                        <select id="especialidad" name="especialidad"
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                            <option value="">Sin especialidad</option>
                            @foreach ($especialidades as $especialidad)
                                <option value="{{ $especialidad->nombre }}" @selected(old('especialidad', $usuario->especialidad) === $especialidad->nombre)>
                                    {{ $especialidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-[15px] text-slate">Se muestra en el perfil del estudiante, en la lista de notas y en sus documentos.</p>
                        @error('especialidad') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if ($editando && $usuario->esEstudiante())
                    <div class="mt-5 rounded-[15px] border-l-[6px] border-brand bg-[#fdecee] px-4 py-3">
                        <p class="text-[16px] text-ink">
                            La contraseña se genera automáticamente con el carnet, el primer nombre y el primer apellido. Si cambias el nombre o el carnet, la contraseña se actualiza y se muestra una sola vez al guardar.
                        </p>
                    </div>
                @endif

                <div class="mt-7 flex flex-wrap gap-4">
                    <button type="submit" class="btn-ss">{{ $editando ? 'Guardar cambios' : 'Registrar usuario' }}</button>
                    <a href="{{ route('admin.usuarios.index') }}"
                        class="inline-block rounded-[15px] border-2 border-ink px-6 py-[10px] text-[18px] font-bold text-ink hover:bg-ink hover:text-white">Cancelar</a>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const rol = document.getElementById('role');
            if (!rol) return;

            const actualizar = () => {
                const esEstudiante = rol.value === 'estudiante';

                document.querySelectorAll('[data-solo-estudiante]').forEach((bloque) => {
                    bloque.hidden = !esEstudiante;
                    bloque.querySelectorAll('input, select').forEach((campo) => {
                        campo.disabled = !esEstudiante;
                        if (campo.dataset.requerido === '1') campo.required = esEstudiante;
                    });
                });

                document.querySelectorAll('[data-solo-personal]').forEach((bloque) => {
                    bloque.hidden = esEstudiante;
                    bloque.querySelectorAll('input, select').forEach((campo) => {
                        campo.disabled = esEstudiante;
                        if (campo.dataset.requerido === '1') campo.required = !esEstudiante;
                    });
                });
            };

            rol.addEventListener('change', actualizar);
            actualizar();
        })();
    </script>

@endsection

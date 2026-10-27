@extends('layouts.app')

@section('title', 'Configuración — EBIM Oruro')
@section('description', 'Actualiza tu foto de perfil, tus datos personales y tu contraseña en el portal de la EBIM.')

@section('content')
    <section data-reveal class="pb-[40px] pt-[70px] max-md:pt-[50px]">
        <div class="ebim-container">
            <div class="mb-[30px]">
                <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Configuración</h1>
                <p class="mt-3 text-[19px] text-white">Edita tu información, tu foto de perfil y tu contraseña.</p>
            </div>

            <div class="grid gap-[24px] lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
                <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
                    <h2 class="text-[28px] font-bold text-ink max-md:text-[24px]">Datos personales</h2>
                    <p class="mt-2 text-[16px] text-slate">Actualiza tu foto, tu nombre y tu correo electrónico.</p>

                    <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data"
                        class="mt-[24px] flex flex-col gap-[16px]">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col gap-4 rounded-[20px] border border-mist bg-[#faf8f5] p-4 sm:flex-row sm:items-center">
                            <img src="{{ $usuario->foto_url }}" alt="Foto de perfil"
                                class="h-[88px] w-[88px] shrink-0 rounded-full border-4 border-white object-cover shadow-[0_0_10px_rgba(0,0,0,0.12)]">

                            <div class="min-w-0 flex-1">
                                <label for="foto" class="mb-1.5 block text-[14px] font-semibold text-ink">Foto de perfil</label>
                                <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"
                                    class="block w-full text-[14px] text-ink file:mr-3 file:cursor-pointer file:rounded-full file:border-0 file:bg-brand file:px-4 file:py-2 file:text-[14px] file:font-semibold file:text-white hover:file:bg-ink">
                                <p class="mt-2 text-[13px] text-slate">JPG, PNG o WebP. Máximo 2 MB.</p>

                                @if ($usuario->foto && ! str_contains($usuario->foto, 'default-'))
                                    <label class="mt-3 flex items-center gap-2 text-[14px] text-ink">
                                        <input type="checkbox" name="eliminar_foto" value="1" @checked(old('eliminar_foto'))
                                            class="h-4 w-4 accent-[#830101]">
                                        Quitar foto personalizada
                                    </label>
                                @endif

                                @error('foto')
                                    <p class="mt-2 text-[14px] font-semibold text-brand">{{ $message }}</p>
                                @enderror
                                @error('eliminar_foto')
                                    <p class="mt-2 text-[14px] font-semibold text-brand">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="name" class="mb-1.5 block text-[14px] font-semibold text-ink">Nombre completo</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $usuario->name) }}" required
                                class="w-full rounded-[15px] border-2 border-ink/10 px-4 py-3 text-[17px] text-ink outline-none transition-colors focus:border-brand">
                            @error('name')
                                <p class="mt-2 text-[14px] font-semibold text-brand">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-1.5 block text-[14px] font-semibold text-ink">Correo electrónico</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}"
                                @if ($usuario->esPersonal()) required @endif
                                class="w-full rounded-[15px] border-2 border-ink/10 px-4 py-3 text-[17px] text-ink outline-none transition-colors focus:border-brand">
                            <p class="mt-2 text-[14px] text-slate">
                                {{ $usuario->esPersonal() ? 'Obligatorio para iniciar sesión.' : 'Opcional. Puedes agregarlo para recibir avisos.' }}
                            </p>
                            @error('email')
                                <p class="mt-2 text-[14px] font-semibold text-brand">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-[8px]">
                            <button type="submit" class="btn-ss">Guardar cambios</button>
                        </div>
                    </form>

                    <div class="mt-8 border-t border-mist pt-6">
                        <h3 class="text-[20px] font-bold text-ink">Datos de la cuenta</h3>

                        <dl class="mt-4 grid grid-cols-2 gap-[16px] max-md:grid-cols-1">
                            <div class="min-w-0">
                                <dt class="text-[13px] font-bold uppercase tracking-[1.2px] text-brand">Rol</dt>
                                <dd class="mt-1 break-words text-[17px] text-ink">{{ $usuario->etiquetaRol() }}</dd>
                            </div>

                            @if ($usuario->esEstudiante())
                                <div class="min-w-0">
                                    <dt class="text-[13px] font-bold uppercase tracking-[1.2px] text-brand">Código de estudiante</dt>
                                    <dd class="mt-1 break-words text-[17px] text-ink">{{ $usuario->codigo ?: 'No asignado' }}</dd>
                                </div>
                                <div class="min-w-0">
                                    <dt class="text-[13px] font-bold uppercase tracking-[1.2px] text-brand">Curso</dt>
                                    <dd class="mt-1 break-words text-[17px] text-ink">{{ $usuario->curso?->nombre ?: 'No asignado' }}</dd>
                                </div>
                                <div class="min-w-0">
                                    <dt class="text-[13px] font-bold uppercase tracking-[1.2px] text-brand">Instrumento de especialidad</dt>
                                    <dd class="mt-1 break-words text-[17px] text-ink">{{ $usuario->especialidad ?: 'No asignado' }}</dd>
                                </div>
                                <div class="min-w-0">
                                    <dt class="text-[13px] font-bold uppercase tracking-[1.2px] text-brand">Carnet de identidad</dt>
                                    <dd class="mt-1 break-words text-[17px] text-ink">{{ $usuario->carnet ?: 'No asignado' }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)] max-md:p-[20px]">
                    <h2 class="text-[28px] font-bold text-ink max-md:text-[24px]">Cambiar contraseña</h2>
                    <p class="mt-2 text-[16px] text-slate">Deja los campos vacíos si no deseas cambiarla.</p>

                    <form method="POST" action="{{ route('perfil.update') }}" class="mt-[24px] flex flex-col gap-[16px]">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="name" value="{{ old('name', $usuario->name) }}">
                        <input type="hidden" name="email" value="{{ old('email', $usuario->email) }}">

                        <div>
                            <label for="password_actual" class="mb-1.5 block text-[14px] font-semibold text-ink">Contraseña actual</label>
                            <input id="password_actual" name="password_actual" type="password" autocomplete="current-password"
                                class="w-full rounded-[15px] border-2 border-ink/10 px-4 py-3 text-[17px] text-ink outline-none transition-colors focus:border-brand">
                            @error('password_actual')
                                <p class="mt-2 text-[14px] font-semibold text-brand">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-[14px] font-semibold text-ink">Nueva contraseña</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" minlength="8"
                                class="w-full rounded-[15px] border-2 border-ink/10 px-4 py-3 text-[17px] text-ink outline-none transition-colors focus:border-brand">
                            <p class="mt-2 text-[14px] text-slate">Mínimo 8 caracteres.</p>
                            @error('password')
                                <p class="mt-2 text-[14px] font-semibold text-brand">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-[14px] font-semibold text-ink">Confirmar nueva contraseña</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                class="w-full rounded-[15px] border-2 border-ink/10 px-4 py-3 text-[17px] text-ink outline-none transition-colors focus:border-brand">
                        </div>

                        <div class="mt-[8px]">
                            <button type="submit" class="btn-ss">Cambiar contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

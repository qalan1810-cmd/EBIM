@extends('layouts.app')

@section('title', 'Usuarios — Administración | EBIM Oruro')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Usuarios</h1>
        <p class="mt-3 text-[19px] text-white">Administra estudiantes y personal autorizado de la escuela.</p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-[28px] font-bold text-ink">Listado de usuarios</h2>
                <a href="{{ route('admin.usuarios.create') }}" class="btn-ss">Nuevo usuario</a>
            </div>

            @if (session('credenciales'))
                @php($credenciales = session('credenciales'))
                <div class="mt-6 rounded-[15px] border-l-[6px] border-brand bg-[#fdecee] px-4 py-4">
                    <p class="text-[18px] font-bold text-ink">
                        {{ ($credenciales['actualizada'] ?? false) ? 'Contraseña actualizada' : 'Credenciales del estudiante' }}
                    </p>
                    <p class="mt-2 text-[16px] text-ink">Código: <strong>{{ $credenciales['codigo'] }}</strong></p>
                    <p class="text-[16px] text-ink">Contraseña: <strong>{{ $credenciales['password'] }}</strong></p>
                    <p class="mt-2 text-[15px] text-slate">Entregue estos datos al estudiante. La contraseña no se vuelve a mostrar.</p>
                </div>
            @endif

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-[1040px] border-collapse">
                    <thead>
                        <tr class="bg-ink text-left text-white">
                            <th class="px-4 py-3 text-[16px] font-bold">Nombre</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Código</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Carnet</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Correo</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Curso</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Rol</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr class="border-b border-mist">
                                <td class="px-4 py-3 text-[16px] font-semibold text-ink">{{ $usuario->name }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">{{ $usuario->codigo ?: '—' }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">{{ $usuario->carnet ?: '—' }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">{{ $usuario->email ?: '—' }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">{{ $usuario->curso->nombre ?? '—' }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">
                                    <span class="rounded-full px-3 py-1 text-[14px] font-bold {{ $usuario->esEstudiante() ? 'bg-mist text-ink' : 'bg-[#fdecee] text-brand' }}">
                                        {{ $usuario->etiquetaRol() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                                            class="rounded-[10px] bg-ink px-3 py-2 text-[14px] font-bold text-white hover:bg-brand">Editar</a>
                                        @if ($usuario->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}"
                                                onsubmit="return confirm('¿Eliminar este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-[10px] bg-brand px-3 py-2 text-[14px] font-bold text-white hover:bg-ink">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-[17px] text-ink">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $usuarios->links() }}
            </div>
        </div>
    </section>

@endsection

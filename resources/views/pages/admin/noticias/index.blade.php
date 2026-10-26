@extends('layouts.app')

@section('title', 'Noticias — Administración | EBIM Oruro')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Noticias y comunicados</h1>
        <p class="mt-3 text-[19px] text-white">Registra, actualiza o elimina las publicaciones que se muestran en la portada.</p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-[28px] font-bold text-ink">Listado de publicaciones</h2>
                <a href="{{ route('admin.noticias.create') }}" class="btn-ss">Nueva publicación</a>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-[980px] border-collapse">
                    <thead>
                        <tr class="bg-ink text-left text-white">
                            <th class="px-4 py-3 text-[16px] font-bold">Fecha</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Tipo</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Título</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Resumen</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Multimedia</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Estado</th>
                            <th class="px-4 py-3 text-[16px] font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($noticias as $noticia)
                            <tr class="border-b border-mist align-top">
                                <td class="whitespace-nowrap px-4 py-3 text-[16px] text-ink">
                                    {{ optional($noticia->fecha)->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-[16px] text-ink">
                                    <span class="rounded-full px-3 py-1 text-[14px] font-bold {{ $noticia->tipo === \App\Models\Noticia::TIPO_COMUNICADO ? 'bg-[#fdecee] text-brand' : 'bg-mist text-ink' }}">
                                        {{ $noticia->etiquetaTipo() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-[16px] font-semibold text-ink">{{ $noticia->titulo }}</td>
                                <td class="px-4 py-3 text-[16px] text-ink">
                                    <span class="line-clamp-2 max-w-[280px]">{{ $noticia->resumen ?? '—' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-[16px] text-ink">
                                    @if ($noticia->tieneVideo())
                                        <span class="rounded-full bg-[#e7f6ec] px-3 py-1 text-[14px] font-bold text-[#1f7a44]">Video incrustado</span>
                                    @elseif ($noticia->imagenUrl())
                                        <span class="rounded-full bg-mist px-3 py-1 text-[14px] font-bold text-ink">Fotografía</span>
                                    @else
                                        <span class="text-slate">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-[16px] text-ink">
                                    <span class="rounded-full px-3 py-1 text-[14px] font-bold {{ $noticia->activo ? 'bg-[#e7f6ec] text-[#1f7a44]' : 'bg-mist text-slate' }}">
                                        {{ $noticia->activo ? 'Visible' : 'Oculta' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.noticias.edit', $noticia) }}"
                                            class="rounded-[10px] bg-ink px-3 py-2 text-[14px] font-bold text-white hover:bg-brand">Editar</a>
                                        <form method="POST" action="{{ route('admin.noticias.destroy', $noticia) }}"
                                            onsubmit="return confirm('¿Eliminar esta publicación?');">
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
                                <td colspan="7" class="px-4 py-6 text-[17px] text-ink">No hay publicaciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $noticias->links() }}
            </div>
        </div>
    </section>

@endsection

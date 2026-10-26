@extends('layouts.app')

@section('title', 'Contenidos institucionales | Administración EBIM')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">Información institucional</h1>
        <p class="mt-3 text-[19px] text-white">Actualiza los textos que se muestran en las páginas públicas del sitio.</p>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <form method="POST" action="{{ route('admin.contenidos.update') }}" class="space-y-[25px]">
            @csrf
            @method('PUT')

            @foreach ($campos as $clave => $etiqueta)
                @php($contenido = $contenidos[$clave] ?? null)
                <div class="rounded-[25px] bg-white p-[30px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
                    <h2 class="text-[24px] font-bold text-ink">{{ $etiqueta }}</h2>

                    <div class="mt-4">
                        <label for="{{ $clave }}-titulo" class="mb-2 block text-[15px] font-bold text-ink">Título de la sección</label>
                        <input id="{{ $clave }}-titulo" name="{{ $clave }}[titulo]" type="text"
                            value="{{ old("$clave.titulo", $contenido->titulo ?? $etiqueta) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error("$clave.titulo") <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="{{ $clave }}-cuerpo" class="mb-2 block text-[15px] font-bold text-ink">Contenido</label>
                        <textarea id="{{ $clave }}-cuerpo" name="{{ $clave }}[cuerpo]" rows="{{ in_array($clave, ['requisitos', 'pasos_inscripcion']) ? 7 : 5 }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">{{ old("$clave.cuerpo", $contenido->cuerpo ?? '') }}</textarea>
                        @if (in_array($clave, ['requisitos', 'pasos_inscripcion']))
                            <p class="mt-2 text-[15px] text-slate">Escribe un ítem por línea. Cada línea se mostrará como un requisito o paso independiente.</p>
                        @endif
                        @if ($clave === 'pasos_inscripcion')
                            <p class="mt-2 text-[15px] text-slate">En cada paso puedes escribir <strong>Título | Detalle</strong>: el título se muestra destacado y el detalle debajo. Si escribes solo el texto, se muestra como un paso simple.</p>
                        @endif
                        @if ($clave === 'cronologia')
                            <p class="mt-2 text-[15px] text-slate">Escribe un hito por línea con el formato <strong>año | título | detalle</strong>. El detalle es opcional.</p>
                        @endif
                        @error("$clave.cuerpo") <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror

                        @if ($clave === 'rector')
                            <div class="mt-4">
                                <label for="{{ $clave }}-foto" class="mb-2 block text-[15px] font-bold text-ink">Fotografía</label>
                                <input id="{{ $clave }}-foto" name="{{ $clave }}[foto]" type="text"
                                    value="{{ old("$clave.foto", $contenido->foto ?? '') }}"
                                    placeholder="/assets/ebim/foto-del-rector.jpg"
                                    class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                                <p class="mt-2 text-[15px] text-slate">Ruta o dirección de la imagen. Déjalo vacío para mostrar el escudo de la escuela.</p>
                                @error("$clave.foto") <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="flex flex-wrap gap-4">
                <button type="submit" class="btn-ss">Guardar información</button>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-block rounded-[15px] border-2 border-ink px-6 py-[10px] text-[18px] font-bold text-ink hover:bg-ink hover:text-white">Volver al panel</a>
            </div>
        </form>
    </section>

@endsection

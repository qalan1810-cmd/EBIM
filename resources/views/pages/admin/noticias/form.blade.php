@extends('layouts.app')

@php($editando = $noticia->exists)

@section('title', ($editando ? 'Editar publicación' : 'Nueva publicación') . ' | Administración EBIM')

@section('content')

    <section data-reveal class="ebim-container pt-[70px] pb-[30px] max-md:pt-[50px]">
        <h1 class="text-[48px] font-black leading-[1] text-white max-lg:text-[38px] max-md:text-[30px]">
            {{ $editando ? 'Editar publicación' : 'Nueva publicación' }}
        </h1>

        <div class="mt-[30px]">
            @include('partials.admin-nav')
        </div>
    </section>

    <section data-reveal class="ebim-container pb-[80px] max-md:pb-[60px]">
        <div class="max-w-[900px] rounded-[25px] bg-white p-[35px] shadow-[0_0_12px_rgba(0,0,0,0.12)]">
            <form method="POST"
                action="{{ $editando ? route('admin.noticias.update', $noticia) : route('admin.noticias.store') }}">
                @csrf
                @if ($editando)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-3 gap-[20px] max-md:grid-cols-1">
                    <div class="max-md:col-span-full">
                        <label for="titulo" class="mb-2 block text-[15px] font-bold text-ink">Título</label>
                        <input id="titulo" name="titulo" type="text" value="{{ old('titulo', $noticia->titulo) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('titulo') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tipo" class="mb-2 block text-[15px] font-bold text-ink">Tipo de publicación</label>
                        <select id="tipo" name="tipo" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                            @foreach (\App\Models\Noticia::TIPOS as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(old('tipo', $noticia->tipo ?? \App\Models\Noticia::TIPO_NOTICIA) === $valor)>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="fecha" class="mb-2 block text-[15px] font-bold text-ink">Fecha de publicación</label>
                        <input id="fecha" name="fecha" type="date" value="{{ old('fecha', optional($noticia->fecha)->format('Y-m-d')) }}" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                        @error('fecha') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="resumen" class="mb-2 block text-[15px] font-bold text-ink">Resumen</label>
                    <textarea id="resumen" name="resumen" rows="3"
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">{{ old('resumen', $noticia->resumen) }}</textarea>
                    <p class="mt-1 text-[15px] text-ink">Texto breve que se muestra en la portada, debajo del título.</p>
                    @error('resumen') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="cuerpo" class="mb-2 block text-[15px] font-bold text-ink">Contenido completo</label>
                    <textarea id="cuerpo" name="cuerpo" rows="5"
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">{{ old('cuerpo', $noticia->cuerpo) }}</textarea>
                    @error('cuerpo') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="enlace" class="mb-2 block text-[15px] font-bold text-ink">Enlace de la publicación o del video</label>
                    <input id="enlace" name="enlace" type="url" value="{{ old('enlace', $noticia->enlace) }}"
                        placeholder="https://www.facebook.com/..."
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    <p class="mt-1 text-[15px] text-ink">
                        Pega aquí el enlace de la publicación de Facebook o del video de YouTube. La publicación se
                        mostrará incrustada en la página, sin necesidad de abrir el enlace.
                    </p>
                    @error('enlace') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="imagen" class="mb-2 block text-[15px] font-bold text-ink">Fotografía</label>
                    <input id="imagen" name="imagen" type="text" value="{{ old('imagen', $noticia->imagen) }}"
                        placeholder="/assets/ebim/noticias/mi-foto.jpg"
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    <p class="mt-1 text-[15px] text-ink">
                        Ruta de una fotografía guardada en el proyecto (por ejemplo
                        <code>/assets/ebim/noticias/ensayo.jpg</code>) o una dirección web completa. Opcional.
                    </p>
                    @error('imagen') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5 max-w-[280px]">
                    <label for="orden" class="mb-2 block text-[15px] font-bold text-ink">Orden de aparición</label>
                    <input id="orden" name="orden" type="number" min="0" value="{{ old('orden', $noticia->orden ?? 0) }}"
                        class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    @error('orden') <p class="mt-1 text-[15px] font-semibold text-brand">{{ $message }}</p> @enderror
                </div>

                <label class="mt-5 flex items-center gap-2 text-[17px] text-ink">
                    <input type="checkbox" name="activo" value="1" @checked(old('activo', $noticia->activo ?? true)) class="h-4 w-4">
                    <span>Mostrar esta noticia en la portada</span>
                </label>

                <div class="mt-7 flex flex-wrap gap-4">
                    <button type="submit" class="btn-ss">{{ $editando ? 'Guardar cambios' : 'Registrar publicación' }}</button>
                    <a href="{{ route('admin.noticias.index') }}"
                        class="inline-block rounded-[15px] border-2 border-ink px-6 py-[10px] text-[18px] font-bold text-ink hover:bg-ink hover:text-white">Cancelar</a>
                </div>
            </form>
        </div>
    </section>

@endsection

@extends('layouts.app')

@section('title', 'Página no encontrada | EBIM Oruro')

@section('content')
    <section class="flex min-h-[60vh] flex-col items-center justify-center pt-[150px] pb-[80px] text-center max-md:pt-[120px]">
        <div class="ebim-container flex flex-col items-center">
            <p class="text-[90px] font-black leading-none text-brand max-md:text-[64px]">404</p>
            <h1 class="mt-4 text-[40px] font-extrabold leading-[1.3] text-ink max-md:text-[28px]">Página no encontrada</h1>
            <p class="mt-4 max-w-[620px] text-[20px] text-ink max-md:text-[18px]">
                Lo sentimos, la página que buscas no existe o fue movida. Puedes volver al inicio o revisar las secciones principales del sitio.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ url('/') }}" class="btn-ss">Volver al inicio</a>
                <a href="{{ url('/inscripcion') }}" class="btn-ss">Ver inscripción</a>
            </div>
        </div>
    </section>
@endsection

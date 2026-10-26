@extends('layouts.app')

@section('title', 'Acceso denegado | EBIM Oruro')

@section('content')
    {{--
        Este 403 solo lo dispara el middleware "admin" sobre /admin/*, asi que
        siempre se pinta sobre el guindo del area privada (pagina-guindo, que el
        layout aplica por ruta): el texto va en blanco por eso.
    --}}
    <section class="flex min-h-[60vh] flex-col items-center justify-center pt-[150px] pb-[80px] text-center max-md:pt-[120px]">
        <div class="ebim-container flex flex-col items-center">
            <p class="text-[90px] font-black leading-none text-white/30 max-md:text-[64px]">403</p>
            <h1 class="mt-4 text-[40px] font-extrabold leading-[1.3] text-white max-md:text-[28px]">Acceso denegado</h1>
            <p class="mt-4 max-w-[620px] text-[20px] text-white max-md:text-[18px]">
                No tienes permisos para acceder a esta sección. Si eres estudiante, puedes consultar tus notas y tu horario desde tu portal académico.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ url('/') }}" class="btn-ss">Volver al inicio</a>
                <a href="{{ url('/portal') }}" class="btn-ss">Ir a mi portal</a>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Ingresar | EBIM Oruro')
@section('description', 'Acceso al Portal del Estudiante y al panel de administración de la Escuela Boliviana Intercultural de Música.')
@section('body-class', 'pagina-guindo')

@section('content')
    {{--
        El bloque se centra en el centro exacto de la pantalla: el padding
        superior e inferior son iguales (--header-h + 16px), de modo que el
        centro de la tarjeta cae en el centro del viewport y el pie queda
        siempre por debajo, al bajar.
    --}}
    <section class="ingreso-guindo flex min-h-svh items-center justify-center pt-[calc(var(--header-h)_+_16px)] pb-[calc(var(--header-h)_+_16px)]">
        <div class="ebim-container flex justify-center" data-reveal>
            <div class="w-full max-w-[480px] rounded-[25px] bg-white p-[40px] shadow-[0_22px_60px_rgba(0,0,0,0.38)] max-md:p-[26px]">
                <h1 class="text-[36px] font-extrabold leading-[1.2] text-ink max-md:text-[28px]">Ingresar</h1>
                <p class="mt-3 text-[17px] leading-[1.5] text-ink">
                    Accede a tu <strong>Portal del Estudiante</strong> para consultar tus notas y horarios. El personal autorizado ingresa al panel de administración.
                </p>

                @if ($errors->any())
                    <p class="mt-5 rounded-[15px] border-l-[6px] border-brand bg-[#fdecee] px-4 py-3 text-[16px] font-semibold text-brand">
                        {{ $errors->first() }}
                    </p>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-6" novalidate>
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-[15px] font-bold text-ink">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    </div>

                    <div class="mt-5">
                        <label for="password" class="mb-2 block text-[15px] font-bold text-ink">Contraseña</label>
                        <input id="password" name="password" type="password" required
                            class="w-full rounded-[15px] border-2 border-ink/20 bg-white px-4 py-3 text-[17px] text-ink outline-none focus:border-ink">
                    </div>

                    <label class="mt-5 flex items-center gap-2 text-[16px] text-ink">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4">
                        <span>Mantener mi sesión iniciada</span>
                    </label>

                    <button type="submit" class="btn-ss mt-7 w-full">Acceder</button>
                </form>

                <p class="mt-6 text-center text-[16px] text-ink">
                    ¿Aún no eres estudiante?
                    <a href="{{ url('/inscripcion') }}" class="underline hover:text-brand">Consulta los requisitos de inscripción</a>.
                </p>
            </div>
        </div>
    </section>
@endsection

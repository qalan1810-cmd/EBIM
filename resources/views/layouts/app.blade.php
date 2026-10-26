<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Escuela Boliviana Intercultural de Música | EBIM Oruro')</title>
    <meta name="description" content="@yield('description', 'Escuela Boliviana Intercultural de Música — formación musical intercultural en Oruro, Bolivia. Técnico Medio, Técnico Superior y Licenciatura en Música.')">

    <link rel="icon" href="/assets/sohosonic/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" href="/assets/sohosonic/img/favicon.png">

    {{--
        app.css se carga primero para fijar el orden de capas antes de las
        hojas de estilo heredadas.
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Estilos por página. --}}
    @stack('head')

    {{--
        Sin JavaScript no habría forma de revelar los bloques que esperan la
        animación de aparición, así que se muestran directamente. Es el mismo
        criterio que ya aplica prefers-reduced-motion en app.css.
    --}}
    <noscript>
        <style>
            [data-reveal] {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
    </noscript>
</head>
{{--
    pagina-guindo pinta el area privada (portal del estudiante y panel de
    administracion) con el mismo fondo guindo texturado de la pestaña
    "Ingresar". Se decide aqui por ruta para no tener que repetirlo en cada
    vista: solo las rutas autenticadas entran, las publicas no.
--}}
<body class="@yield('body-class') @yield('body_class') {{ request()->routeIs('portal.*', 'admin.*') ? 'pagina-guindo' : '' }}">

    @include('partials.header')

    <main id="content">
        {{--
            Los avisos flotan sobre la esquina superior (en movil, sobre el
            borde inferior) y se retiran solos: no empujan el contenido, asi
            que el titulo de la pagina no se mueve al guardar.
        --}}
        @if (session('exito') || session('error'))
            <div class="ebim-avisos">
                @if (session('exito'))
                    <div class="ebim-aviso" data-aviso data-duracion="6000" role="status">
                        <span class="ebim-aviso__icono" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </span>

                        <span class="ebim-aviso__cuerpo">
                            <span class="ebim-aviso__titulo">Listo</span>
                            <span class="ebim-aviso__mensaje">{{ session('exito') }}</span>
                        </span>

                        <button type="button" class="ebim-aviso__cerrar" data-aviso-cerrar aria-label="Cerrar aviso">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                stroke-linecap="round" aria-hidden="true">
                                <path d="M6 6l12 12M18 6L6 18" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="ebim-aviso ebim-aviso--error" data-aviso data-duracion="8000" role="alert">
                        <span class="ebim-aviso__icono" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8v5" />
                                <path d="M12 17h.01" />
                            </svg>
                        </span>

                        <span class="ebim-aviso__cuerpo">
                            <span class="ebim-aviso__titulo">No se pudo completar</span>
                            <span class="ebim-aviso__mensaje">{{ session('error') }}</span>
                        </span>

                        <button type="button" class="ebim-aviso__cerrar" data-aviso-cerrar aria-label="Cerrar aviso">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                stroke-linecap="round" aria-hidden="true">
                                <path d="M6 6l12 12M18 6L6 18" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>

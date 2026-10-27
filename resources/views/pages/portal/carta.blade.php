<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Carta de solicitud de notas | EBIM Oruro</title>

    @include('pages.portal.partials.estilos-impresion')
</head>

<body>
    @include('pages.portal.partials.barra-impresion')

    <main class="hoja">
        <header class="membrete">
            <img src="/assets/ebim/logo-ebim.png" alt="EBIM — Escuela Boliviana Intercultural de Música" class="logo">

            <div class="datos">
                <p>{{ config('ebim.contact.address') }}</p>
                <p>{{ config('ebim.contact.phone') }} &middot; {{ config('ebim.contact.email') }}</p>
            </div>
        </header>

        <p class="lugar-fecha">Oruro, {{ $fecha->translatedFormat('d \d\e F \d\e Y') }}</p>

        <div class="destinatario">
            <p>Señor Director Académico</p>
            <p>{{ config('ebim.full_name') }}</p>
            <p class="presente">Presente.-</p>
        </div>

        <p class="referencia">Ref.: Solicitud de notas</p>

        <p class="cuerpo">
            Yo, <strong>{{ $estudiante->name }}</strong>{{ $condicion }}, tengo a bien dirigirme a su autoridad para
            solicitar la emisión de mis notas correspondientes a la gestión <strong>{{ $gestion }}</strong>, para fines
            de trámite.
        </p>

        <p class="cuerpo">Sin otro particular, agradezco su atención.</p>

        <p class="despedida">Atentamente,</p>

        <div class="firma">
            <div class="linea"></div>
            <p class="nombre">{{ $estudiante->name }}</p>
            <p>C.I.: {{ $estudiante->carnet ?: '____________________' }}</p>
            <p>Estudiante</p>
        </div>

        <footer class="pie">
            Documento generado desde el Portal del Estudiante &middot; {{ $fecha->format('d/m/Y H:i') }}
        </footer>
    </main>
</body>

</html>

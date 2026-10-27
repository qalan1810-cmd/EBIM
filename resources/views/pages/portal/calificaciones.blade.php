<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Calificaciones actuales | EBIM Oruro</title>

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

        <h1 class="titulo-documento">Calificaciones actuales</h1>
        <p class="subtitulo-documento">Gestión {{ $gestion }} &middot; {{ $fecha->format('d/m/Y') }}</p>

        <div class="datos-estudiante">
            <p><span>Estudiante:</span> {{ $estudiante->name }}</p>
            <p><span>Código:</span> {{ $estudiante->codigo ?: 'No asignado' }}</p>
            <p><span>Carnet:</span> {{ $estudiante->carnet ?: 'No asignado' }}</p>
            <p><span>Curso:</span> {{ $estudiante->curso?->nombre ?? 'No asignado' }}</p>
            <p><span>Gestión:</span> {{ $gestion }}</p>
        </div>

        @if ($filasNotas->isEmpty())
            <p class="sin-notas">No hay calificaciones registradas para esta gestión.</p>
        @else
            <div class="tabla-scroll">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Asignatura</th>
                            @foreach ($parciales as $etiqueta)
                                <th class="num">{{ $etiqueta }}</th>
                            @endforeach
                            <th class="num">Nota final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filasNotas as $fila)
                            <tr>
                                <td>{{ $fila['asignatura'] }}</td>

                                @foreach ($fila['notas'] as $nota)
                                    <td class="num {{ $nota ? '' : 'vacia' }}">
                                        {{ $nota ? number_format((float) $nota->nota, 2) : '—' }}
                                    </td>
                                @endforeach

                                <td class="num final">{{ number_format($fila['final'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($promedio !== null)
                <p class="promedio">Promedio de notas: {{ number_format($promedio, 2) }}</p>
            @endif
        @endif

        <p class="nota-pie">
            Documento para conocimiento del estudiante. La emisión oficial de notas se realiza de forma personal en la
            escuela.
        </p>

        <footer class="pie">
            Documento generado desde el Portal del Estudiante &middot; {{ $fecha->format('d/m/Y H:i') }}
        </footer>
    </main>
</body>

</html>

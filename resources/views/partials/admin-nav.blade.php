@php
    // El rector administra todo el sistema; el director académico solo entra a
    // la sección de horarios, que es la que le corresponde elaborar.
    $items = collect([
        ['label' => 'Panel', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
        ['label' => 'Docentes', 'route' => 'admin.docentes.index', 'pattern' => 'admin.docentes.*'],
        ['label' => 'Noticias y comunicados', 'route' => 'admin.noticias.index', 'pattern' => 'admin.noticias.*'],
        ['label' => 'Contenidos', 'route' => 'admin.contenidos.edit', 'pattern' => 'admin.contenidos.*'],
        ['label' => 'Notas', 'route' => 'admin.notas.index', 'pattern' => 'admin.notas.*'],
        ['label' => 'Horarios', 'route' => 'admin.horarios.index', 'pattern' => 'admin.horarios.*'],
        ['label' => 'Usuarios', 'route' => 'admin.usuarios.index', 'pattern' => 'admin.usuarios.*'],
    ]);

    if (! auth()->user()->esAdmin()) {
        $items = $items->whereIn('label', ['Panel', 'Horarios']);
    }
@endphp

<nav class="rounded-[20px] bg-white p-[15px] shadow-[0_0_12px_rgba(0,0,0,0.12)]" aria-label="Administración">
    <ul class="flex flex-wrap gap-2">
        @foreach ($items as $item)
            <li>
                <a href="{{ route($item['route']) }}"
                    @class([
                        'inline-block rounded-[12px] px-5 py-3 text-[16px] font-bold',
                        'bg-brand text-white' => request()->routeIs($item['pattern']),
                        'text-ink hover:bg-mist' => ! request()->routeIs($item['pattern']),
                    ])>
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>

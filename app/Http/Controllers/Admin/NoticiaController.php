<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Administración de las noticias que se publican en la portada
 * (Histórico 1: mantener la información de la escuela actualizada).
 */
class NoticiaController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.noticias.index', [
            'noticias' => Noticia::orderByDesc('fecha')->orderBy('orden')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.noticias.form', [
            'noticia' => new Noticia(['fecha' => now()->toDateString()]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['activo'] = $request->boolean('activo');

        Noticia::create($datos);

        return redirect()->route('admin.noticias.index')->with('exito', 'Noticia registrada correctamente.');
    }

    public function edit(Noticia $noticia): View
    {
        return view('pages.admin.noticias.form', ['noticia' => $noticia]);
    }

    public function update(Request $request, Noticia $noticia): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['activo'] = $request->boolean('activo');

        $noticia->update($datos);

        return redirect()->route('admin.noticias.index')->with('exito', 'Noticia actualizada correctamente.');
    }

    public function destroy(Noticia $noticia): RedirectResponse
    {
        $noticia->delete();

        return redirect()->route('admin.noticias.index')->with('exito', 'Noticia eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:160'],
            'tipo' => ['required', 'in:' . implode(',', array_keys(Noticia::TIPOS))],
            'fecha' => ['required', 'date'],
            'resumen' => ['nullable', 'string'],
            'cuerpo' => ['nullable', 'string'],
            'enlace' => ['nullable', 'url', 'max:500'],
            'imagen' => ['nullable', 'string', 'max:500'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], [], [
            'titulo' => 'título',
            'tipo' => 'tipo de publicación',
            'fecha' => 'fecha de publicación',
            'resumen' => 'resumen',
            'cuerpo' => 'contenido',
            'enlace' => 'enlace del video o de la publicación',
            'imagen' => 'fotografía',
        ]);

        // "Mostrar el video en la página" es la opción por defecto, pero el
        // enlace es opcional: si viene vacío se guarda como null.
        foreach (['enlace', 'imagen'] as $campo) {
            $datos[$campo] = filled($datos[$campo] ?? null) ? trim($datos[$campo]) : null;
        }

        return $datos;
    }
}

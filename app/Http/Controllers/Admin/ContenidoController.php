<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contenido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Edición de los textos institucionales de la página web
 * (Histórico 4: mantener la información correcta y actualizada).
 */
class ContenidoController extends Controller
{
    /**
     * Campos editables por sección del sitio.
     */
    private const CAMPOS = [
        'historia' => 'Historia de la escuela',
        'historia_resumen' => 'Resumen de la historia (portada)',
        'cronologia' => 'Cronología de la escuela (Sobre EBIM)',
        'sobre_intro' => 'Texto inicial de la página Sobre EBIM',
        'rector' => 'Rector(a) de la escuela',
        'mision' => 'Misión',
        'vision' => 'Visión',
        'valores' => 'Valores',
        'requisitos' => 'Requisitos de inscripción',
        'pasos_inscripcion' => 'Pasos del proceso de inscripción',
    ];

    public function edit(): View
    {
        return view('pages.admin.contenidos.edit', [
            'campos' => self::CAMPOS,
            'contenidos' => Contenido::whereIn('clave', array_keys(self::CAMPOS))->get()->keyBy('clave'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Se validan explícitamente las claves conocidas: el comodín `*`
        // también alcanzaría a los campos `_token` y `_method` del formulario.
        $reglas = [];

        foreach (array_keys(self::CAMPOS) as $clave) {
            $reglas["{$clave}.titulo"] = ['required', 'string', 'max:160'];
            $reglas["{$clave}.cuerpo"] = ['required', 'string'];
            $reglas["{$clave}.foto"] = ['nullable', 'string', 'max:255'];
        }

        $datos = $request->validate($reglas, [], [
            'titulo' => 'título',
            'cuerpo' => 'contenido',
        ]);

        foreach ($datos as $clave => $valor) {
            Contenido::updateOrCreate(
                ['clave' => $clave],
                [
                    'titulo' => $valor['titulo'],
                    'cuerpo' => $valor['cuerpo'],
                    'foto' => $valor['foto'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.contenidos.edit')->with('exito', 'Información institucional actualizada.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocenteController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.docentes.index', [
            'docentes' => Docente::orderBy('orden')->orderBy('nombre')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.docentes.form', ['docente' => new Docente()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['activo'] = $request->boolean('activo');

        Docente::create($datos);

        return redirect()->route('admin.docentes.index')->with('exito', 'Docente registrado correctamente.');
    }

    public function edit(Docente $docente): View
    {
        return view('pages.admin.docentes.form', ['docente' => $docente]);
    }

    public function update(Request $request, Docente $docente): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['activo'] = $request->boolean('activo');
        $docente->update($datos);

        return redirect()->route('admin.docentes.index')->with('exito', 'Docente actualizado correctamente.');
    }

    public function destroy(Docente $docente): RedirectResponse
    {
        $docente->delete();

        return redirect()->route('admin.docentes.index')->with('exito', 'Docente eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'cargo' => ['required', 'string', 'max:120'],
            'especialidad' => ['required', 'string', 'max:120'],
            'formacion' => ['required', 'string'],
            'experiencia' => ['required', 'string'],
            'foto' => ['nullable', 'string', 'max:255'],
            'encuadre' => ['nullable', 'integer', 'between:0,100'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], [], [
            'nombre' => 'nombre completo',
            'cargo' => 'cargo',
            'especialidad' => 'especialidad',
            'formacion' => 'formación profesional',
            'experiencia' => 'experiencia y trayectoria',
            'foto' => 'fotografía',
            'encuadre' => 'encuadre de la foto',
        ]);

        $datos['foto'] = filled($datos['foto'] ?? null) ? $datos['foto'] : null;
        $datos['encuadre'] = (int) ($datos['encuadre'] ?? 50);

        return $datos;
    }
}

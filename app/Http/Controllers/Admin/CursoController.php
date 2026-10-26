<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Cursos de la escuela. Cada curso (por ejemplo 2 "A") tiene su propio
 * horario de clases.
 */
class CursoController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $curso = Curso::create($this->validar($request));

        return redirect()
            ->route('admin.horarios.index', ['curso' => $curso->id])
            ->with('exito', 'Curso creado correctamente.');
    }

    public function update(Request $request, Curso $curso): RedirectResponse
    {
        $curso->update($this->validar($request, $curso->id));

        return redirect()
            ->route('admin.horarios.index', ['curso' => $curso->id])
            ->with('exito', 'Curso actualizado correctamente.');
    }

    public function destroy(Curso $curso): RedirectResponse
    {
        if ($curso->horarios()->exists()) {
            return back()->with('error', 'No se puede eliminar un curso que todavía tiene horarios registrados.');
        }

        if ($curso->estudiantes()->exists()) {
            return back()->with('error', 'No se puede eliminar un curso que todavía tiene estudiantes asignados.');
        }

        $curso->delete();

        return redirect()->route('admin.horarios.index')->with('exito', 'Curso eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?int $id = null): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:40', Rule::unique('cursos', 'nombre')->ignore($id)],
            'orden' => ['nullable', 'integer', 'min:0', 'max:999'],
        ], [], [
            'nombre' => 'nombre del curso',
            'orden' => 'orden',
        ]);

        $datos['orden'] ??= 0;

        return $datos;
    }
}

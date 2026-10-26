<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Especialidad;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Gestión de usuarios (estudiantes y personal autorizado).
 */
class UsuarioController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.usuarios.index', [
            'usuarios' => User::with('curso')->orderByDesc('created_at')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.usuarios.form', [
            'usuario' => new User(['role' => 'estudiante']),
            'cursos' => Curso::ordenado()->get(),
            'especialidades' => Especialidad::orderBy('orden')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['password'] = Hash::make($datos['password']);

        User::create($datos);

        return redirect()->route('admin.usuarios.index')->with('exito', 'Usuario registrado correctamente.');
    }

    public function edit(User $usuario): View
    {
        return view('pages.admin.usuarios.form', [
            'usuario' => $usuario,
            'cursos' => Curso::ordenado()->get(),
            'especialidades' => Especialidad::orderBy('orden')->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $datos = $this->validar($request, $usuario->id);

        if (! empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        $usuario->update($datos);

        return redirect()->route('admin.usuarios.index')->with('exito', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puede eliminar su propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('exito', 'Usuario eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?int $id = null): array
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:6'],
            'role' => ['required', 'in:' . implode(',', array_keys(User::roles()))],
            'codigo' => ['nullable', 'string', 'max:30', Rule::unique('users', 'codigo')->ignore($id)],
            'curso_id' => ['nullable', 'exists:cursos,id'],
            'especialidad' => ['nullable', 'string', Rule::exists('especialidades', 'nombre')],
        ], [], [
            'name' => 'nombre completo',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'role' => 'rol',
            'codigo' => 'código de estudiante',
            'curso_id' => 'curso',
            'especialidad' => 'especialidad',
        ]);

        $datos['especialidad_id'] = ($datos['especialidad'] ?? null)
            ? Especialidad::where('nombre', $datos['especialidad'])->value('id')
            : null;

        unset($datos['especialidad']);

        return $datos;
    }
}

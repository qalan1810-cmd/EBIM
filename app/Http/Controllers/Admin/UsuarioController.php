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
        $esEstudiante = $datos['role'] === 'estudiante';
        $passwordInicial = null;

        if ($esEstudiante) {
            // La contraseña inicial no se escribe a mano: se deriva del carnet
            // y del nombre para que el estudiante pueda recibirla al inscribirse.
            $passwordInicial = User::generarPasswordEstudiante($datos['carnet'], $datos['name']);
            $datos['password'] = Hash::make($passwordInicial);
        } else {
            $datos['password'] = Hash::make($datos['password']);
        }

        $usuario = User::create($datos);

        $respuesta = redirect()->route('admin.usuarios.index')->with(
            'exito',
            $esEstudiante ? 'Estudiante registrado correctamente.' : 'Usuario registrado correctamente.'
        );

        if ($esEstudiante) {
            $respuesta->with('credenciales', [
                'codigo' => $usuario->codigo,
                'password' => $passwordInicial,
                'actualizada' => false,
            ]);
        }

        return $respuesta;
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
        $esEstudiante = $datos['role'] === 'estudiante';
        $passwordInicial = null;
        $passwordActualizada = false;

        if ($esEstudiante) {
            // Si cambia el carnet o el nombre, la contraseña derivada también
            // cambia. Se mantiene sincronizada y se muestra una sola vez.
            $cambiaIdentidad = $usuario->carnet !== $datos['carnet']
                || $usuario->name !== $datos['name'];

            if ($cambiaIdentidad) {
                $passwordInicial = User::generarPasswordEstudiante($datos['carnet'], $datos['name']);
                $datos['password'] = Hash::make($passwordInicial);
                $passwordActualizada = true;
            } else {
                unset($datos['password']);
            }
        } elseif (! empty($datos['password'] ?? null)) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        $usuario->update($datos);

        $respuesta = redirect()->route('admin.usuarios.index')->with(
            'exito',
            $esEstudiante ? 'Estudiante actualizado correctamente.' : 'Usuario actualizado correctamente.'
        );

        if ($passwordActualizada) {
            $respuesta->with('credenciales', [
                'codigo' => $usuario->codigo,
                'password' => $passwordInicial,
                'actualizada' => true,
            ]);
        }

        return $respuesta;
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
        $rol = (string) $request->input('role');
        $esEstudiante = $rol === 'estudiante';
        $editando = $id !== null;

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'email' => [
                $esEstudiante ? 'nullable' : 'required',
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'codigo' => $esEstudiante
                ? ['required', 'digits_between:3,10', Rule::unique('users', 'codigo')->ignore($id)]
                : ['nullable', 'string', 'max:30', Rule::unique('users', 'codigo')->ignore($id)],
            'carnet' => $esEstudiante
                ? ['required', 'digits_between:6,9', Rule::unique('users', 'carnet')->ignore($id)]
                : ['nullable', 'string', 'max:20', Rule::unique('users', 'carnet')->ignore($id)],
            'curso_id' => ['nullable', 'exists:cursos,id'],
            'especialidad' => ['nullable', 'string', Rule::exists('especialidades', 'nombre')],
            'password' => $esEstudiante
                ? ['nullable']
                : [$editando ? 'nullable' : 'required', 'string', 'min:6'],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'name.max' => 'El nombre completo no puede superar los 120 caracteres.',
            'role.required' => 'Seleccione un rol.',
            'role.in' => 'Seleccione un rol válido.',
            'email.required' => 'El correo electrónico es obligatorio para el personal autorizado.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Ese correo electrónico ya está registrado.',
            'codigo.required' => 'El código de estudiante es obligatorio.',
            'codigo.digits_between' => 'El código de estudiante debe contener solo números, entre 3 y 10 dígitos.',
            'codigo.unique' => 'Ese código de estudiante ya está registrado.',
            'carnet.required' => 'El carnet de identidad es obligatorio.',
            'carnet.digits_between' => 'El carnet de identidad debe contener solo números, entre 6 y 9 dígitos.',
            'carnet.unique' => 'Ese carnet de identidad ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'curso_id.exists' => 'Seleccione un curso válido.',
            'especialidad.exists' => 'Seleccione una especialidad válida.',
        ], [
            'name' => 'nombre completo',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'role' => 'rol',
            'codigo' => 'código de estudiante',
            'carnet' => 'carnet de identidad',
            'curso_id' => 'curso',
            'especialidad' => 'especialidad',
        ]);

        // Los estudiantes no usan correo ni contraseña manual. El personal
        // autorizado no usa carnet ni código de estudiante.
        $datos['email'] = $esEstudiante ? null : $datos['email'];
        $datos['codigo'] = $esEstudiante ? $datos['codigo'] : null;
        $datos['carnet'] = $esEstudiante ? $datos['carnet'] : null;

        $datos['especialidad_id'] = ($datos['especialidad'] ?? null)
            ? Especialidad::where('nombre', $datos['especialidad'])->value('id')
            : null;

        unset($datos['especialidad']);

        return $datos;
    }
}

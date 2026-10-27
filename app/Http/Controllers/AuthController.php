<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de acceso (estudiantes y personal autorizado).
     */
    public function showLogin(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Autentica al usuario y lo envía a su panel según su rol.
     *
     * Los estudiantes usan su código numérico; el personal autorizado sigue
     * usando su correo electrónico. El campo se llama `login` y se acepta
     * también el antiguo campo `email` para no romper formularios guardados.
     */
    public function login(Request $request): RedirectResponse
    {
        $campo = $request->exists('login') ? 'login' : 'email';

        $mensajes = [
            'password.required' => 'La contraseña es obligatoria.',
            $campo.'.required' => $campo === 'login'
                ? 'Ingrese su código de estudiante o correo electrónico.'
                : 'Ingrese su correo electrónico.',
            $campo.'.max' => 'El dato ingresado es demasiado largo.',
        ];

        $request->validate([
            $campo => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], $mensajes, [
            $campo => $campo === 'login' ? 'código o correo electrónico' : 'correo electrónico',
            'password' => 'contraseña',
        ]);

        $identificador = trim((string) $request->input($campo));

        // Los códigos de estudiante son solo dígitos; cualquier otro valor se
        // busca como correo electrónico del personal autorizado.
        $campoCredencial = ctype_digit($identificador) ? 'codigo' : 'email';
        $credenciales = [
            $campoCredencial => $identificador,
            'password' => (string) $request->input('password'),
        ];

        if (! Auth::attempt($credenciales, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                $campo => 'El código o correo y la contraseña no son correctos.',
            ]);
        }

        $request->session()->regenerate();

        $usuario = $request->user();
        $esPersonal = $usuario->esPersonal();

        // `url.intended` lo fija el middleware de autenticación cuando un
        // visitante intenta abrir una ruta privada. Solo se respeta si la ruta
        // corresponde al rol del usuario, para no enviar a un estudiante al
        // panel de administración (ni al personal al portal) tras iniciar
        // sesión, donde recibiría un error de permisos.
        $intended = $request->session()->pull('url.intended');
        $rutaIntendida = is_string($intended) ? (parse_url($intended, PHP_URL_PATH) ?: '') : '';
        $prefijo = $esPersonal ? '/admin' : '/portal';

        $destino = str_starts_with($rutaIntendida, $prefijo)
            ? $intended
            : ($esPersonal ? route('admin.dashboard') : route('portal.index'));

        return redirect()->to($destino)->with(
            'exito',
            $esPersonal
                ? 'Bienvenido al panel de administración.'
                : 'Bienvenido a tu portal, '.$usuario->primerNombre().'.'
        );
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('exito', 'Sesión cerrada correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pages.perfil.edit', [
            'usuario' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                $usuario->esPersonal() ? 'required' : 'nullable',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'eliminar_foto' => ['nullable', 'boolean'],
            'password_actual' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio para el personal autorizado.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.unique' => 'Ya existe una cuenta con ese correo electrónico.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser JPG, PNG o WebP.',
            'foto.max' => 'La foto no puede superar los 2 MB.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        if (! empty($datos['password'])) {
            if (empty($datos['password_actual'])) {
                throw ValidationException::withMessages([
                    'password_actual' => 'Debes ingresar tu contraseña actual para cambiarla.',
                ]);
            }

            if (! Hash::check($datos['password_actual'], $usuario->password)) {
                throw ValidationException::withMessages([
                    'password_actual' => 'La contraseña actual no es correcta.',
                ]);
            }

            $usuario->password = Hash::make($datos['password']);
        }

        $usuario->name = $datos['name'];
        $usuario->email = ($datos['email'] ?? null) ?: null;

        if ($request->boolean('eliminar_foto')) {
            $this->borrarFoto($usuario);
            $usuario->foto = null;
        }

        if ($request->hasFile('foto')) {
            $this->borrarFoto($usuario);

            $archivo = $request->file('foto');
            $extension = $archivo->extension() ?: 'jpg';
            $nombre = 'perfil-'.$usuario->id.'-'.Str::random(10).'.'.$extension;
            $carpeta = public_path('assets/ebim/perfiles');

            if (! is_dir($carpeta)) {
                mkdir($carpeta, 0755, true);
            }

            $archivo->move($carpeta, $nombre);
            $usuario->foto = 'assets/ebim/perfiles/'.$nombre;
        }

        $usuario->save();

        return redirect()->route('perfil.edit')->with('exito', 'Tu perfil se actualizó correctamente.');
    }

    /**
     * Borra la foto personalizada del usuario, nunca las imágenes
     * predeterminadas del sistema.
     */
    private function borrarFoto(User $usuario): void
    {
        $foto = $usuario->foto ? ltrim($usuario->foto, '/') : null;

        if (! $foto || ! Str::startsWith($foto, 'assets/ebim/perfiles/')) {
            return;
        }

        $ruta = public_path($foto);

        if (is_file($ruta) && ! Str::endsWith($ruta, 'default-estudiante.svg') && ! Str::endsWith($ruta, 'default-personal.svg')) {
            unlink($ruta);
        }
    }
}

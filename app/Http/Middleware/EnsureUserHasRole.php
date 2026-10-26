<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Requiere que el usuario autenticado tenga alguno de los roles indicados.
 *
 * Ejemplos: ->middleware('role:admin') o ->middleware('role:admin,director').
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! in_array($usuario->role, $roles, true)) {
            abort(403, 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

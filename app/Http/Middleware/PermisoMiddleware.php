<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;

class PermisoMiddleware
{
    public function handle(Request $request, Closure $next, ...$permisos)
    {
        $user = Usuario::where('usuario', Session::get('user_usuario'))->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        // Administrador tiene todos los permisos
        if ($user->rol === 'Administrador') {
            return $next($request);
        }

        // Verificar que el usuario tenga al menos uno de los permisos requeridos
        foreach ($permisos as $permiso) {
            if ($user->hasPermiso($permiso)) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}
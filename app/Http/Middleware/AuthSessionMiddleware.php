<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthSessionMiddleware
{
    /**
     * Verifica que haya sesión iniciada por el sistema custom
     * (Session::put('user_usuario', ...) desde AuthController).
     * NO usa Auth::user() porque el login es custom.
     *
     * Además revalida contra DB en cada request para que un usuario
     * soft-deleted pierda el acceso sin esperar a que cierre sesión.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Session::has('user_usuario')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión');
        }

        // Revalidar contra DB (SoftDeletes excluye automáticamente los borrados).
        $usuario = Usuario::where('usuario', Session::get('user_usuario'))->first();

        if (! $usuario) {
            Session::flush();
            return redirect()->route('login')
                ->with('error', 'Tu sesión expiró');
        }

        return $next($request);
    }
}

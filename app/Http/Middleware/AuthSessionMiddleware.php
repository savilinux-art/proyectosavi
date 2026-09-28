<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthSessionMiddleware
{
    /**
     * Verifica que haya sesión iniciada por el sistema custom
     * (Session::put('user_usuario', ...) desde AuthController).
     * NO usa Auth::user() porque el login es custom.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Session::has('user_usuario')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión');
        }

        return $next($request);
    }
}

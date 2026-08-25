<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdministradorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }
        return $next($request);
    }
}
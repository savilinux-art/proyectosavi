<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contraseña' => 'required|string'
        ]);

        $usuario = Usuario::where('usuario', $request->usuario)->first();

        if ($usuario && Hash::check($request->contraseña, $usuario->contraseña)) {
            Session::put('user_id', $usuario->id);
            Session::put('user_usuario', $usuario->usuario);
            Session::put('user_nombre', $usuario->nombre);
            Session::put('user_rol', $usuario->rol);

            return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $usuario->nombre);
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}
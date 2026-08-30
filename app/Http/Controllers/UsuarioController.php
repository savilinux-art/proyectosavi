<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    private function verificarAdmin()
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No autorizado');
        }
        return null;
    }

   public function index()
{
    $usuarios = Usuario::all(); // o con orden, filtros, etc.
    return view('usuarios.index', compact('usuarios'));
}

    public function create()
    {
        if ($redir = $this->verificarAdmin()) return $redir;
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if ($redir = $this->verificarAdmin()) return $redir;

        $request->validate([
            'usuario' => 'required|string|unique:usuarios',
            'nombre' => 'required|string',
            'correo' => 'required|email|unique:usuarios',
            'contraseña' => 'required|string|min:6|confirmed',
            'telegram_chat_id' => 'nullable|string',
            'rol' => 'required|exists:roles,rol'
        ]);

        DB::beginTransaction();
        try {
            Usuario::create([
                'usuario' => $request->usuario,
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'contraseña' => Hash::make($request->contraseña),
                'telegram_chat_id' => $request->telegram_chat_id,
                'rol' => $request->rol
            ]);
            DB::commit();
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if ($redir = $this->verificarAdmin()) return $redir;
        $usuario = Usuario::findOrFail($id);
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }
public function update(Request $request, $id)
{
    $usuario = Usuario::findOrFail($id);

    $request->validate([
        'usuario' => 'required|string|max:255|unique:usuarios,usuario,' . $id,
        'nombre' => 'required|string|max:255',
        'correo' => 'required|email|max:255|unique:usuarios,correo,' . $id,
        'telegram_chat_id' => 'nullable|string|max:255', // ← Opcional
        'contraseña' => 'nullable|string|min:6',
        'rol' => 'required|string|exists:roles,rol',
    ]);

    $data = $request->all();

    // Si no se envió contraseña, la eliminamos del array para no actualizarla
    if (empty($data['contraseña'])) {
        unset($data['contraseña']);
    } else {
        $data['contraseña'] = bcrypt($data['contraseña']);
    }

    // Si telegram_chat_id está vacío, lo guardamos como null
    if (empty($data['telegram_chat_id'])) {
        $data['telegram_chat_id'] = null;
    }

    $usuario->update($data);

    return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
}
   

    public function destroy($id)
    {
        if ($redir = $this->verificarAdmin()) return $redir;
        $usuario = Usuario::findOrFail($id);
        if ($usuario->usuario == 'admin') {
            return back()->with('error', 'No se puede eliminar al admin principal');
        }
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado');
    }
}
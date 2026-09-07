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
/**
 * Display the specified user.
 */
public function show($id)
{
    $usuario = Usuario::findOrFail($id);
    return view('usuarios.show', compact('usuario'));
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
    'usuario' => 'required|unique:usuarios,usuario,' . $usuario->id,
    'nombre' => 'required',
    'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
    'telegram_chat_id' => 'nullable|unique:usuarios,telegram_chat_id,' . $usuario->id,
    'traccar_device_id' => 'nullable|unique:usuarios,traccar_device_id,' . $usuario->id,
    'rol' => 'required|exists:roles,rol',
    'contraseña' => 'required|min:6',
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
    // 1. Validar los datos
    $request->validate([
        'usuario' => 'required|unique:usuarios,usuario,' . $id,
        'nombre' => 'required',
        'correo' => 'required|email|unique:usuarios,correo,' . $id,
        'telegram_chat_id' => 'nullable|unique:usuarios,telegram_chat_id,' . $id,
        'traccar_device_id' => 'nullable|unique:usuarios,traccar_device_id,' . $id,  // ← Validación
        'rol' => 'required|exists:roles,rol',
    ]);

    // 2. Buscar el usuario
    $usuario = Usuario::findOrFail($id);

    // 3. Asignar los valores
    $usuario->usuario = $request->usuario;
    $usuario->nombre = $request->nombre;
    $usuario->correo = $request->correo;
    $usuario->telegram_chat_id = $request->telegram_chat_id;
    $usuario->traccar_device_id = $request->traccar_device_id;  // ← Aquí se asigna el valor
    $usuario->rol = $request->rol;

    // Si estás actualizando la contraseña
    if ($request->filled('contraseña')) {
        $usuario->contraseña = bcrypt($request->contraseña);
    }

    // 4. Guardar en la base de datos
    $usuario->save();  // ← Aquí se guarda el campo en la tabla

    return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
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
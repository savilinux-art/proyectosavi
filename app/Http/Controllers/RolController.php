<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Permiso;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
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
        if ($redir = $this->verificarAdmin()) return $redir;
        $roles = Rol::withCount('usuarios')->with('permisos')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        if ($redir = $this->verificarAdmin()) return $redir;
        $permisos = Permiso::all()->groupBy('modulo');
        return view('roles.create', compact('permisos'));
    }

    public function store(Request $request)
    {
        if ($redir = $this->verificarAdmin()) return $redir;

        $request->validate([
            'rol' => 'required|string|unique:roles'
        ]);

        DB::beginTransaction();
        try {
            $rol = Rol::create(['rol' => $request->rol]);
            if ($request->has('permisos')) {
                $rol->permisos()->sync($request->permisos);
            }
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Rol creado');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if ($redir = $this->verificarAdmin()) return $redir;
        $rol = Rol::findOrFail($id);
        $permisos = Permiso::all()->groupBy('modulo');
        $rolPermisos = $rol->permisos->pluck('id')->toArray();
        return view('roles.edit', compact('rol', 'permisos', 'rolPermisos'));
    }

    public function update(Request $request, $id)
    {
        // Validar y sincronizar permisos
    }

    public function destroy($id)
    {
        if ($redir = $this->verificarAdmin()) return $redir;
        $rol = Rol::findOrFail($id);
        if ($rol->usuarios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar, tiene usuarios asignados');
        }
        $rol->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado');
    }
}
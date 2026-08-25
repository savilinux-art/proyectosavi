<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $permisos = Permiso::all()->groupBy('modulo');
        return view('permisos.index', compact('permisos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $modulos = $this->getModulos();
        return view('permisos.create', compact('modulos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permisos',
            'descripcion' => 'nullable|string|max:500',
            'modulo' => 'required|string|max:255'
        ]);

        Permiso::create($request->all());

        return redirect()->route('permisos.index')
            ->with('success', 'Permiso creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $permiso = Permiso::with('roles')->findOrFail($id);
        return view('permisos.show', compact('permiso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $permiso = Permiso::findOrFail($id);
        $modulos = $this->getModulos();
        return view('permisos.edit', compact('permiso', 'modulos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permisos,slug,' . $id,
            'descripcion' => 'nullable|string|max:500',
            'modulo' => 'required|string|max:255'
        ]);

        $permiso = Permiso::findOrFail($id);
        $permiso->update($request->all());

        return redirect()->route('permisos.index')
            ->with('success', 'Permiso actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return redirect()->route('permisos.index')
            ->with('success', 'Permiso eliminado exitosamente');
    }

    private function getModulos()
    {
        return [
            'dashboard' => 'Dashboard',
            'inventario' => 'Inventario',
            'ventas' => 'Ventas',
            'instalaciones' => 'Instalaciones',
            'clientes' => 'Clientes',
            'proyectos' => 'Proyectos',
            'asignaciones' => 'Asignaciones',
            'reportes' => 'Reportes',
            'usuarios' => 'Usuarios',
            'roles' => 'Roles y Permisos',
            'configuracion' => 'Configuración'
        ];
    }
}
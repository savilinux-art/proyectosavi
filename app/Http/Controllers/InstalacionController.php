<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instalacion;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class InstalacionController extends Controller
{
    public function index()
    {
        $rol = Session::get('user_rol');
        $user = Session::get('user_usuario');

        if ($rol == 'Instalador') {
            $instalaciones = Instalacion::whereHas('instaladores', function ($q) use ($user) {
                $q->where('instalador_usuario', $user);
            })->with(['proyecto', 'instaladores'])->get();
        } else {
            $instalaciones = Instalacion::with(['proyecto', 'instaladores'])->get();
        }

        return view('instalaciones.index', compact('instalaciones'));
    }

    public function create()
    {
        $proyectos = Venta::where('venta_ganada', true)->get();
        $instaladores = Usuario::where('rol', 'Instalador')->get();
        $estatus = ['preparacion', 'en_proceso', 'programacion', 'pruebas', 'entrega'];
        return view('instalaciones.create', compact('proyectos', 'instaladores', 'estatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto',
            'fecha_hora_inicio' => 'required|date',
            'estatus_instalacion' => 'required|string',
            'instaladores' => 'required|array|min:1',
            'instaladores.*' => 'exists:usuarios,usuario',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['check_list'] = json_encode($request->check_list ?? []);

            // Procesar archivos (evidencias) si se suben
            // ...

            $instalacion = Instalacion::create($data);
            $instalacion->instaladores()->sync($request->instaladores);

            DB::commit();
            return redirect()->route('instalaciones.index')->with('success', 'Instalación creada');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $instalacion = Instalacion::with(['proyecto', 'instaladores'])->findOrFail($id);
        return view('instalaciones.show', compact('instalacion'));
    }

    public function edit($id)
    {
        $instalacion = Instalacion::with('instaladores')->findOrFail($id);
        $proyectos = Venta::where('venta_ganada', true)->get();
        $instaladores = Usuario::where('rol', 'Instalador')->get();
        $estatus = ['preparacion', 'en_proceso', 'programacion', 'pruebas', 'entrega'];
        $instaladoresSeleccionados = $instalacion->instaladores->pluck('usuario')->toArray();

        return view('instalaciones.edit', compact('instalacion', 'proyectos', 'instaladores', 'estatus', 'instaladoresSeleccionados'));
    }

    public function update(Request $request, $id)
    {
        // Validar y actualizar, sincronizar instaladores
        // ...
    }

    public function destroy($id)
    {
        $instalacion = Instalacion::findOrFail($id);
        $instalacion->delete();
        return redirect()->route('instalaciones.index')->with('success', 'Instalación eliminada');
    }
}
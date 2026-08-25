<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rol = Session::get('user_rol');
        $user_usuario = Session::get('user_usuario');

        if ($rol == 'Instalador') {
            // 🔥 CORREGIDO: Usar la relación muchos a muchos a través de instaladores
            $proyectos = Proyecto::whereHas('venta', function($q) use ($user_usuario) {
                $q->whereHas('instalaciones', function($sub) use ($user_usuario) {
                    $sub->whereHas('instaladores', function($inner) use ($user_usuario) {
                        $inner->where('instalador_usuario', $user_usuario);
                    });
                });
            })->with(['venta', 'modificadoPor'])->get();
        } else {
            $proyectos = Proyecto::with(['venta', 'modificadoPor'])->get();
        }

        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ventas = Venta::where('venta_ganada', true)->get();
        $usuarios = Usuario::all();
        return view('proyectos.create', compact('ventas', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto|unique:proyectos',
            'correo_electronico' => 'required|email|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'credenciales' => 'nullable|string',
            'propuesta_economica' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'archivo_as_built' => 'nullable|file|mimes:pdf,dwg|max:5120',
            'salida_inventario' => 'nullable|file|mimes:pdf|max:5120',
            'devolucion_inventario' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['modificado_por'] = Session::get('user_usuario');

            if ($request->hasFile('propuesta_economica')) {
                $data['propuesta_economica'] = file_get_contents($request->file('propuesta_economica')->getRealPath());
            }

            if ($request->hasFile('archivo_as_built')) {
                $data['archivo_as_built'] = file_get_contents($request->file('archivo_as_built')->getRealPath());
            }

            if ($request->hasFile('salida_inventario')) {
                $data['salida_inventario'] = file_get_contents($request->file('salida_inventario')->getRealPath());
            }

            if ($request->hasFile('devolucion_inventario')) {
                $data['devolucion_inventario'] = file_get_contents($request->file('devolucion_inventario')->getRealPath());
            }

            Proyecto::create($data);

            DB::commit();

            return redirect()->route('proyectos.index')
                ->with('success', 'Proyecto creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proyecto = Proyecto::with(['venta', 'modificadoPor'])->findOrFail($id);
        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $ventas = Venta::where('venta_ganada', true)->get();
        $usuarios = Usuario::all();
        return view('proyectos.edit', compact('proyecto', 'ventas', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto|unique:proyectos,nombre_proyecto,' . $id,
            'correo_electronico' => 'required|email|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'credenciales' => 'nullable|string',
            'propuesta_economica' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'archivo_as_built' => 'nullable|file|mimes:pdf,dwg|max:5120',
            'salida_inventario' => 'nullable|file|mimes:pdf|max:5120',
            'devolucion_inventario' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $proyecto = Proyecto::findOrFail($id);
            $data = $request->all();
            $data['modificado_por'] = Session::get('user_usuario');

            if ($request->hasFile('propuesta_economica')) {
                $data['propuesta_economica'] = file_get_contents($request->file('propuesta_economica')->getRealPath());
            }

            if ($request->hasFile('archivo_as_built')) {
                $data['archivo_as_built'] = file_get_contents($request->file('archivo_as_built')->getRealPath());
            }

            if ($request->hasFile('salida_inventario')) {
                $data['salida_inventario'] = file_get_contents($request->file('salida_inventario')->getRealPath());
            }

            if ($request->hasFile('devolucion_inventario')) {
                $data['devolucion_inventario'] = file_get_contents($request->file('devolucion_inventario')->getRealPath());
            }

            $proyecto->update($data);

            DB::commit();

            return redirect()->route('proyectos.index')
                ->with('success', 'Proyecto actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente');
    }
}
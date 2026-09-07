<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // Mostrar todas las ventas metodo index
    public function index()
    {
        $ventas = Venta::with('vendedor')->get();
        return view('ventas.index', compact('ventas'));
    }

    // Mostrar formulario para crear una nueva venta metodo create
    public function create()
    {
        $vendedores = Usuario::whereIn('rol', ['Ventas', 'Administrador'])->get();
        return view('ventas.create', compact('vendedores'));
    }

    // Guardar nueva venta en la base de datos metodo store
    public function store(Request $request)
    {
        $request->validate([
            'titulo_venta' => 'required|string',
            'nombre_proyecto' => 'required|string|unique:ventas',
            'moneda' => 'required|string',
            'monto_venta' => 'required|numeric',
            'requerimiento_venta' => 'required|string',
            'fecha_hora_levantamiento' => 'required|date',
            'venta_ganada' => 'required|boolean',
            'estatus' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['vendedor'] = Session::get('user_usuario');

            if ($request->hasFile('cotizacion')) {
                $data['cotizacion'] = file_get_contents($request->file('cotizacion')->getRealPath());
            }

            Venta::create($data);
            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta creada');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Mostrar formulario para editar una venta metodo edit
public function edit($id)
{
    // Buscar la venta por ID
    $venta = Venta::findOrFail($id);

    // Obtener datos necesarios para el formulario (ej. vendedores, estatus, etc.)
    $vendedores = Usuario::where('rol', 'Ventas')->get();
    $estatus = ['prospeccion', 'levantamiento', 'cotizacion', 'cierre_venta'];

    return view('ventas.edit', compact('venta', 'vendedores', 'estatus'));
}

// Actualizar una venta metodo update
public function update(Request $request, $id)
{
    $request->validate([
        'titulo_venta' => 'required|string',
        'nombre_proyecto' => 'required|string|unique:ventas,nombre_proyecto,' . $id,
        'moneda' => 'required|string',
        'monto_venta' => 'required|numeric',
        'requerimiento_venta' => 'required|string',
        'vendedor' => 'required|exists:usuarios,usuario',
        'estatus' => 'required|exists:estatus,estatus',
        // ... otras validaciones
    ]);

    $venta = Venta::findOrFail($id);
    $venta->update($request->all());

    return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente.');
}
/**
 * Display the specified venta.
 */
public function show($id)
{
    // Buscar la venta con relaciones necesarias
    $venta = Venta::with(['proyecto', 'instalaciones'])->findOrFail($id);

    // Obtener instalaciones relacionadas (si las hay)
    $instalaciones = $venta->instalaciones; // Relación definida en Venta

    return view('ventas.show', compact('venta', 'instalaciones'));
}

/**
 * Remove the specified venta from storage.
 */
public function destroy($id)
{
    $venta = Venta::findOrFail($id);

    // Verificar si la venta tiene instalaciones asociadas
    if ($venta->instalaciones()->count() > 0) {
        return back()->with('error', 'No se puede eliminar la venta porque tiene instalaciones asociadas.');
    }

    // Opcional: verificar si tiene proyectos asociados
    // if ($venta->proyecto) {
    //     return back()->with('error', 'No se puede eliminar porque tiene un proyecto asociado.');
    // }

    $venta->delete();

    return redirect()->route('ventas.index')
        ->with('success', 'Venta eliminada exitosamente.');
}



}
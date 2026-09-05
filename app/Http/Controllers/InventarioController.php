<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Categoria;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index()
    {
        $inventario = Inventario::with(['categoriaRelacion', 'modificadoPor'])->get();
        return view('inventario.index', compact('inventario'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('inventario.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'modelo' => 'required|string',
            'descripcion' => 'required|string',
            'marca' => 'required|string',
            'categoria' => 'required|exists:categorias,nombre_categoria',
            'existencia' => 'required|integer|min:0',
            'almacen_url' => 'required|string',
            'apea' => 'required|string',
            'comentarios' => 'nullable|string',
            'imagen_url' => 'nullable|string'
        ]);

        DB::beginTransaction();
        
        try {
            $data = $request->all();
            $data['fecha_modificacion'] = now();
            $data['modificado_por'] = Session::get('user_usuario');

            if ($request->hasFile('imagen')) {
                $data['imagen_url'] = file_get_contents($request->file('imagen')->getRealPath());
            }

            $inventario = Inventario::create($data);

            // Registrar movimiento
            MovimientoInventario::create([
                'inventario_id' => $inventario->id,
                'entrada' => $request->existencia,
                'modificado_por' => Session::get('user_usuario')
            ]);

            DB::commit();
            return redirect()->route('inventario.index')->with('success', 'Producto agregado');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

   public function show($id)
{
    $inventario = Inventario::with(['categoriaRelacion', 'modificadoPor'])->findOrFail($id);
    $movimientos = MovimientoInventario::where('inventario_id', $id)->with('modificadoPor')->get();

    // Calcular totales de movimientos
    $totalEntradas = $movimientos->sum('entrada');
    $totalSalidas = $movimientos->sum('salida');
    $totalAjustes = $movimientos->sum('ajuste');
    $totalApartados = $movimientos->sum('apartado');
    $totalDevoluciones = $movimientos->sum('devolucion');

    return view('inventario.show', compact(
        'inventario',
        'movimientos',
        'totalEntradas',
        'totalSalidas',
        'totalAjustes',
        'totalApartados',
        'totalDevoluciones'
    ));
}

    public function edit($id)
    {
        $inventario = Inventario::findOrFail($id);
        $categorias = Categoria::all();
        return view('inventario.edit', compact('inventario', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        // Similar a store pero actualizando y registrando ajustes si cambia existencia
        // ... (puedes copiar la lógica de versiones anteriores)
    }

    public function destroy($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->delete();
        return redirect()->route('inventario.index')->with('success', 'Producto eliminado');
    }

    public function export()
    {
        // Lógica para exportar a CSV (ya la tienes)
    }
}
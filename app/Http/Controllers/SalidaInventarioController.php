<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalidaInventario;
use App\Models\Inventario;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SalidaInventarioController extends Controller
{
    public function index()
    {
        $salidas = SalidaInventario::with(['proyecto', 'entregadoPor', 'entregadoA'])->get();
        return view('salidas.index', compact('salidas'));
    }

    public function create()
    {
        $proyectos = Venta::where('venta_ganada', true)->get();
        $usuarios = Usuario::all();
        $productos = Inventario::where('existencia', '>', 0)->get();
        return view('salidas.create', compact('proyectos', 'usuarios', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto',
            'entregado_a' => 'required|exists:usuarios,usuario',
            'productos' => 'required|array|min:1',
            'productos.*.inventario_id' => 'required|exists:inventario,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['entregado_por'] = Session::get('user_usuario');
            $data['fecha_hora_salida'] = now();
            $data['productos'] = json_encode($request->productos);

            // Descontar stock
            foreach ($request->productos as $item) {
                $producto = Inventario::find($item['inventario_id']);
                if ($producto->existencia < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->modelo}");
                }
                $producto->existencia -= $item['cantidad'];
                $producto->save();
            }

            $salida = SalidaInventario::create($data);
            DB::commit();

            return redirect()->route('salidas.index')
                ->with('success', 'Salida registrada. Puedes descargar el PDF.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $salida = SalidaInventario::with(['proyecto', 'entregadoPor', 'entregadoA'])->findOrFail($id);
        return view('salidas.show', compact('salida'));
    }

    public function destroy($id)
    {
        // Restaurar stock antes de eliminar
        $salida = SalidaInventario::findOrFail($id);
        $productos = json_decode($salida->productos, true);
        foreach ($productos as $item) {
            $producto = Inventario::find($item['inventario_id']);
            if ($producto) {
                $producto->existencia += $item['cantidad'];
                $producto->save();
            }
        }
        $salida->delete();
        return redirect()->route('salidas.index')->with('success', 'Salida eliminada y stock restaurado.');
    }

    public function downloadPDF($id, $copia = null)
    {
        $salida = SalidaInventario::with(['proyecto', 'entregadoPor', 'entregadoA'])->findOrFail($id);
        $productos = json_decode($salida->productos, true);

        // Obtener detalles de productos
        foreach ($productos as &$item) {
            $inv = Inventario::find($item['inventario_id']);
            $item['modelo'] = $inv->modelo ?? 'N/A';
            $item['descripcion'] = $inv->descripcion ?? 'N/A';
        }

        $copias = ['administracion' => 'Administración', 'cliente' => 'Cliente', 'instalador' => 'Instalador'];
        $tipoCopia = $copia ? ($copias[$copia] ?? 'General') : 'General';

        $pdf = Pdf::loadView('pdf.salida_inventario', compact('salida', 'productos', 'tipoCopia'));
        return $pdf->download("salida_{$salida->id}_{$tipoCopia}.pdf");
    }
}
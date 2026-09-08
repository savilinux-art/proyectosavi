<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalidaInventario;
use App\Models\SalidaDetalle;
use App\Models\Inventario;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SalidaInventarioController extends Controller
{
    /**
     * Lista todas las salidas con sus relaciones.
     */
    public function index()
    {
        $salidas = SalidaInventario::with(['proyecto', 'entregadoPor', 'entregadoA', 'detalles.inventario'])->get();
        return view('salidas.index', compact('salidas'));
    }

    /**
     * Muestra el formulario para crear una nueva salida.
     */
    public function create()
    {
        $proyectos = Venta::where('venta_ganada', true)->get();
        $usuarios = Usuario::all();
        $productos = Inventario::where('existencia', '>', 0)->get();
        return view('salidas.create', compact('proyectos', 'usuarios', 'productos'));
    }

    /**
     * Busca productos con stock positivo para Select2.
     */
    public function buscarProductos(Request $request)
    {
        $search = $request->get('q');

        if (empty($search) || strlen($search) < 2) {
            return response()->json([]);
        }

        $productos = Inventario::where('existencia', '>', 0)
            ->where(function ($query) use ($search) {
                $query->where('modelo', 'LIKE', "%{$search}%")
                      ->orWhere('descripcion', 'LIKE', "%{$search}%")
                      ->orWhere('marca', 'LIKE', "%{$search}%")
                      ->orWhere('codigo_origen', 'LIKE', "%{$search}%");
            })
            ->orderBy('modelo')
            ->limit(20)
            ->get();

        return response()->json($productos);
    }

    /**
     * Almacena una nueva salida en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto',
            'entregado_a'     => 'required|exists:usuarios,usuario',
            'productos'       => 'required|array|min:1',
            'productos.*.inventario_id' => 'required|exists:inventario,id',
            'productos.*.cantidad'      => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear el encabezado de la salida (sin JSON)
            $data = $request->only(['nombre_proyecto', 'entregado_a', 'observaciones']);
            $data['entregado_por'] = Session::get('user_usuario');
            $data['fecha_hora_salida'] = now();

            $salida = SalidaInventario::create($data);

            // 2. Procesar cada producto: descontar stock y guardar detalle
            foreach ($request->productos as $item) {
                $producto = Inventario::find($item['inventario_id']);
                if ($producto->existencia < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->modelo} (ID: {$producto->id})");
                }

                // Descontar stock
                $producto->existencia -= $item['cantidad'];
                $producto->save();

                // Guardar detalle
                SalidaDetalle::create([
                    'salida_id'       => $salida->id,
                    'inventario_id'   => $item['inventario_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'] ?? null,
                    'observaciones'   => $item['observaciones'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('salidas.index')
                ->with('success', 'Salida registrada correctamente. Puedes descargar el PDF.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la salida: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de una salida específica.
     */
    public function show($id)
    {
        $salida = SalidaInventario::with(['proyecto', 'entregadoPor', 'entregadoA', 'detalles.inventario'])
                    ->findOrFail($id);

        // Los productos ya vienen en la relación `detalles`, no es necesario decodificar JSON
        $productos = $salida->detalles; // Colección de SalidaDetalle con su inventario

        return view('salidas.show', compact('salida', 'productos'));
    }

    /**
     * Genera y descarga el PDF de la salida.
     */
    public function downloadPDF($id, $copia = null)
    {
        $salida = SalidaInventario::with(['proyecto', 'entregadoPor', 'entregadoA', 'detalles.inventario'])
                    ->findOrFail($id);

        $productos = $salida->detalles; // Ya tiene los datos del inventario

        $copias = ['administracion' => 'Administración', 'cliente' => 'Cliente', 'instalador' => 'Instalador'];
        $tipoCopia = $copia ? ($copias[$copia] ?? 'General') : 'General';

        $pdf = Pdf::loadView('pdf.salida_inventario', compact('salida', 'productos', 'tipoCopia'));
        return $pdf->download("salida_{$salida->id}_{$tipoCopia}.pdf");
    }

    // Opcional: método para eliminar (con cascada definida en la BD)
    public function destroy($id)
    {
        $salida = SalidaInventario::findOrFail($id);
        // Los detalles se eliminan automáticamente si la FK tiene ON DELETE CASCADE
        $salida->delete();

        return redirect()->route('salidas.index')
            ->with('success', 'Salida eliminada correctamente.');
    }
}
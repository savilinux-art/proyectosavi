<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DevolucionInventario;
use App\Models\DevolucionDetalle;
use App\Models\Inventario;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DevolucionInventarioController extends Controller
{
    /**
     * Lista todas las devoluciones con sus relaciones.
     */
    public function index()
    {
        $devoluciones = DevolucionInventario::with(['proyecto', 'devueltoPor', 'recibidoPor', 'detalles.inventario'])->get();
        return view('devoluciones.index', compact('devoluciones'));
    }

    /**
     * Muestra el formulario para crear una nueva devolución.
     */
    public function create()
    {
        $proyectos = Venta::where('venta_ganada', true)->get();
        $usuarios = Usuario::all();
        $productos = Inventario::all(); // Se pueden devolver productos sin stock (para devolver lo que ya se llevaron)
        return view('devoluciones.create', compact('proyectos', 'usuarios', 'productos'));
    }

    /**
     * Busca productos en el inventario (sin filtrar por stock > 0,
     * porque se pueden devolver productos que ya no tienen existencia).
     */
    public function buscarProductos(Request $request)
    {
        $q = $request->get('q');

        if (empty($q) || strlen($q) < 2) {
            return response()->json([]);
        }

        $productos = Inventario::where(function ($query) use ($q) {
            $query->where('modelo', 'LIKE', "%{$q}%")
                  ->orWhere('descripcion', 'LIKE', "%{$q}%")
                  ->orWhere('marca', 'LIKE', "%{$q}%")
                  ->orWhere('codigo_origen', 'LIKE', "%{$q}%");
        })
        ->orderBy('modelo')
        ->limit(20)
        ->get();

        return response()->json($productos);
    }

    /**
     * Almacena una nueva devolución en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto' => 'nullable|exists:ventas,nombre_proyecto',
            'recibido_por'    => 'required|exists:usuarios,usuario',
            'productos'       => 'required|array|min:1',
            'productos.*.inventario_id' => 'required|exists:inventario,id',
            'productos.*.cantidad'      => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear el encabezado de la devolución (sin JSON)
            $data = $request->only(['nombre_proyecto', 'recibido_por', 'observaciones']);
            $data['devuelto_por'] = Session::get('user_usuario');
            $data['fecha_hora_devolucion'] = now();

            $devolucion = DevolucionInventario::create($data);

            // 2. Procesar cada producto: aumentar stock y guardar detalle
            foreach ($request->productos as $item) {
                $producto = Inventario::find($item['inventario_id']);
                $producto->existencia += $item['cantidad'];
                $producto->save();

                // Guardar detalle
                DevolucionDetalle::create([
                    'devolucion_id'   => $devolucion->id,
                    'inventario_id'   => $item['inventario_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'] ?? null,
                    'observaciones'   => $item['observaciones'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('devoluciones.index')
                ->with('success', 'Devolución registrada y stock actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de una devolución específica.
     */
    public function show($id)
    {
        $devolucion = DevolucionInventario::with(['proyecto', 'devueltoPor', 'recibidoPor', 'detalles.inventario'])
                        ->findOrFail($id);

        $productos = $devolucion->detalles; // Colección de DevolucionDetalle con su inventario

        return view('devoluciones.show', compact('devolucion', 'productos'));
    }

    // Nota: No se permite editar ni eliminar devoluciones (solo guardar)
}
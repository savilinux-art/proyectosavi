<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DevolucionInventario;
use App\Models\Inventario;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DevolucionInventarioController extends Controller
{
    public function index()
    {
        $devoluciones = DevolucionInventario::with(['proyecto', 'devueltoPor', 'recibidoPor'])->get();
        return view('devoluciones.index', compact('devoluciones'));
    }

    public function create()
    {
        $proyectos = Venta::where('venta_ganada', true)->get();
        $usuarios = Usuario::all();
        $productos = Inventario::all();
        return view('devoluciones.create', compact('proyectos', 'usuarios', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recibido_por' => 'required|exists:usuarios,usuario',
            'productos' => 'required|array|min:1',
            'productos.*.inventario_id' => 'required|exists:inventario,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['devuelto_por'] = Session::get('user_usuario');
            $data['fecha_hora_devolucion'] = now();
            $data['productos'] = json_encode($request->productos);

            // Aumentar stock
            foreach ($request->productos as $item) {
                $producto = Inventario::find($item['inventario_id']);
                $producto->existencia += $item['cantidad'];
                $producto->save();
            }

            DevolucionInventario::create($data);
            DB::commit();

            return redirect()->route('devoluciones.index')
                ->with('success', 'Devolución registrada y stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $devolucion = DevolucionInventario::with(['proyecto', 'devueltoPor', 'recibidoPor'])->findOrFail($id);
        return view('devoluciones.show', compact('devolucion'));
    }

    // No se permite editar ni eliminar devoluciones (solo guardar)
}
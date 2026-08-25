<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('vendedor')->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $vendedores = Usuario::whereIn('rol', ['Ventas', 'Administrador'])->get();
        return view('ventas.create', compact('vendedores'));
    }

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

    // show, edit, update, destroy, export (similares a versiones anteriores)
}
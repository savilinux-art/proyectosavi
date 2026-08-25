<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Inventario;
use App\Models\Instalacion;
use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{

 public function index()
{
    $reportes = [
        'ventas' => [
            'total' => Venta::count(),
            'ganadas' => Venta::where('venta_ganada', true)->count(),
            'perdidas' => Venta::where('venta_ganada', false)->count(),
            'monto_total' => Venta::sum('monto_venta'),
            'por_estatus' => Venta::select('estatus', DB::raw('count(*) as total'))->groupBy('estatus')->get(),
        ],
        'inventario' => [
            'total_productos' => Inventario::count(),
            'total_existencia' => Inventario::sum('existencia'),
            'bajo_inventario' => Inventario::where('existencia', '<', 10)->count(),
            'sin_stock' => Inventario::where('existencia', 0)->count(),
            'por_categoria' => Inventario::select('categoria', DB::raw('count(*) as total, sum(existencia) as existencia'))->groupBy('categoria')->get(),
        ],
        'instalaciones' => [
            'total' => Instalacion::count(),
            'completadas' => Instalacion::where('estatus_instalacion', 'entrega')->count(),
            'en_proceso' => Instalacion::where('estatus_instalacion', '!=', 'entrega')->count(),
        ],
        'clientes' => [
            'total' => Cliente::count(),
        ],
        'proyectos' => [
            'total' => Proyecto::count(),
        ],
    ];

    return view('reportes.index', compact('reportes'));
}
}
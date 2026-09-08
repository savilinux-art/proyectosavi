<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Cliente;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with(['cliente', 'creador'])->orderBy('id', 'desc')->get();
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $proyectos = Proyecto::all();  // ← Agregar esta línea
        $productos = Inventario::all();
        return view('cotizaciones.create', compact('clientes', 'proyectos', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'proyecto_id' => 'required|exists:proyectos,id',
            'fecha_emision' => 'required|date',
            'fecha_validez' => 'nullable|date|after_or_equal:fecha_emision',
            'moneda' => 'required|in:MXN,USD,EUR',
            'condiciones' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.descripcion' => 'required|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Crear cotización
            $cotizacion = Cotizacion::create([
                'folio' => Cotizacion::generarFolio(),
                'cliente_id' => $request->cliente_id,
                'proyecto_id' => $request->proyecto_id,
                'fecha_emision' => $request->fecha_emision,
                'fecha_validez' => $request->fecha_validez,
                'moneda' => $request->moneda,
                'condiciones' => $request->condiciones,
                'estatus' => 'borrador',
                'creado_por' => Session::get('user_usuario'),
            ]);

            // Guardar detalles
            $subtotal = 0;
            foreach ($request->productos as $item) {
                $importe = $item['cantidad'] * $item['precio_unitario'];
                $subtotal += $importe;
                CotizacionDetalle::create([
                    'cotizacion_id' => $cotizacion->id,
                    'inventario_id' => $item['inventario_id'] ?? null,
                    'descripcion' => $item['descripcion'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'importe' => $importe,
                ]);
            }

            // Actualizar totales
            $cotizacion->subtotal = $subtotal;
            $cotizacion->iva = $subtotal * 0.16;
            $cotizacion->total = $subtotal + ($subtotal * 0.16);
            $cotizacion->save();

            DB::commit();
            return redirect()->route('cotizaciones.show', $cotizacion)
                ->with('success', 'Cotización creada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente', 'creador', 'detalles.inventario']);
        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function edit(Cotizacion $cotizacion)
    {
        $clientes = Cliente::all();
        $productos = Inventario::all();
        $cotizacion->load('detalles');
        return view('cotizaciones.edit', compact('cotizacion', 'clientes', 'productos'));
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        // Similar a store pero actualizando
        // ... validación y actualización
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada.');
    }

    public function pdf(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente', 'detalles.inventario']);
        $pdf = Pdf::loadView('pdf.cotizacion', compact('cotizacion'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);
        return $pdf->download("cotizacion_{$cotizacion->folio}.pdf");
    }

    // API para buscar productos (para el buscador en el formulario)
    public function buscarProductos(Request $request)
    {
        $search = $request->get('q');
        if (strlen($search) < 2) return response()->json([]);

        $productos = Inventario::where('existencia', '>', 0)
            ->where(function ($query) use ($search) {
                $query->where('modelo', 'LIKE', "%{$search}%")
                      ->orWhere('descripcion', 'LIKE', "%{$search}%")
                      ->orWhere('marca', 'LIKE', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($productos);
    }
}
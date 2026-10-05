<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Proyecto;
use App\Models\VentaMostrador;
use App\Models\VentaMostradorDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaMostradorController extends Controller
{
    public function index(Request $request)
    {
        $query = VentaMostrador::with(['proyecto', 'creadoPor'])
            ->orderBy('id', 'desc');

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }
        if ($proyectoId = $request->input('proyecto_id')) {
            $query->where('proyecto_id', $proyectoId);
        }

        $ventas = $query->get();
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        return view('ventas_mostrador.index', compact('ventas', 'proyectos'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
        $inventario = Inventario::where('existencia', '>', 0)
            ->orderBy('modelo')
            ->get();

        return view('ventas_mostrador.create', compact('proyectos', 'inventario'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyecto_id'    => 'nullable|exists:proyectos,id|required_without:proyecto_nuevo',
            'proyecto_nuevo' => 'nullable|string|max:255|required_without:proyecto_id',
            'observaciones'  => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.inventario_id'    => 'required|exists:inventario,id',
            'items.*.cantidad'         => 'required|integer|min:1',
            'items.*.precio_unitario'  => 'required|numeric|min:0',
            'items.*.descuento'        => 'nullable|numeric|min:0',
        ]);

        $creadoPor = Session::get('user_usuario');
        if (!$creadoPor) {
            return back()->with('error', 'No hay sesión activa.')->withInput();
        }

        DB::beginTransaction();
        try {
            $proyectoId = $request->proyecto_id;
            if (!$proyectoId && $request->filled('proyecto_nuevo')) {
                $proyecto = Proyecto::create([
                    'nombre_proyecto' => $request->proyecto_nuevo,
                    'modificado_por'  => $creadoPor,
                ]);
                $proyectoId = $proyecto->id;
            }

            $venta = VentaMostrador::create([
                'proyecto_id'    => $proyectoId,
                'estado'         => 'pendiente',
                'total'          => 0,
                'observaciones'  => $request->observaciones,
                'creado_por'     => $creadoPor,
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $descuento = $item['descuento'] ?? 0;
                $subtotal  = $item['cantidad'] * ($item['precio_unitario'] - $descuento);
                $total    += $subtotal;

                VentaMostradorDetalle::create([
                    'venta_mostrador_id' => $venta->id,
                    'inventario_id'      => $item['inventario_id'],
                    'cantidad'           => $item['cantidad'],
                    'precio_unitario'    => $item['precio_unitario'],
                    'descuento'          => $descuento,
                    'subtotal'           => $subtotal,
                ]);
            }

            $venta->update(['total' => $total]);

            DB::commit();
            return redirect()->route('ventas_mostrador.show', $venta)
                ->with('success', 'Venta de mostrador creada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }

   public function show(VentaMostrador $ventasMostrador)
{
    $ventasMostrador->load(['proyecto', 'detalles.inventario', 'creadoPor', 'salidas']);
    return view('ventas_mostrador.show', [
        'ventaMostrador' => $ventasMostrador,
    ]);
}

    public function edit(VentaMostrador $ventasMostrador)
{
    if (!$ventasMostrador->puedeEditarse()) {
        return redirect()->route('ventas_mostrador.show', $ventasMostrador)
            ->with('error', 'Esta venta no puede editarse en su estado actual.');
    }

    $ventasMostrador->load('detalles');
    $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
    $inventario = Inventario::where('existencia', '>', 0)
        ->orderBy('modelo')
        ->get();

    return view('ventas_mostrador.edit', [
        'ventaMostrador' => $ventasMostrador,
        'proyectos'      => $proyectos,
        'inventario'     => $inventario,
    ]);
}

    public function update(Request $request, VentaMostrador $ventasMostrador)
    {
        if (!$ventasMostrador->puedeEditarse()) {
            return redirect()->route('ventas_mostrador.show', $ventasMostrador)
                ->with('error', 'Esta venta no puede editarse.');
        }

        $request->validate([
            'proyecto_id'    => 'required|exists:proyectos,id',
            'observaciones'  => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.inventario_id'    => 'required|exists:inventario,id',
            'items.*.cantidad'         => 'required|integer|min:1',
            'items.*.precio_unitario'  => 'required|numeric|min:0',
            'items.*.descuento'        => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $ventasMostrador->update([
                'proyecto_id'     => $request->proyecto_id,
                'observaciones'   => $request->observaciones,
                'modificado_por'  => Session::get('user_usuario'),
            ]);

            $ventasMostrador->detalles()->delete();

            $total = 0;
            foreach ($request->items as $item) {
                $descuento = $item['descuento'] ?? 0;
                $subtotal  = $item['cantidad'] * ($item['precio_unitario'] - $descuento);
                $total    += $subtotal;

                VentaMostradorDetalle::create([
                    'venta_mostrador_id' => $ventasMostrador->id,
                    'inventario_id'      => $item['inventario_id'],
                    'cantidad'           => $item['cantidad'],
                    'precio_unitario'    => $item['precio_unitario'],
                    'descuento'          => $descuento,
                    'subtotal'           => $subtotal,
                ]);
            }

            $ventasMostrador->update(['total' => $total]);

            DB::commit();
            return redirect()->route('ventas_mostrador.show', $ventasMostrador)
                ->with('success', 'Venta actualizada.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }

    public function cancelar(VentaMostrador $ventasMostrador)
    {
        if ($ventasMostrador->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden cancelar ventas pendientes.');
        }

        $ventasMostrador->update(['estado' => 'cancelada']);
        return redirect()->route('ventas_mostrador.show', $ventasMostrador)
            ->with('success', 'Venta cancelada.');
    }

    public function cambiarEstado(Request $request, VentaMostrador $ventasMostrador)
    {
    $request->validate([
        'estado' => 'required|in:' . implode(',', VentaMostrador::ESTADOS),
    ]);

    $nuevo = $request->estado;

    if (!$ventasMostrador->puedeCambiarEstadoA($nuevo)) {
        return back()->with('error',
            "No se puede cambiar el estado de '{$ventasMostrador->estado}' a '{$nuevo}'.");
    }

    $ventasMostrador->update([
        'estado'         => $nuevo,
        'modificado_por' => Session::get('user_usuario'),
    ]);

    return redirect()->route('ventas_mostrador.show', $ventasMostrador)
        ->with('success', 'Estado cambiado a ' . ucfirst($nuevo) . '.');
    }

    public function pdf(VentaMostrador $ventasMostrador)
{
    $ventasMostrador->load(['proyecto', 'detalles.inventario', 'creadoPor']);

    $logoPath = public_path('images/logo.png');
    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

    $pdf = Pdf::loadView('pdf.venta_mostrador', compact('ventasMostrador', 'logoBase64'));
    $pdf->setPaper('letter', 'portrait');
    $pdf->setOptions([
        'defaultFont'         => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled'      => true,
    ]);

    $folio = 'VM-' . str_pad($ventasMostrador->id, 4, '0', STR_PAD_LEFT);
    return $pdf->download("venta_mostrador_{$folio}.pdf");
}

}
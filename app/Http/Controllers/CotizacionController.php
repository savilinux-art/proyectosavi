<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Proyecto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class CotizacionController extends Controller
{
    // funciones para manejar las cotizaciones
       public function index(Request $request)
    {
         $query = Cotizacion::with(['creador', 'proyecto.cliente'])
            ->orderBy('id', 'desc');

        $proyectoFiltro = $request->input('proyecto');

        if ($proyectoFiltro) {
            $query->whereHas('proyecto', function ($q) use ($proyectoFiltro) {
                $q->where('nombre_proyecto', $proyectoFiltro);
            });
        }

        $cotizaciones = $query->get();

        return view('cotizaciones.index', compact('cotizaciones', 'proyectoFiltro'));
    }

    // ============== Mostrar formulario para crear una nueva cotización ========================
    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
$productos = Inventario::all();
return view('cotizaciones.create', compact('proyectos', 'productos'));
    }

    // =========================== Guardar nueva cotización ======================================
    public function store(Request $request)
{
    // Validación: quitar cliente_id, agregar proyecto_modo/proyecto_nuevo
$request->validate([
    'proyecto_id'    => 'nullable|exists:proyectos,id|required_without:proyecto_nuevo',
    'proyecto_nuevo' => 'nullable|string|max:255|required_without:proyecto_id',
    'proyecto_modo'  => 'required|in:existente,nuevo',   // opcional, para coherencia
    'fecha_emision'  => 'required|date',
    'fecha_validez'  => 'nullable|date|after_or_equal:fecha_emision',
    'moneda'         => 'required|in:MXN,USD,EUR',
    'condiciones'    => 'nullable|string',
    'productos'      => 'required|array|min:1',
    'productos.*.descripcion'      => 'required|string',
    'productos.*.cantidad'         => 'required|integer|min:1',
    'productos.*.precio_unitario'  => 'required|numeric|min:0',
    'productos.*.inventario_id'    => 'nullable|exists:inventario,id',
]);
    // Obtener usuario de la sesión o autenticación
    $creadoPor = Session::get('user_usuario');

    // Si no está en sesión, intentar con autenticación
    if (!$creadoPor) {
        $creadoPor = auth()->user()->usuario ?? null;
    }

    if (!$creadoPor) {
        return back()->with('error', 'No hay sesión activa. Inicia sesión nuevamente.')->withInput();
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
        // Crear cotización
        $cotizacion = Cotizacion::create([
            'folio' => Cotizacion::generarFolio(),
            'proyecto_id' => $proyectoId,  // ← dinámico (existente o recién creado)
            'fecha_emision' => $request->fecha_emision,
            'fecha_validez' => $request->fecha_validez,
            'moneda' => $request->moneda,
            'condiciones' => $request->condiciones,
            'estatus' => 'borrador',
            'creado_por' => $creadoPor,
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
        // Registrar el error en el log para depuración
        \Log::error('Error al guardar cotización: ' . $e->getMessage());
        return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
    }
}
    // =========================== Mostrar cotización ======================================
    public function show(Cotizacion $cotizacion)
    {
        $cotizacion->load(['creador', 'detalles.inventario', 'proyecto.cliente']);
        return view('cotizaciones.show', compact('cotizacion'));
    }

    // =========================== Actualizar cotización ======================================
public function update(Request $request, Cotizacion $cotizacion)
{
    // Validación
    $request->validate([
        'proyecto_id'    => 'nullable|exists:proyectos,id|required_without:proyecto_nuevo',
        'proyecto_nuevo' => 'nullable|string|max:255|required_without:proyecto_id',
        'proyecto_modo' => 'nullable|in:existente,nuevo',  // opcional, para coherencia
        'fecha_emision' => 'required|date',
        'fecha_validez' => 'nullable|date|after_or_equal:fecha_emision',
        'moneda' => 'required|in:MXN,USD,EUR',
        'condiciones' => 'nullable|string',
        'productos' => 'required|array|min:1',
        'productos.*.descripcion' => 'required|string',
        'productos.*.cantidad' => 'required|integer|min:1',
        'productos.*.precio_unitario' => 'required|numeric|min:0',
        'productos.*.inventario_id' => 'nullable|exists:inventario,id',
    ]);

    DB::beginTransaction();
    try {
        // 1. Actualizar datos principales
        $cotizacion->update($request->only([
            'proyecto_id', 'fecha_emision', 
            'fecha_validez', 'moneda', 'condiciones'
        ]));

        // 2. Eliminar detalles antiguos
        $cotizacion->detalles()->delete();

        // 3. Crear nuevos detalles
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

        // 4. Recalcular totales
        $cotizacion->subtotal = $subtotal;
        $cotizacion->iva = $subtotal * 0.16;
        $cotizacion->total = $subtotal + ($subtotal * 0.16);
        $cotizacion->save();

        DB::commit();
        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización actualizada correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
    }
}

// =========================== Mostrar formulario para editar una cotización ======================================
public function edit(Cotizacion $cotizacion)
    {
    $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
    $productos = Inventario::all();
    $cotizacion->load(['detalles.inventario']);
    return view('cotizaciones.edit', compact('cotizacion','proyectos', 'productos'));
    }

    /**
     * Vista de la lista de materiales para el almacenista.
     * Sin precios — se filtran server-side.
     */
    public function vistaAlmacen(Cotizacion $cotizacion)
    {
        $cotizacion->load(['detalles.inventario', 'proyecto.cliente']);
        return view('cotizaciones.almacen', compact('cotizacion'));
    }

// =========================== Eliminar cotización ======================================
    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada.');
    }

   public function pdf(Cotizacion $cotizacion)
    {
    $cotizacion->load(['cliente', 'detalles.inventario']);
    
    // Obtener la imagen en base64
    $logoPath = public_path('images/logo.png');
    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    
    $pdf = Pdf::loadView('pdf.cotizacion', compact('cotizacion', 'logoBase64'));
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true, // ← Importante para imágenes externas
    ]);
    return $pdf->download("cotizacion_{$cotizacion->folio}.pdf");
    }
public function buscarProductos(Request $request)
{
    try {
        $search = $request->get('q');
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $productos = Inventario::where('existencia', '>', 0)
            ->where(function ($query) use ($search) {
                $query->where('modelo', 'LIKE', "%{$search}%")
                      ->orWhere('descripcion', 'LIKE', "%{$search}%")
                      ->orWhere('marca', 'LIKE', "%{$search}%");
            })
            ->limit(20)
            ->get(['id', 'modelo', 'descripcion', 'marca', 'existencia', 'precio']);

        return response()->json($productos);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error en el servidor',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
}
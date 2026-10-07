<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Services\TrazabilidadService;
use App\Models\SalidaInventario;
use App\Models\DevolucionInventario;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rol = Session::get('user_rol');
        $user_usuario = Session::get('user_usuario');

        if ($rol == 'Instalador') {
            // 🔥 CORREGIDO: Usar la relación muchos a muchos a través de instaladores
            $proyectos = Proyecto::whereHas('instalaciones', function($q) use ($user_usuario) {
                $q->whereHas('instaladores', function($sub) use ($user_usuario) {
                    $sub->where('instalador_usuario', $user_usuario);
                });
          })->with(['modificadoPor'])->get();
         } else {
            $proyectos = Proyecto::with(['modificadoPor'])->get();
        }

        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proyectos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto' => 'required|string|max:255|unique:proyectos,nombre_proyecto',
            'correo_electronico' => 'nullable|email|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'credenciales' => 'nullable|string',
            'propuesta_economica' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'archivo_as_built' => 'nullable|file|mimes:pdf,dwg|max:5120',
            'salida_inventario' => 'nullable|file|mimes:pdf|max:5120',
            'devolucion_inventario' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['modificado_por'] = Session::get('user_usuario');

            if ($request->hasFile('propuesta_economica')) {
                $data['propuesta_economica'] = file_get_contents($request->file('propuesta_economica')->getRealPath());
            }

            if ($request->hasFile('archivo_as_built')) {
                $data['archivo_as_built'] = file_get_contents($request->file('archivo_as_built')->getRealPath());
            }

            if ($request->hasFile('salida_inventario')) {
                $data['salida_inventario'] = file_get_contents($request->file('salida_inventario')->getRealPath());
            }

            if ($request->hasFile('devolucion_inventario')) {
                $data['devolucion_inventario'] = file_get_contents($request->file('devolucion_inventario')->getRealPath());
            }

            Proyecto::create($data);

            DB::commit();

            return redirect()->route('proyectos.index')
                ->with('success', 'Proyecto creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
        public function show($id)
    {
        $proyecto = Proyecto::with(['modificadoPor'])->findOrFail($id);

        $materiales = app(TrazabilidadService::class)->paraProyecto($proyecto);

        $salidas = SalidaInventario::with('detalles.inventario')
            ->where('nombre_proyecto', $proyecto->nombre_proyecto)
            ->orderByDesc('fecha_hora_salida')
            ->get();

        $devoluciones = DevolucionInventario::with('detalles.inventario')
            ->where('nombre_proyecto', $proyecto->nombre_proyecto)
            ->orderByDesc('fecha_hora_devolucion')
            ->get();

        return view('proyectos.show', compact(
            'proyecto', 'materiales', 'salidas', 'devoluciones'
        ));
    }

    public function salidaPdf($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        if (!$proyecto->salida_inventario) {
            abort(404);
        }

        return response($proyecto->salida_inventario)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="salida-inventario-' . $proyecto->id . '.pdf"');
    }

    public function devolucionPdf($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        if (!$proyecto->devolucion_inventario) {
            abort(404);
        }

        return response($proyecto->devolucion_inventario)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="devolucion-inventario-' . $proyecto->id . '.pdf"');
    }

        public function propuestaPdf($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        if (!$proyecto->propuesta_economica) {
            abort(404);
        }

        return response($proyecto->propuesta_economica)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="propuesta-' . $proyecto->id . '.pdf"');
    }

    public function asBuilt($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        if (!$proyecto->archivo_as_built) {
            abort(404);
        }

        // As-Built puede ser PDF o DWG (validación: mimes:pdf,dwg)
        return response($proyecto->archivo_as_built)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="as-built-' . $proyecto->id . '"');
    }
    /**
     * Show the form for editing the specified resource.
     */
    
    public function edit($id)
    {
    $proyecto = Proyecto::findOrFail($id);
    return view('proyectos.edit', compact('proyecto'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_proyecto'       => 'required|string|max:255|unique:proyectos,nombre_proyecto,' . $id,
            'correo_electronico'    => 'nullable|email|max:255',
            'ubicacion'             => 'nullable|string|max:255',
            'credenciales'          => 'nullable|string',
            'propuesta_economica'   => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'archivo_as_built'      => 'nullable|file|mimes:pdf,dwg|max:5120',
            'salida_inventario'     => 'nullable|file|mimes:pdf|max:5120',
            'devolucion_inventario' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $proyecto = Proyecto::findOrFail($id);
            $data = $request->all();
            $data['modificado_por'] = Session::get('user_usuario');

            if ($request->hasFile('propuesta_economica')) {
                $data['propuesta_economica'] = file_get_contents($request->file('propuesta_economica')->getRealPath());
            }

            if ($request->hasFile('archivo_as_built')) {
                $data['archivo_as_built'] = file_get_contents($request->file('archivo_as_built')->getRealPath());
            }

            if ($request->hasFile('salida_inventario')) {
                $data['salida_inventario'] = file_get_contents($request->file('salida_inventario')->getRealPath());
            }

            if ($request->hasFile('devolucion_inventario')) {
                $data['devolucion_inventario'] = file_get_contents($request->file('devolucion_inventario')->getRealPath());
            }

            $proyecto->update($data);

            DB::commit();

            return redirect()->route('proyectos.index')
                ->with('success', 'Proyecto actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente');
    }
}
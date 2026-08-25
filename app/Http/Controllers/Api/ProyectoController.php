<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProyectoController extends Controller
{
    /**
     * Listar todos los proyectos
     * GET /api/proyectos
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Proyecto::with(['venta', 'modificadoPor']);

        // Filtrar por rol
        if ($user->rol == 'Instalador') {
            $query->whereHas('venta', function($q) use ($user) {
                $q->whereHas('instalaciones', function($sub) use ($user) {
                    $sub->where('id_usuario_asignado', $user->usuario);
                });
            });
        }

        // Filtros opcionales
        if ($request->has('estatus')) {
            $query->whereHas('venta', function($q) use ($request) {
                $q->where('estatus', $request->estatus);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre_proyecto', 'LIKE', "%{$search}%")
                  ->orWhere('correo_electronico', 'LIKE', "%{$search}%")
                  ->orWhere('credenciales', 'LIKE', "%{$search}%");
            });
        }

        $proyectos = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $proyectos,
            'total' => $proyectos->count()
        ]);
    }

    /**
     * Mostrar un proyecto específico
     * GET /api/proyectos/{id}
     */
    public function show($id)
    {
        $proyecto = Proyecto::with(['venta', 'modificadoPor'])->find($id);
        
        if (!$proyecto) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }

    /**
     * Crear un nuevo proyecto
     * POST /api/proyectos
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto|unique:proyectos',
            'correo_electronico' => 'required|email|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'credenciales' => 'nullable|string',
            'propuesta_economica' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'archivo_as_built' => 'nullable|file|mimes:pdf,dwg|max:5120',
            'salida_inventario' => 'nullable|file|mimes:pdf|max:5120',
            'devolucion_inventario' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['modificado_por'] = $request->user()->usuario;

            // Procesar archivos
            $files = ['propuesta_economica', 'archivo_as_built', 'salida_inventario', 'devolucion_inventario'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $data[$file] = file_get_contents($request->file($file)->getRealPath());
                }
            }

            $proyecto = Proyecto::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proyecto creado exitosamente',
                'data' => $proyecto
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el proyecto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un proyecto
     * PUT /api/proyectos/{id}
     */
    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::find($id);
        
        if (!$proyecto) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre_proyecto' => 'sometimes|exists:ventas,nombre_proyecto|unique:proyectos,nombre_proyecto,' . $id,
            'correo_electronico' => 'sometimes|email|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'credenciales' => 'nullable|string',
            'propuesta_economica' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'archivo_as_built' => 'nullable|file|mimes:pdf,dwg|max:5120',
            'salida_inventario' => 'nullable|file|mimes:pdf|max:5120',
            'devolucion_inventario' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['modificado_por'] = $request->user()->usuario;

            // Procesar archivos (solo si se suben nuevos)
            $files = ['propuesta_economica', 'archivo_as_built', 'salida_inventario', 'devolucion_inventario'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $data[$file] = file_get_contents($request->file($file)->getRealPath());
                }
            }

            $proyecto->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proyecto actualizado exitosamente',
                'data' => $proyecto
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el proyecto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un proyecto
     * DELETE /api/proyectos/{id}
     */
    public function destroy($id)
    {
        $proyecto = Proyecto::find($id);
        
        if (!$proyecto) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $proyecto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado exitosamente'
        ]);
    }

    /**
     * Buscar proyectos
     * GET /api/proyectos/buscar?q=texto
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro de búsqueda requerido'
            ], 422);
        }

        $proyectos = Proyecto::where('nombre_proyecto', 'LIKE', "%{$query}%")
            ->orWhere('correo_electronico', 'LIKE', "%{$query}%")
            ->orWhere('credenciales', 'LIKE', "%{$query}%")
            ->with(['venta', 'modificadoPor'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $proyectos,
            'total' => $proyectos->count()
        ]);
    }

    /**
     * Obtener proyectos por estatus
     * GET /api/proyectos/estatus/{estatus}
     */
    public function byStatus($estatus)
    {
        $proyectos = Proyecto::whereHas('venta', function($q) use ($estatus) {
            $q->where('estatus', $estatus);
        })->with(['venta', 'modificadoPor'])->get();

        return response()->json([
            'success' => true,
            'data' => $proyectos,
            'total' => $proyectos->count()
        ]);
    }

    /**
     * Obtener resumen de proyectos
     * GET /api/proyectos/resumen
     */
    public function resumen()
    {
        $total = Proyecto::count();
        $porEstatus = Proyecto::select('ventas.estatus', DB::raw('count(*) as total'))
            ->join('ventas', 'proyectos.nombre_proyecto', '=', 'ventas.nombre_proyecto')
            ->groupBy('ventas.estatus')
            ->get();

        $conDocumentos = Proyecto::whereNotNull('propuesta_economica')->count();
        $sinDocumentos = Proyecto::whereNull('propuesta_economica')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'con_documentos' => $conDocumentos,
                'sin_documentos' => $sinDocumentos,
                'por_estatus' => $porEstatus
            ]
        ]);
    }

    /**
     * Descargar archivo del proyecto
     * GET /api/proyectos/{id}/archivo/{tipo}
     */
    public function downloadFile($id, $tipo)
    {
        $proyecto = Proyecto::find($id);
        
        if (!$proyecto) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $campos = [
            'propuesta_economica' => 'Propuesta_Economica',
            'archivo_as_built' => 'As_Built',
            'salida_inventario' => 'Salida_Inventario',
            'devolucion_inventario' => 'Devolucion_Inventario'
        ];

        if (!isset($campos[$tipo])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de archivo no válido'
            ], 422);
        }

        $campo = $tipo;
        $nombreArchivo = $campos[$tipo];

        if (!$proyecto->$campo) {
            return response()->json([
                'success' => false,
                'message' => "El archivo {$nombreArchivo} no está disponible"
            ], 404);
        }

        $contenido = $proyecto->$campo;
        $filename = "{$nombreArchivo}_{$proyecto->nombre_proyecto}.pdf";

        return response($contenido)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($contenido));
    }

    /**
     * Obtener proyectos recientes
     * GET /api/proyectos/recientes?limit=10
     */
    public function recientes(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $proyectos = Proyecto::with(['venta', 'modificadoPor'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $proyectos,
            'total' => $proyectos->count()
        ]);
    }

    /**
     * Verificar si un proyecto existe
     * GET /api/proyectos/existe/{nombre}
     */
    public function exists($nombre)
    {
        $existe = Proyecto::where('nombre_proyecto', $nombre)->exists();

        return response()->json([
            'success' => true,
            'exists' => $existe
        ]);
    }
}
<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Listar todos los clientes
     * GET /api/clientes
     */
    public function index()
    {
        $clientes = Cliente::with(['proyecto'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $clientes,
            'total' => $clientes->count()
        ]);
    }

    /**
     * Mostrar un cliente específico
     * GET /api/clientes/{id}
     */
    public function show($id)
    {
        $cliente = Cliente::with(['proyecto'])->find($id);
        
        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cliente
        ]);
    }

    /**
     * Crear un nuevo cliente
     * POST /api/clientes
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfc' => 'required|string|max:13|unique:clientes',
            'razon_social' => 'required|string|max:255',
            'nombre_proyecto' => 'nullable|string|exists:ventas,nombre_proyecto',
            'regimen_fiscal' => 'required|string|max:255',
            'codigo_postal' => 'required|integer|digits:5',
            'correo_electronico' => 'required|email|max:255',
            'constancia_situacion_fiscal' => 'nullable|file|mimes:pdf|max:5120'
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

            if ($request->hasFile('constancia_situacion_fiscal')) {
                $data['constancia_situacion_fiscal'] = file_get_contents($request->file('constancia_situacion_fiscal')->getRealPath());
            }

            $cliente = Cliente::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cliente creado exitosamente',
                'data' => $cliente
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un cliente
     * PUT /api/clientes/{id}
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'rfc' => 'sometimes|string|max:13|unique:clientes,rfc,' . $id,
            'razon_social' => 'sometimes|string|max:255',
            'nombre_proyecto' => 'nullable|string|exists:ventas,nombre_proyecto',
            'regimen_fiscal' => 'sometimes|string|max:255',
            'codigo_postal' => 'sometimes|integer|digits:5',
            'correo_electronico' => 'sometimes|email|max:255',
            'constancia_situacion_fiscal' => 'nullable|file|mimes:pdf|max:5120'
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

            if ($request->hasFile('constancia_situacion_fiscal')) {
                $data['constancia_situacion_fiscal'] = file_get_contents($request->file('constancia_situacion_fiscal')->getRealPath());
            }

            $cliente->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado exitosamente',
                'data' => $cliente
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un cliente
     * DELETE /api/clientes/{id}
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $cliente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado exitosamente'
        ]);
    }

    /**
     * Buscar clientes por RFC o Razón Social
     * GET /api/clientes/buscar?q=texto
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

        $clientes = Cliente::where('rfc', 'LIKE', "%{$query}%")
            ->orWhere('razon_social', 'LIKE', "%{$query}%")
            ->orWhere('correo_electronico', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clientes,
            'total' => $clientes->count()
        ]);
    }

    /**
     * Obtener clientes por régimen fiscal
     * GET /api/clientes/regimen/{regimen}
     */
    public function byRegimen($regimen)
    {
        $clientes = Cliente::where('regimen_fiscal', $regimen)->get();

        return response()->json([
            'success' => true,
            'data' => $clientes,
            'total' => $clientes->count()
        ]);
    }

    /**
     * Obtener resumen de clientes (para dashboard móvil)
     * GET /api/clientes/resumen
     */
    public function resumen()
    {
        $total = Cliente::count();
        $porRegimen = Cliente::select('regimen_fiscal', DB::raw('count(*) as total'))
            ->groupBy('regimen_fiscal')
            ->get();
        $conProyecto = Cliente::whereNotNull('nombre_proyecto')->count();
        $sinProyecto = Cliente::whereNull('nombre_proyecto')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'con_proyecto' => $conProyecto,
                'sin_proyecto' => $sinProyecto,
                'por_regimen' => $porRegimen
            ]
        ]);
    }

    /**
     * Descargar constancia fiscal de un cliente
     * GET /api/clientes/{id}/constancia
     */
    public function downloadConstancia($id)
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        if (!$cliente->constancia_situacion_fiscal) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente no tiene constancia fiscal cargada'
            ], 404);
        }

        $pdfContent = $cliente->constancia_situacion_fiscal;
        $filename = "constancia_{$cliente->rfc}.pdf";

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($pdfContent));
    }
}
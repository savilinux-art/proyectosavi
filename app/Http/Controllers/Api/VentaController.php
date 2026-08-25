<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['vendedor'])->get();
        return response()->json([
            'success' => true,
            'data' => $ventas
        ]);
    }

    public function show($id)
    {
        $venta = Venta::with(['vendedor'])->find($id);
        
        if (!$venta) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $venta
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo_venta' => 'required|string|max:255',
            'nombre_proyecto' => 'required|string|unique:ventas',
            'moneda' => 'required|string|max:10',
            'monto_venta' => 'required|numeric|min:0',
            'requerimiento_venta' => 'required|string',
            'fecha_hora_levantamiento' => 'required|date',
            'venta_ganada' => 'required|boolean',
            'estatus' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['vendedor'] = $request->user()->usuario;

        $venta = Venta::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Venta creada exitosamente',
            'data' => $venta
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $venta = Venta::find($id);
        
        if (!$venta) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo_venta' => 'sometimes|string|max:255',
            'moneda' => 'sometimes|string|max:10',
            'monto_venta' => 'sometimes|numeric|min:0',
            'requerimiento_venta' => 'sometimes|string',
            'fecha_hora_levantamiento' => 'sometimes|date',
            'venta_ganada' => 'sometimes|boolean',
            'estatus' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $venta->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Venta actualizada exitosamente',
            'data' => $venta
        ]);
    }

    public function destroy($id)
    {
        $venta = Venta::find($id);
        
        if (!$venta) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }

        $venta->delete();

        return response()->json([
            'success' => true,
            'message' => 'Venta eliminada exitosamente'
        ]);
    }
}
<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Categoria;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    /**
     * Listar inventario
     */
    public function index(Request $request)
    {
        $query = Inventario::with(['categoriaRelacion', 'modificadoPor']);

        // Filtros
        if ($request->has('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('modelo', 'LIKE', "%{$request->search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$request->search}%")
                  ->orWhere('marca', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->has('bajo_inventario')) {
            $query->where('existencia', '<', 10);
        }

        if ($request->has('sin_stock')) {
            $query->where('existencia', 0);
        }

        $inventario = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $inventario,
            'total' => $inventario->count()
        ]);
    }

    /**
     * Mostrar un producto
     */
    public function show($id)
    {
        $producto = Inventario::with(['categoriaRelacion', 'modificadoPor', 'movimientos'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $producto
        ]);
    }

    /**
     * Crear producto
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modelo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'marca' => 'required|string|max:255',
            'categoria' => 'required|exists:categorias,nombre_categoria',
            'existencia' => 'required|integer|min:0',
            'almacen' => 'required|string|max:255',
            'apea' => 'required|string|max:255',
            'comentarios' => 'nullable|string'
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
            $data['fecha_modificacion'] = now();
            $data['modificado_por'] = $request->user()->usuario;
            $data['apartados'] = 0;
            $data['cantidad_apartados'] = 0;

            if ($request->hasFile('imagen')) {
                $image = $request->file('imagen');
                $data['imagen'] = file_get_contents($image->getRealPath());
            }

            $producto = Inventario::create($data);

            // Registrar movimiento
            MovimientoInventario::create([
                'inventario_id' => $producto->id,
                'entrada' => $request->existencia,
                'modificado_por' => $request->user()->usuario
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'modelo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'marca' => 'required|string|max:255',
            'categoria' => 'required|exists:categorias,nombre_categoria',
            'existencia' => 'required|integer|min:0',
            'almacen' => 'required|string|max:255',
            'apea' => 'required|string|max:255',
            'comentarios' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $producto = Inventario::findOrFail($id);
            $data = $request->all();
            $data['fecha_modificacion'] = now();
            $data['modificado_por'] = $request->user()->usuario;

            $existencia_anterior = $producto->existencia;

            if ($request->hasFile('imagen')) {
                $image = $request->file('imagen');
                $data['imagen'] = file_get_contents($image->getRealPath());
            }

            $producto->update($data);

            // Registrar movimiento si cambia la existencia
            if ($request->existencia != $existencia_anterior) {
                $diferencia = $request->existencia - $existencia_anterior;
                MovimientoInventario::create([
                    'inventario_id' => $producto->id,
                    'ajuste' => $diferencia,
                    'modificado_por' => $request->user()->usuario
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        $producto = Inventario::findOrFail($id);
        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente'
        ]);
    }

    /**
     * Obtener categorías
     */
    public function categorias()
    {
        $categorias = Categoria::all();
        return response()->json([
            'success' => true,
            'data' => $categorias
        ]);
    }
}
<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instalacion;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InstalacionController extends Controller
{
    /**
     * Listar instalaciones
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Instalacion::with(['proyecto', 'usuarioAsignado']);

        if ($user->rol == 'Instalador') {
            $query->where('id_usuario_asignado', $user->usuario);
        }

        if ($request->has('estatus')) {
            $query->where('estatus_instalacion', $request->estatus);
        }

        if ($request->has('pendientes')) {
            $query->where('estatus_instalacion', '!=', 'entrega');
        }

        $instalaciones = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $instalaciones,
            'total' => $instalaciones->count()
        ]);
    }

    /**
     * Mostrar instalación
     */
    public function show($id)
    {
        $instalacion = Instalacion::with(['proyecto', 'usuarioAsignado'])->findOrFail($id);
        $instalacion->check_list = json_decode($instalacion->check_list);

        return response()->json([
            'success' => true,
            'data' => $instalacion
        ]);
    }

    /**
     * guardar instalación
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto',
            'nombre_instalacion' => 'required|string|max:255', // ← NUEVO
            'id_usuario_asignado' => 'nullable|exists:usuarios,usuario',
            'fecha_hora_inicio' => 'required|date',
            'estatus_instalacion' => 'required|string'
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
            $data['check_list'] = json_encode($request->check_list ?? []);

            if ($request->hasFile('evidencia_inicio')) {
                $data['evidencia_inicio'] = file_get_contents($request->file('evidencia_inicio')->getRealPath());
            }

            if ($request->hasFile('incidencias')) {
                $data['incidencias'] = file_get_contents($request->file('incidencias')->getRealPath());
            }

            if ($request->hasFile('evidencia_fin')) {
                $data['evidencia_fin'] = file_get_contents($request->file('evidencia_fin')->getRealPath());
            }

            $instalacion = Instalacion::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Instalación creada exitosamente',
                'data' => $instalacion
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear instalación: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Actualizar instalación
 */
public function update(Request $request, $id)
{
    // 1. Validar los datos
    $validated = $request->validate([
        'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto',
        'nombre_instalacion' => 'required|string|max:255',
        'fecha_hora_inicio' => 'required|date',
        'fecha_hora_fin' => 'nullable|date|after:fecha_hora_inicio',
        'estatus_instalacion' => 'required|exists:estatus,estatus',
        'instaladores' => 'nullable|array',
        'instaladores.*' => 'exists:usuarios,id',
    ]);

    // 2. Buscar la instalación por ID (¡AQUÍ SE DEFINE LA VARIABLE!)
    $instalacion = Instalacion::findOrFail($id);

    // 3. Actualizar los campos básicos
    $instalacion->update($validated);

    // 4. Gestionar los instaladores (sincronizar)
    if ($request->has('instaladores') && is_array($request->instaladores)) {
        // Filtrar valores nulos o vacíos
        $instaladorIds = array_filter($request->instaladores, function($val) {
            return !empty($val) && is_numeric($val);
        });

        if (count($instaladorIds) > 0) {
            // Convertir IDs a nombres de usuario (porque la tabla pivote usa 'usuario')
            $instaladorUsuarios = Usuario::whereIn('id', $instaladorIds)->pluck('usuario')->toArray();
            // Sincronizar (reemplaza los existentes)
            $instalacion->instaladores()->sync($instaladorUsuarios);
        } else {
            // Si el array está vacío, eliminar todos
            $instalacion->instaladores()->detach();
        }
    } else {
        // Si no se envió el campo 'instaladores', eliminar todos
        $instalacion->instaladores()->detach();
    }

    // 5. Redirigir con mensaje de éxito (sin notificación de Telegram)
    return redirect()->route('instalaciones.index')->with('success', 'Instalación actualizada correctamente');
}

    /**
     * Eliminar instalación
     */
    public function destroy($id)
    {
        $instalacion = Instalacion::findOrFail($id);
        $instalacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Instalación eliminada exitosamente'
        ]);
    }

    /**
     * Subir evidencia
     */
    public function uploadEvidence(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|in:inicio,fin,incidencia',
            'archivo' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $instalacion = Instalacion::findOrFail($id);
            $archivo = $request->file('archivo');
            $contenido = file_get_contents($archivo->getRealPath());

            switch ($request->tipo) {
                case 'inicio':
                    $instalacion->evidencia_inicio = $contenido;
                    break;
                case 'fin':
                    $instalacion->evidencia_fin = $contenido;
                    break;
                case 'incidencia':
                    $instalacion->incidencias = $contenido;
                    break;
            }

            $instalacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Evidencia subida exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir evidencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar checklist
     */
    public function updateChecklist(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'check_list' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $instalacion = Instalacion::findOrFail($id);
            $instalacion->check_list = json_encode($request->check_list);
            $instalacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Checklist actualizado exitosamente',
                'data' => json_decode($instalacion->check_list)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar checklist: ' . $e->getMessage()
            ], 500);
        }
    }
}
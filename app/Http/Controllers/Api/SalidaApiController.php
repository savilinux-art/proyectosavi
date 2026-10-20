<?php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EvidenciaEntrega;              // ← B-08
use App\Models\SalidaInventario;
use App\Support\ProyectoResolver;             // ← B-08 (FALTANTE)
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;                   // ← B-08
use Illuminate\Validation\ValidationException; // ← B-08

class SalidaApiController extends Controller
{
    /**
     * GET /api/salidas
     * Pull de salidas donde el usuario autenticado es el receptor (entregado_a).
     * entregado_a es string (FK a usuarios.usuario), NO id numérico.
     */
    public function index(Request $request): JsonResponse
    {
        $user    = $request->user();
        $perPage = min((int) $request->input('per_page', 50), 100);

        $query = SalidaInventario::query()
            ->where('entregado_a', $user->usuario);

        if ($request->filled('since')) {
            $query->where('updated_at', '>=', $request->input('since'));
        }

        $salidas = $query->orderByDesc('updated_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $salidas->items(),
            'total'   => $salidas->total(),
            'meta'    => [
                'current_page' => $salidas->currentPage(),
                'last_page'    => $salidas->lastPage(),
                'per_page'     => $salidas->perPage(),
                'total'        => $salidas->total(),
            ],
        ]);
    }

        /**
     * POST /api/salidas/{id}/evidencias-entrega (multipart)
     * B-08: subir firma del cliente / PDF firmado / foto de entrega.
     *
     * - Solo el receptor (entregado_a) puede subir la evidencia.
     * - Resuelve proyecto_id vía ProyectoResolver (aborta 422 si no existe).
     * - Invalida evidencias vigentes del mismo tipo antes de crear la nueva.
     * - Guarda el archivo en storage/app/private/evidencias_entrega/{salida_id}/{uuid}.{ext}.
     */
    public function uploadEvidenciaEntrega(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        // 1. Autorización: 404 si la salida no pertenece al usuario
        $salida = SalidaInventario::where('entregado_a', $user->usuario)->findOrFail($id);

        // 2. Validación (inline, consistente con el resto de la API)
        $validated = $request->validate([
            'archivo' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'], // 10 MB
            'tipo'    => ['nullable', 'in:firma_cliente,pdf_cliente_firmado,foto_entrega'],
            'notas'   => ['nullable', 'string', 'max:2000'],
        ]);

        $tipo    = $validated['tipo'] ?? 'firma_cliente';
        $archivo = $request->file('archivo');
        $mime    = $archivo->getMimeType() ?: 'application/octet-stream';

        // 3. Magic bytes para PDF (tolera hasta 1024 bytes de header garbage del spec PDF)
        $esPdf = $archivo->getClientOriginalExtension() === 'pdf' || $mime === 'application/pdf';
        if ($esPdf) {
            $realPath = $archivo->getRealPath();
            $handle   = $realPath ? fopen($realPath, 'rb') : false;
            $header   = $handle ? (string) fread($handle, 1024) : '';
            if ($handle) {
                fclose($handle);
            }
            if (strpos($header, '%PDF') === false) {
                throw ValidationException::withMessages([
                    'archivo' => ['El PDF no es válido (no contiene la cabecera %PDF).'],
                ]);
            }
        }

        // 4. Resolver proyecto (lanza ProyectoNoEncontradoException → 422 con accion_sugerida)
        $proyecto = ProyectoResolver::resolver($salida->nombre_proyecto);

        // 5. Invalidar evidencias vigentes del mismo tipo (evita firmas duplicadas)
        EvidenciaEntrega::query()
            ->where('salida_id', $salida->id)
            ->where('tipo', $tipo)
            ->where('estado', 'vigente')
            ->update([
                'estado'            => 'invalidada_por_edicion',
                'invalidada_at'     => now(),
                'invalidada_por'    => $user->usuario,
                'invalidada_motivo' => "Reemplazada por nueva subida de tipo {$tipo}",
            ]);

        // 6. Guardar archivo en disco 'local'
        $extension = $archivo->getClientOriginalExtension() ?: ($archivo->extension() ?: 'bin');
        $nombre    = Str::uuid()->toString() . '.' . strtolower($extension);
        $path      = $archivo->storeAs("evidencias_entrega/{$salida->id}", $nombre, 'local');

        // 7. Crear registro en BD (vigente)
        $evidencia = EvidenciaEntrega::create([
            'salida_id'               => $salida->id,
            'proyecto_id'             => $proyecto->id,
            'archivo_path'            => $path,
            'archivo_nombre_original' => $archivo->getClientOriginalName(),
            'archivo_mime'            => $mime,
            'archivo_tamano'          => $archivo->getSize(),
            'tipo'                    => $tipo,
            'estado'                  => 'vigente',
            'notas'                   => $validated['notas'] ?? null,
            'subido_por'              => $user->usuario,
        ]);

        return response()->json([
            'success'   => true,
            'evidencia' => [
                'id'         => $evidencia->id,
                'tipo'       => $evidencia->tipo,
                'estado'     => $evidencia->estado,
                'mime'       => $evidencia->archivo_mime,
                'size_bytes' => $evidencia->archivo_tamano,
                'created_at' => $evidencia->created_at?->toIso8601String(),
            ],
        ], 201);
    }

}
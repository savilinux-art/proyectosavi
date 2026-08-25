<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    /**
     * Listar notificaciones
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $notificaciones = Notificacion::where('usuario_id', $user->usuario)
            ->orderBy('created_at', 'desc')
            ->get();

        $noLeidas = $notificaciones->where('leida', false)->count();

        return response()->json([
            'success' => true,
            'data' => $notificaciones,
            'no_leidas' => $noLeidas,
            'total' => $notificaciones->count()
        ]);
    }

    /**
     * Mostrar notificación
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $notificacion = Notificacion::where('usuario_id', $user->usuario)
            ->findOrFail($id);

        // Marcar como leída si no lo está
        if (!$notificacion->leida) {
            $notificacion->update(['leida' => true]);
        }

        return response()->json([
            'success' => true,
            'data' => $notificacion
        ]);
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notificacion = Notificacion::where('usuario_id', $user->usuario)
            ->findOrFail($id);

        $notificacion->update(['leida' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída'
        ]);
    }

    /**
     * Marcar todas como leídas
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        Notificacion::where('usuario_id', $user->usuario)
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas'
        ]);
    }

    /**
     * Eliminar notificación
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $notificacion = Notificacion::where('usuario_id', $user->usuario)
            ->findOrFail($id);

        $notificacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada'
        ]);
    }

    /**
     * Contar notificaciones no leídas
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();
        $count = Notificacion::where('usuario_id', $user->usuario)
            ->where('leida', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
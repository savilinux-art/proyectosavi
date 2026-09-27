<?php

namespace App\Http\Controllers;

use App\Models\NotificacionWeb;
use Illuminate\Support\Facades\Session;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = NotificacionWeb::where('usuario_id', Session::get('user_id'))
            ->orderByDesc('created_at')
            ->get();

        $noLeidas = $notificaciones->whereNull('leida_at')->count();

        return view('notificaciones.index', compact('notificaciones', 'noLeidas'));
    }

    public function markAsRead($id)
    {
        $notificacion = NotificacionWeb::where('usuario_id', Session::get('user_id'))
            ->findOrFail($id);
        $notificacion->update(['leida_at' => now()]);

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación marcada como leída');
    }

    public function markAllAsRead()
    {
        NotificacionWeb::where('usuario_id', Session::get('user_id'))
            ->whereNull('leida_at')
            ->update(['leida_at' => now()]);

        return redirect()->route('notificaciones.index')
            ->with('success', 'Todas las notificaciones marcadas como leídas');
    }

    public function destroy($id)
    {
        $notificacion = NotificacionWeb::where('usuario_id', Session::get('user_id'))
            ->findOrFail($id);
        $notificacion->delete();

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación eliminada');
    }

    public function count()
    {
        $count = NotificacionWeb::where('usuario_id', Session::get('user_id'))
            ->whereNull('leida_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Session;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', Session::get('user_usuario'))
            ->orderBy('created_at', 'desc')
            ->get();

        $noLeidas = $notificaciones->where('leida', false)->count();

        return view('notificaciones.index', compact('notificaciones', 'noLeidas'));
    }

    public function markAsRead($id)
    {
        $notificacion = Notificacion::where('usuario_id', Session::get('user_usuario'))
            ->findOrFail($id);
        $notificacion->update(['leida' => true]);

        return redirect()->route('notificaciones.index')->with('success', 'Notificación marcada como leída');
    }

    public function markAllAsRead()
    {
        Notificacion::where('usuario_id', Session::get('user_usuario'))
            ->where('leida', false)
            ->update(['leida' => true]);

        return redirect()->route('notificaciones.index')->with('success', 'Todas las notificaciones marcadas como leídas');
    }

    public function destroy($id)
    {
        $notificacion = Notificacion::where('usuario_id', Session::get('user_usuario'))
            ->findOrFail($id);
        $notificacion->delete();

        return redirect()->route('notificaciones.index')->with('success', 'Notificación eliminada');
    }

    public function count()
    {
        $count = Notificacion::where('usuario_id', Session::get('user_usuario'))
            ->where('leida', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
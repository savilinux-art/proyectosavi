<?php

namespace App\Http\Controllers;

use App\Models\GeocercaAlerta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GeocercaAlertaController extends Controller
{
    private function soloAdmin()
    {
        abort_unless(
            Session::get('user_rol') === 'Administrador',
            403,
            'Solo administradores'
        );
    }

    /**
     * Últimas N alertas (para el widget y polling)
     */
    public function recientes(Request $request)
    {
        $this->soloAdmin();

        $limit = (int) $request->get('limit', 10);

        $alertas = GeocercaAlerta::with(['usuario:id,nombre', 'geocerca:id,nombre,color'])
            ->recientes($limit)
            ->get()
            ->map(fn($a) => [
                'id'         => $a->id,
                'tipo'       => $a->tipo,
                'geocerca'   => $a->geocerca->nombre ?? '—',
                'color'      => $a->geocerca->color ?? '#888',
                'usuario'    => $a->usuario->nombre ?? '—',
                'fecha'      => $a->fecha_hora->format('d/m/Y H:i'),
                'hace'       => $a->fecha_hora->diffForHumans(),
                'lat'        => $a->latitud,
                'lng'        => $a->longitud,
                'leido'      => $a->notificado,
            ]);

        return response()->json([
            'alertas'  => $alertas,
            'noLeidas' => GeocercaAlerta::noLeidas()->count(),
        ]);
    }

    /**
     * Marcar una alerta como leída
     */
    public function marcarLeida($id)
    {
        $this->soloAdmin();

        $alerta = GeocercaAlerta::findOrFail($id);
        $alerta->notificado = true;
        $alerta->save();

        return response()->json([
            'ok'       => true,
            'noLeidas' => GeocercaAlerta::noLeidas()->count(),
        ]);
    }

    /**
     * Marcar todas como leídas
     */
    public function marcarTodas()
    {
        $this->soloAdmin();

        GeocercaAlerta::noLeidas()->update(['notificado' => true]);

        return response()->json(['ok' => true, 'noLeidas' => 0]);
    }
}
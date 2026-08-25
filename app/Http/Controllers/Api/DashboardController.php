<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Inventario;
use App\Models\Instalacion;
use App\Models\Notificacion;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Obtener datos del dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $rol = $user->rol;

        $data = [
            'user' => [
                'nombre' => $user->nombre,
                'rol' => $user->rol
            ],
            'stats' => $this->getStats($rol, $user),
            'notificaciones' => $this->getNotifications($user)
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Obtener estadísticas
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        $stats = $this->getStats($user->rol, $user);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    private function getStats($rol, $user)
    {
        $stats = [];

        if (in_array($rol, ['Administrador', 'Ventas'])) {
            $stats['total_ventas'] = Venta::count();
            $stats['ventas_ganadas'] = Venta::where('venta_ganada', true)->count();
            $stats['monto_total'] = Venta::sum('monto_venta');
            $stats['ventas_por_estatus'] = Venta::select('estatus', DB::raw('count(*) as total'))
                ->whereNotNull('estatus')
                ->groupBy('estatus')
                ->get();
        }

        if (in_array($rol, ['Administrador', 'Inventarios'])) {
            $stats['total_inventario'] = Inventario::sum('existencia');
            $stats['productos_bajo'] = Inventario::where('existencia', '<', 10)->count();
            $stats['productos_sin_stock'] = Inventario::where('existencia', 0)->count();
        }

        if (in_array($rol, ['Administrador', 'Instalador'])) {
            $stats['instalaciones_pendientes'] = Instalacion::where('estatus_instalacion', '!=', 'entrega')
                ->where('estatus_instalacion', '!=', 'preparacion')
                ->count();
            
            if ($rol == 'Instalador') {
                $stats['mis_instalaciones'] = Instalacion::where('id_usuario_asignado', $user->usuario)
                    ->with(['proyecto'])
                    ->get();
            }
        }

        return $stats;
    }

    private function getNotifications($user)
    {
        $notificaciones = Notificacion::where('usuario_id', $user->usuario)
            ->where('leida', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'no_leidas' => Notificacion::where('usuario_id', $user->usuario)
                ->where('leida', false)
                ->count(),
            'lista' => $notificaciones
        ];
    }
}
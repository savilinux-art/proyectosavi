<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Instalacion;
use App\Models\Inventario;
use App\Models\Usuario;
use App\Models\Cliente;        // ← Agregar
use App\Models\Proyecto;       // ← Agregar
use App\Models\Notificacion;   // ← Agregar (opcional, si usas tabla de notificaciones)
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rol = Session::get('user_rol');
        $userUsuario = Session::get('user_usuario');
        $data = [];

        // =====================================================
        // 1. NOTIFICACIONES (según rol)
        // =====================================================
        if ($rol == 'Instalador') {
            $data['notificaciones'] = $this->getNotificacionesInstalador($userUsuario);
        } else {
            $data['notificaciones'] = $this->getNotificacionesGenerales();
        }

        // =====================================================
        // 2. DATOS SEGÚN ROL
        // =====================================================
        switch ($rol) {
            // ---------- ADMINISTRADOR Y VENTAS ----------
            case 'Administrador':
            case 'Ventas':
                $data['total_ventas'] = Venta::count();
                $data['total_inventario'] = Inventario::sum('existencia');
                $data['instalaciones_pendientes'] = Instalacion::where('estatus_instalacion', '!=', 'entrega')->count();
                $data['total_clientes'] = Cliente::count();
                $data['ventas_por_estatus'] = Venta::select('estatus', DB::raw('count(*) as total'))
                    ->whereNotNull('estatus')
                    ->groupBy('estatus')
                    ->get();
                break;

            // ---------- INVENTARIOS ----------
            case 'Inventarios':
                $data['total_inventario'] = Inventario::sum('existencia');
                $data['total_ventas'] = Venta::count();
                $data['total_proyectos'] = Proyecto::count();
                $data['inventario_bajo'] = Inventario::where('existencia', '<', 10)->get();
                break;

            // ---------- INSTALADOR ----------
            case 'Instalador':
                $data['instalaciones'] = Instalacion::whereHas('instaladores', function($q) use ($userUsuario) {
                    $q->where('instalador_usuario', $userUsuario);
                })->with(['proyecto'])->get();

                $data['proyectos'] = Proyecto::whereHas('venta', function($q) use ($userUsuario) {
                    $q->whereHas('instalaciones', function($sub) use ($userUsuario) {
                        $sub->whereHas('instaladores', function($sq) use ($userUsuario) {
                            $sq->where('instalador_usuario', $userUsuario);
                        });
                    });
                })->get();
                break;

            // ---------- CONTABILIDAD ----------
            case 'Contabilidad':
                $data['total_clientes'] = Cliente::count();
                $data['total_proyectos'] = Proyecto::count();
                $data['total_instalaciones'] = Instalacion::count();
                break;

            // ---------- SISTEMAS ----------
            case 'Sistemas':
                $data['proyectos'] = Proyecto::with(['venta'])->get();
                $data['instalaciones'] = Instalacion::with(['proyecto', 'instaladores'])->get();
                break;

            // ---------- POR DEFECTO (otros roles) ----------
            default:
                $data['total_ventas'] = Venta::count();
                $data['total_inventario'] = Inventario::sum('existencia');
                $data['instalaciones_pendientes'] = Instalacion::where('estatus_instalacion', '!=', 'entrega')->count();
                $data['ventas_por_estatus'] = Venta::select('estatus', DB::raw('count(*) as total'))
                    ->whereNotNull('estatus')
                    ->groupBy('estatus')
                    ->get();
                break;
        }

        return view('dashboard.index', $data);
    }

    // =====================================================
    // NOTIFICACIONES GENERALES (para Admin, Ventas, Inventarios, etc.)
    // =====================================================
    private function getNotificacionesGenerales()
    {
        $notificaciones = [];

        $prospeccion = Venta::where('estatus', 'prospeccion')->count();
        if ($prospeccion > 0) {
            $notificaciones[] = "🔵 Hay {$prospeccion} venta(s) en prospección";
        }

        $levantamiento = Venta::where('estatus', 'levantamiento')->count();
        if ($levantamiento > 0) {
            $notificaciones[] = "🟡 Hay {$levantamiento} venta(s) en levantamiento";
        }

        $cotizacion = Venta::where('estatus', 'cotizacion')->count();
        if ($cotizacion > 0) {
            $notificaciones[] = "🟠 Hay {$cotizacion} venta(s) en cotización";
        }

        $instalaciones = Instalacion::where('estatus_instalacion', '!=', 'entrega')
            ->orWhereNull('estatus_instalacion')
            ->count();
        if ($instalaciones > 0) {
            $notificaciones[] = "🔧 Hay {$instalaciones} instalación(es) en proceso";
        }

        $inventario_bajo = Inventario::where('existencia', '<', 10)->count();
        if ($inventario_bajo > 0) {
            $notificaciones[] = "⚠️ Hay {$inventario_bajo} producto(s) con inventario bajo";
        }

        return $notificaciones;
    }

    // =====================================================
    // NOTIFICACIONES PARA INSTALADOR
    // =====================================================
    private function getNotificacionesInstalador($userId)
    {
        $notificaciones = [];

        $instalaciones = Instalacion::whereHas('instaladores', function($q) use ($userId) {
            $q->where('instalador_usuario', $userId);
        })
        ->whereIn('estatus_instalacion', ['preparacion', 'en_proceso', 'programacion', 'pruebas'])
        ->with('proyecto')
        ->get();

        foreach ($instalaciones as $instalacion) {
            $notificaciones[] = "📌 Instalación pendiente: {$instalacion->proyecto->nombre_proyecto} - Estatus: {$instalacion->estatus_instalacion}";
        }

        // Instalaciones completadas recientemente (opcional)
        $completadas = Instalacion::whereHas('instaladores', function($q) use ($userId) {
            $q->where('instalador_usuario', $userId);
        })
        ->where('estatus_instalacion', 'entrega')
        ->whereDate('updated_at', '>=', now()->subDays(7))
        ->count();

        if ($completadas > 0) {
            $notificaciones[] = "✅ {$completadas} instalación(es) completadas en los últimos 7 días";
        }

        return $notificaciones;
    }
}
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instalacion;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;   
use Illuminate\Support\Facades\Notification;
class AsignacionInstalacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    if (Session::get('user_rol') !== 'Administrador') {
        return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
    }

    $instalacionesPendientes = Instalacion::whereDoesntHave('instaladores')
        ->whereIn('estatus_instalacion', ['preparacion', 'en_proceso', 'programacion'])
        ->with(['proyecto', 'instaladores']) // Cargar proyecto e instaladores
        ->orderBy('created_at', 'desc')
        ->get();

    $instaladores = Usuario::where('rol', 'Instalador')
        ->withCount(['instalaciones' => function($query) {
            $query->whereIn('estatus_instalacion', ['preparacion', 'en_proceso', 'programacion', 'pruebas']);
        }])
        ->get();

    $estadisticas = [
        'total_pendientes' => $instalacionesPendientes->count(),
        'instaladores_activos' => $instaladores->where('instalaciones_count', '>', 0)->count(),
        'instaladores_disponibles' => $instaladores->where('instalaciones_count', 0)->count(),
    ];

    return view('asignaciones.index', compact('instalacionesPendientes', 'instaladores', 'estadisticas'));
}

    /**
     * Show the form for creating a new resource.
     */
 public function create()
{
    if (Session::get('user_rol') !== 'Administrador') {
        return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
    }

    $instalacionesDisponibles = Instalacion::whereDoesntHave('instaladores')
        ->whereIn('estatus_instalacion', ['preparacion', 'en_proceso', 'programacion'])
        ->with(['proyecto'])
        ->get();

    

    $instaladores = Usuario::where('rol', 'Instalador')
        ->withCount(['instalaciones' => function($query) {
            $query->whereIn('estatus_instalacion', ['preparacion', 'en_proceso', 'programacion', 'pruebas']);
        }])
        ->orderBy('instalaciones_count')
        ->get();

    return view('asignaciones.create', compact('instalacionesDisponibles', 'instaladores'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TelegramService $telegramService) // ← Agrega la inyección
{
    if (Session::get('user_rol') !== 'Administrador') {
        return redirect()->route('dashboard')->with('error', 'No autorizado');
    }

    $request->validate([
        'instalacion_id' => 'required|exists:instalaciones,id',
        'instaladores' => 'required|array|min:1',
        'instaladores.*' => 'exists:usuarios,usuario',
        'fecha_hora_inicio' => 'required|date|after:now',
        'observaciones' => 'nullable|string'
    ]);

    DB::beginTransaction();
    try {
        $instalacion = Instalacion::findOrFail($request->instalacion_id);
        
        if ($instalacion->instaladores()->count() > 0) {
            return back()->with('error', 'Esta instalación ya tiene instaladores asignados');
        }

        // Asignar instaladores
        $instalacion->instaladores()->sync($request->instaladores);
        $instalacion->update([
            'fecha_hora_inicio' => $request->fecha_hora_inicio,
            'estatus_instalacion' => 'en_proceso',
            'observaciones' => $request->observaciones
        ]);

        // 🔥 NUEVO: Enviar notificaciones por Telegram
        foreach ($instalacion->instaladores as $instalador) {
            try {
                $telegramService->notifyInstalacionAsignada($instalador, $instalacion);
                Log::info('✅ Notificación enviada a instalador', [
                    'instalador_id' => $instalador->id,
                    'chat_id' => $instalador->telegram_chat_id
                ]);
            } catch (\Exception $e) {
                Log::error('❌ Error enviando notificación a instalador', [
                    'instalador_id' => $instalador->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        DB::commit();

        return redirect()->route('asignaciones.index')
            ->with('success', 'Instalación asignada exitosamente a ' . count($request->instaladores) . ' instalador(es)');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al asignar la instalación: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $instalador = Usuario::findOrFail($id);
        $instalaciones = Instalacion::whereHas('instaladores', function($q) use ($id) {
            $q->where('instalador_usuario', $id);
        })->with(['proyecto', 'instaladores'])->get();

        return view('asignaciones.show', compact('instalador', 'instalaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este módulo');
        }

        $instalacion = Instalacion::with(['proyecto', 'instaladores'])->findOrFail($id);
        $instaladores = Usuario::where('rol', 'Instalador')->get();
        $instaladoresSeleccionados = $instalacion->instaladores->pluck('usuario')->toArray();
        $estatus = ['preparacion', 'en_proceso', 'programacion', 'pruebas', 'entrega'];

        return view('asignaciones.edit', compact('instalacion', 'instaladores', 'instaladoresSeleccionados', 'estatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No autorizado');
        }

        $request->validate([
            'instaladores' => 'required|array|min:1',
            'instaladores.*' => 'exists:usuarios,usuario',
            'estatus_instalacion' => 'required|string',
            'fecha_hora_inicio' => 'required|date',
            'fecha_hora_fin' => 'nullable|date|after:fecha_hora_inicio'
        ]);

        DB::beginTransaction();
        try {
            $instalacion = Instalacion::findOrFail($id);
            
            $instalacion->update($request->only(['estatus_instalacion', 'fecha_hora_inicio', 'fecha_hora_fin']));
            $instalacion->instaladores()->sync($request->instaladores);

            DB::commit();

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage (liberar instalación).
     */
    public function destroy($id)
    {
        if (Session::get('user_rol') !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No autorizado');
        }

        DB::beginTransaction();
        try {
            $instalacion = Instalacion::findOrFail($id);
            $instalacion->instaladores()->detach();
            $instalacion->update(['estatus_instalacion' => 'preparacion']);

            DB::commit();

            return redirect()->route('asignaciones.index')
                ->with('success', 'Instalación liberada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al liberar la instalación: ' . $e->getMessage());
        }
    }
}
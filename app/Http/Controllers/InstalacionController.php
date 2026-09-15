<?php

namespace App\Http\Controllers;

use App\Models\Estatus;
use App\Models\Instalacion;
use App\Models\InstalacionFoto;
use App\Models\Usuario;
use App\Models\Venta;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class InstalacionController extends Controller
{
    /**
     * Inyectamos TelegramService por constructor (PHP 8.4: property promotion).
     * Ahora `$this->telegram` es válido en TODOS los métodos.
     */
    public function __construct(
        private readonly TelegramService $telegram
    ) {}

    /* ============================================================
     *  HELPERS
     * ============================================================ */
    private function userId(): ?int
    {
        return Session::get('user_id');
    }

    private function userRol(): ?string
    {
        return Session::get('user_rol');
    }

    private function userUsuario(): ?string
    {
        return Session::get('user_usuario');
    }

    private function esInstalador(): bool
    {
        return $this->userRol() === 'Instalador';
    }

    /**
     * Verifica que el instalador tenga permiso sobre la instalación.
     * Lanza 403 si no.
     */
    private function verificarAcceso(Instalacion $instalacion): void
    {
        if (!$this->esInstalador()) {
            return; // admins/otros roles pueden ver todo
        }

        $tiene = $instalacion->instaladores->contains('usuario', $this->userUsuario());
        if (!$tiene) {
            abort(403, 'No tienes permiso para acceder a esta instalación.');
        }
    }

    /* ============================================================
     *  INDEX
     * ============================================================ */
    public function index(Request $request)
    {
        try {
            $query = Instalacion::with(['proyecto', 'instaladores'])
                ->withCount('fotos');

            // Filtrar por rol
            if ($this->esInstalador()) {
                $query->porInstalador($this->userUsuario());
            }

            // Filtros
            if ($request->filled('estatus')) {
                $query->where('estatus_instalacion', $request->estatus);
            }

            if ($request->filled('buscar')) {
                $b = $request->buscar;
                $query->where(function ($q) use ($b) {
                    $q->where('nombre_proyecto', 'like', "%{$b}%")
                      ->orWhere('nombre_instalacion', 'like', "%{$b}%");
                });
            }

            if ($request->filled('desde')) {
                $query->whereDate('fecha_hora_inicio', '>=', $request->desde);
            }

            if ($request->filled('hasta')) {
                $query->whereDate('fecha_hora_inicio', '<=', $request->hasta);
            }

            $instalaciones = $query->orderByDesc('fecha_hora_inicio')
                                   ->paginate(20)
                                   ->withQueryString();

            $estatus = Estatus::where('tipo', 'instalacion')
                              ->orderBy('estatus')
                              ->get();

            return view('instalaciones.index', compact('instalaciones', 'estatus'));

        } catch (\Throwable $e) {
            Log::error('Error listando instalaciones: ' . $e->getMessage(), [
                'user_id' => $this->userId(),
            ]);
            return redirect()->route('dashboard')
                ->with('error', 'No se pudo cargar el listado de instalaciones.');
        }
    }

    /* ============================================================
     *  CREATE
     * ============================================================ */
    public function create()
    {
        $proyectos    = Venta::where('venta_ganada', true)->orderBy('nombre_proyecto')->get();
        $instaladores = Usuario::where('rol', 'Instalador')->orderBy('nombre')->get();
        $estatus      = Estatus::where('tipo', 'instalacion')->orderBy('estatus')->get();

        return view('instalaciones.create', compact('proyectos', 'instaladores', 'estatus'));
    }

    /* ============================================================
     *  STORE
     * ============================================================ */
    public function store(Request $request)
    {
        // Normalizar instaladores si llegan como string separado por comas
        if ($request->has('instaladores') && is_string($request->instaladores)) {
            $request->merge(['instaladores' => explode(',', $request->instaladores)]);
        }

        $request->validate([
            'nombre_proyecto'     => 'required|exists:ventas,nombre_proyecto',
            'nombre_instalacion'  => 'required|string|max:255',
            'fecha_hora_inicio'   => 'required|date',
            'estatus_instalacion' => 'required|exists:estatus,estatus',
            'instaladores'        => 'nullable|array',
            'instaladores.*'      => 'exists:usuarios,id',
            'latitud'             => 'nullable|numeric|between:-90,90',
            'longitud'            => 'nullable|numeric|between:-180,180',
            'direccion'           => 'nullable|string|max:255',
            'fotos'               => 'nullable|array',
            'fotos.*'             => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'tipo_fotos'          => 'nullable|in:inicio,proceso,fin,incidencia',
            'check_list'          => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $instalacion = Instalacion::create([
                'nombre_proyecto'     => $request->nombre_proyecto,
                'nombre_instalacion'  => $request->nombre_instalacion,
                'fecha_hora_inicio'   => $request->fecha_hora_inicio,
                'estatus_instalacion' => $request->estatus_instalacion,
                'latitud'             => $request->latitud,
                'longitud'            => $request->longitud,
                'direccion'           => $request->direccion,
                'check_list'          => $request->check_list ?? [],
            ]);

            // Asignar instaladores y notificar
            if ($request->filled('instaladores')) {
                $usuarios = Usuario::whereIn('id', $request->instaladores)
                    ->pluck('usuario')
                    ->toArray();
                $instalacion->instaladores()->sync($usuarios);

                foreach ($instalacion->fresh()->instaladores as $inst) {
                    try {
                        $this->telegram->notifyInstalacionAsignada($inst, $instalacion);
                    } catch (\Throwable $e) {
                        Log::warning('No se pudo notificar a instalador: ' . $e->getMessage(), [
                            'instalador' => $inst->usuario,
                        ]);
                    }
                }
            }

            // Guardar fotos si vienen
            if ($request->hasFile('fotos')) {
                $this->guardarFotos(
                    $instalacion,
                    $request->file('fotos'),
                    $request->input('tipo_fotos', 'proceso')
                );
            }

            DB::commit();

            Log::info('Instalación creada', [
                'id'      => $instalacion->id,
                'user_id' => $this->userId(),
            ]);

            return redirect()
                ->route('instalaciones.index')
                ->with('success', "Instalación «{$instalacion->nombre_instalacion}» creada correctamente.");

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creando instalación: ' . $e->getMessage(), [
                'user_id' => $this->userId(),
                'input'   => $request->except(['fotos']),
            ]);

            return back()
                ->withInput()
                ->with('error', 'No se pudo crear la instalación. Intenta nuevamente.');
        }
    }

    /* ============================================================
     *  SHOW
     * ============================================================ */
    public function show($id)
    {
        try {
            $instalacion = Instalacion::with([
                'proyecto',
                'instaladores',
                'fotos',
                'ubicaciones.usuario',
                'ultimaUbicacion',
            ])->findOrFail($id);

            $this->verificarAcceso($instalacion);

            return view('instalaciones.show', compact('instalacion'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return redirect()->route('instalaciones.index')
                ->with('error', 'La instalación solicitada no existe.');
        }
    }

    /* ============================================================
     *  EDIT
     * ============================================================ */
    public function edit($id)
    {
        try {
            $instalacion = Instalacion::with(['instaladores', 'fotos'])->findOrFail($id);
            $this->verificarAcceso($instalacion);

            $proyectos    = Venta::where('venta_ganada', true)->orderBy('nombre_proyecto')->get();
            $instaladores = Usuario::where('rol', 'Instalador')->orderBy('nombre')->get();
            $estatus      = Estatus::where('tipo', 'instalacion')->orderBy('estatus')->get();

            $instaladoresSeleccionados = $instalacion->instaladores->pluck('id')->toArray();

            return view('instalaciones.edit', compact(
                'instalacion',
                'proyectos',
                'instaladores',
                'estatus',
                'instaladoresSeleccionados'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return redirect()->route('instalaciones.index')
                ->with('error', 'La instalación solicitada no existe.');
        }
    }

    /* ============================================================
     *  UPDATE
     * ============================================================ */
    public function update(Request $request, $id)
    {
        $instalacion = Instalacion::with('instaladores')->find($id);
        if (!$instalacion) {
            return redirect()->route('instalaciones.index')
                ->with('error', 'La instalación solicitada no existe.');
        }

        $this->verificarAcceso($instalacion);

        if ($request->has('instaladores') && is_string($request->instaladores)) {
            $request->merge(['instaladores' => explode(',', $request->instaladores)]);
        }

        $request->validate([
            'nombre_proyecto'     => 'required|exists:ventas,nombre_proyecto',
            'nombre_instalacion'  => 'required|string|max:255',
            'fecha_hora_inicio'   => 'required|date',
            'fecha_hora_fin'      => 'nullable|date|after_or_equal:fecha_hora_inicio',
            'estatus_instalacion' => 'required|exists:estatus,estatus',
            'instaladores'        => 'nullable|array',
            'instaladores.*'      => 'exists:usuarios,id',
            'latitud'             => 'nullable|numeric|between:-90,90',
            'longitud'            => 'nullable|numeric|between:-180,180',
            'direccion'           => 'nullable|string|max:255',
            'fotos'               => 'nullable|array',
            'fotos.*'             => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'tipo_fotos'          => 'nullable|in:inicio,proceso,fin,incidencia',
            'check_list'          => 'nullable|array',
        ]);

        // Detectar cambios ANTES de actualizar
        $estatusAnterior = $instalacion->estatus_instalacion;
        $estatusNuevo    = $request->estatus_instalacion;
        $cambioEstatus   = $estatusAnterior !== $estatusNuevo;

        $instaladoresViejos = $instalacion->instaladores->pluck('id')->sort()->values()->toArray();
        $instaladoresNuevos = collect($request->instaladores ?? [])->sort()->values()->toArray();
        $cambioInstaladores = $instaladoresViejos !== $instaladoresNuevos;

        DB::beginTransaction();
        try {
            $instalacion->update([
                'nombre_proyecto'     => $request->nombre_proyecto,
                'nombre_instalacion'  => $request->nombre_instalacion,
                'fecha_hora_inicio'   => $request->fecha_hora_inicio,
                'fecha_hora_fin'      => $request->fecha_hora_fin,
                'estatus_instalacion' => $estatusNuevo,
                'latitud'             => $request->latitud,
                'longitud'            => $request->longitud,
                'direccion'           => $request->direccion,
                'check_list'          => $request->check_list ?? $instalacion->check_list,
            ]);

            // Sincronizar instaladores
            if ($request->has('instaladores')) {
                $usuarios = Usuario::whereIn('id', $request->instaladores)
                    ->pluck('usuario')
                    ->toArray();
                $instalacion->instaladores()->sync($usuarios);
            }

            // Guardar fotos nuevas
            if ($request->hasFile('fotos')) {
                $this->guardarFotos(
                    $instalacion,
                    $request->file('fotos'),
                    $request->input('tipo_fotos', 'proceso')
                );
            }

            DB::commit();

            // ========== NOTIFICACIONES (fuera de la transacción) ==========
            try {
                if ($cambioEstatus) {
                    $this->telegram->notifyCambioEstatus(
                        $instalacion->fresh(),
                        $estatusAnterior,
                        $estatusNuevo
                    );
                } elseif ($cambioInstaladores) {
                    // Notificar solo si cambiaron instaladores (y no el estatus)
                    foreach ($instalacion->fresh()->instaladores as $inst) {
                        $this->telegram->notifyInstalacionAsignada($inst, $instalacion);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Error en notificaciones Telegram: ' . $e->getMessage());
            }

            Log::info('Instalación actualizada', [
                'id'              => $instalacion->id,
                'estatus_antes'   => $estatusAnterior,
                'estatus_despues' => $estatusNuevo,
                'user_id'         => $this->userId(),
            ]);

            return redirect()
                ->route('instalaciones.index')
                ->with('success', 'Instalación actualizada correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error actualizando instalación: ' . $e->getMessage(), [
                'id'      => $id,
                'user_id' => $this->userId(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar la instalación.');
        }
    }

    /* ============================================================
     *  DESTROY (soft delete)
     * ============================================================ */
    public function destroy($id)
    {
        if ($this->esInstalador()) {
            return back()->with('error', 'No tienes permiso para eliminar instalaciones.');
        }

        $instalacion = Instalacion::find($id);
        if (!$instalacion) {
            return redirect()->route('instalaciones.index')
                ->with('error', 'La instalación solicitada no existe.');
        }

        DB::beginTransaction();
        try {
            $nombre = $instalacion->nombre_instalacion;
            $instalacion->delete(); // soft delete

            DB::commit();

            Log::warning('Instalación eliminada (soft)', [
                'id'      => $id,
                'user_id' => $this->userId(),
            ]);

            return redirect()
                ->route('instalaciones.index')
                ->with('success', "Instalación «{$nombre}» eliminada correctamente.");

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error eliminando instalación: ' . $e->getMessage(), ['id' => $id]);
            return back()->with('error', 'No se pudo eliminar la instalación.');
        }
    }

    /* ============================================================
     *  ENDPOINTS EXTRA
     * ============================================================ */

    /**
     * Cambio rápido de estatus (AJAX desde el panel o el mapa).
     */
    public function cambiarEstatus(Request $request, $id)
{
    $request->validate([
        'estatus_instalacion' => 'required|exists:estatus,estatus',
    ]);

    $instalacion = Instalacion::with('instaladores')->find($id);

    // Si no existe → JSON o redirect según el tipo de petición
    if (!$instalacion) {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => false, 'message' => 'Instalación no encontrada.'], 404);
        }
        return redirect()->route('instalaciones.index')
            ->with('error', 'La instalación no existe.');
    }

    $this->verificarAcceso($instalacion);

    $anterior = $instalacion->estatus_instalacion;
    $nuevo    = $request->estatus_instalacion;

    // Si no hay cambio → respuesta según tipo
    if ($anterior === $nuevo) {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Sin cambios.']);
        }
        return back()->with('info', 'El estatus es el mismo, sin cambios.');
    }

    DB::beginTransaction();
    try {
        $instalacion->update([
            'estatus_instalacion' => $nuevo,
            'fecha_hora_fin'      => in_array($nuevo, ['completada', 'cancelada', 'entrega'])
                                        ? now()
                                        : $instalacion->fecha_hora_fin,
        ]);

        DB::commit();

        // Notificar (fuera de la transacción)
        try {
            $this->telegram->notifyCambioEstatus($instalacion->fresh(), $anterior, $nuevo);
        } catch (\Throwable $e) {
            Log::warning('Error notificando cambio de estatus: ' . $e->getMessage());
        }

        Log::info('Estatus cambiado', [
            'instalacion_id' => $instalacion->id,
            'anterior'       => $anterior,
            'nuevo'          => $nuevo,
            'user_id'        => $this->userId(),
        ]);

        // Respuesta según tipo de petición
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'      => true,
                'message' => "Estatus cambiado a {$nuevo}.",
            ]);
        }

        return back()->with('success', "Estatus cambiado a {$nuevo}.");

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Error cambiando estatus: ' . $e->getMessage(), ['id' => $id]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => false, 'message' => 'Error al cambiar estatus.'], 500);
        }

        return back()->with('error', 'No se pudo cambiar el estatus.');
    }
}
    /**
     * Eliminar una foto individual.
     */
    public function eliminarFoto($id, $fotoId)
    {
        if ($this->esInstalador()) {
            abort(403);
        }

        $foto = InstalacionFoto::where('instalacion_id', $id)->find($fotoId);
        if (!$foto) {
            return back()->with('error', 'La foto no existe.');
        }

        try {
            Storage::disk('public')->delete($foto->ruta);
            $foto->delete();

            return back()->with('success', 'Foto eliminada.');
        } catch (\Throwable $e) {
            Log::error('Error eliminando foto: ' . $e->getMessage(), ['foto_id' => $fotoId]);
            return back()->with('error', 'No se pudo eliminar la foto.');
        }
    }

    /**
     * Datos para el mapa Leaflet (polling AJAX).
     */
    public function mapaData(Request $request)
    {
        try {
            $instalaciones = Instalacion::query()
                ->when($this->esInstalador(), fn($q) => $q->porInstalador($this->userUsuario()))
                ->whereNotNull('latitud')
                ->whereNotNull('longitud')
                ->whereNotIn('estatus_instalacion', ['completada', 'cancelada'])
                ->get([
                    'id',
                    'nombre_instalacion',
                    'nombre_proyecto',
                    'latitud',
                    'longitud',
                    'estatus_instalacion',
                ]);

            // Última ubicación por usuario + instalación
            $instaladores = DB::table('ubicaciones_usuarios as u')
                ->join('usuarios as us', 'us.id', '=', 'u.usuario_id')
                ->whereNotNull('u.instalacion_id')
                ->whereIn('u.id', function ($q) {
                    $q->selectRaw('MAX(id)')
                      ->from('ubicaciones_usuarios')
                      ->groupBy('usuario_id', 'instalacion_id');
                })
                ->select(
                    'u.usuario_id',
                    'us.usuario',
                    'us.nombre',
                    'u.latitud',
                    'u.longitud',
                    'u.tipo',
                    'u.fecha_hora',
                    'u.instalacion_id'
                )
                ->get();

            return response()->json([
                'instalaciones' => $instalaciones,
                'instaladores'  => $instaladores,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error en mapaData: ' . $e->getMessage());
            return response()->json([
                'instalaciones' => [],
                'instaladores'  => [],
                'error'         => 'No se pudo cargar el mapa.',
            ], 500);
        }
    }

    /* ============================================================
     *  PRIVADO
     * ============================================================ */
    private function guardarFotos(Instalacion $instalacion, array $archivos, string $tipo): void
    {
        foreach ($archivos as $archivo) {
            try {
                $ruta = $archivo->store("instalaciones/{$instalacion->id}/{$tipo}", 'public');

                $foto = $instalacion->fotos()->create([
                    'ruta'               => $ruta,
                    'nombre_original'    => $archivo->getClientOriginalName(),
                    'mime'               => $archivo->getMimeType(),
                    'tamano_kb'          => (int) round($archivo->getSize() / 1024),
                    'tipo'               => $tipo,
                    'subida_por_usuario' => $this->userUsuario(),
                ]);

                // Notificar al admin (con la foto adjunta)
                try {
                    $this->telegram->notifyFotoSubida($instalacion, $tipo, $foto);
                } catch (\Throwable $e) {
                    Log::warning('No se pudo notificar foto: ' . $e->getMessage());
                }

            } catch (\Throwable $e) {
                Log::error('Error guardando foto: ' . $e->getMessage(), [
                    'instalacion_id' => $instalacion->id,
                    'tipo'           => $tipo,
                ]);
                // No lanzamos excepción para no romper todo el guardado
            }
        }
    }
}
<?php

namespace App\Console\Commands;

use App\Models\TraccarDevice;
use App\Models\UbicacionUsuario;
use App\Models\Usuario;
use App\Services\TraccarService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncTraccar extends Command
{
    protected $signature = 'traccar:sync
                            {--devices   : Solo sincronizar dispositivos}
                            {--positions : Solo sincronizar posiciones}
                            {--min-dist=20 : Distancia mínima en metros para guardar nueva posición}';

    protected $description = 'Sincroniza dispositivos y posiciones desde el servidor Traccar';

    public function handle(TraccarService $traccar): int
    {
        $soloDevices   = $this->option('devices');
        $soloPositions = $this->option('positions');
        $hacerTodo     = !$soloDevices && !$soloPositions;

        if ($hacerTodo || $soloDevices) {
            $this->syncDevices($traccar);
        }

        if ($hacerTodo || $soloPositions) {
            $this->syncPositions($traccar);
        }

        return self::SUCCESS;
    }

    /* ============================================================
     *  DISPOSITIVOS
     * ============================================================ */
    protected function syncDevices(TraccarService $traccar): void
    {
        $this->info('🔄 Sincronizando dispositivos...');

        $devices = $traccar->getDevices();

        if (empty($devices)) {
            $this->warn('⚠️  No se obtuvieron dispositivos desde Traccar.');
            return;
        }

        $creados = 0;
        $actualizados = 0;

        DB::beginTransaction();
        try {
            foreach ($devices as $d) {
                $device = TraccarDevice::updateOrCreate(
                    ['uniqueId' => $d['uniqueId'] ?? null],
                    [
                        'name'       => $d['name']       ?? null,
                        'status'     => $d['status']     ?? null,
                        'disabled'   => $d['disabled']   ?? false,
                        'lastUpdate' => isset($d['lastUpdate'])
                                            ? \Carbon\Carbon::parse($d['lastUpdate'])
                                            : null,
                        'positionId' => $d['positionId'] ?? null,
                        'groupId'    => $d['groupId']    ?? null,
                        'phone'      => $d['phone']      ?? null,
                        'model'      => $d['model']      ?? null,
                        'contact'    => $d['contact']    ?? null,
                        'category'   => $d['category']   ?? null,
                        'attribs'    => $d['attributes'] ?? null,
                    ]
                );

                $device->wasRecentlyCreated ? $creados++ : $actualizados++;
            }

            DB::commit();
            $this->info("✅ Dispositivos: {$creados} creados, {$actualizados} actualizados.");

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('❌ Error sincronizando dispositivos: ' . $e->getMessage());
            Log::error('Traccar sync devices error: ' . $e->getMessage());
        }
    }

    /* ============================================================
     *  POSICIONES
     * ============================================================ */
    protected function syncPositions(TraccarService $traccar): void
    {
        $this->info('🔄 Sincronizando posiciones...');

        $positions = $traccar->getAllPositions();

        if (empty($positions)) {
            $this->warn('⚠️  No se obtuvieron posiciones desde Traccar.');
            return;
        }

        // Mapear uniqueId de Traccar → id de usuario local
        // usuarios.traccar_device_id debe contener el uniqueId (no el id interno)
        $mapaUsuarios = Usuario::whereNotNull('traccar_device_id')
            ->pluck('id', 'traccar_device_id')
            ->toArray();

        if (empty($mapaUsuarios)) {
            $this->warn('⚠️  Ningún usuario tiene traccar_device_id asignado.');
            return;
        }

        // Mapa id interno de Traccar → uniqueId
        // (positions usa deviceId = id interno, no uniqueId)
        $deviceIdToUniqueId = TraccarDevice::pluck('uniqueId', 'id')->toArray();

        $guardadas = 0;
        $omitidas  = 0;
        $minDist   = (int) $this->option('min-dist');

        DB::beginTransaction();
        try {
            foreach ($positions as $pos) {
                $deviceId = $pos['deviceId'] ?? null;
                if (!$deviceId) { $omitidas++; continue; }

                // Resolver uniqueId a partir del id interno
                $uniqueId = $deviceIdToUniqueId[$deviceId] ?? null;
                if (!$uniqueId) { $omitidas++; continue; }

                // Resolver usuario local
                $usuarioId = $mapaUsuarios[$uniqueId] ?? null;
                if (!$usuarioId) { $omitidas++; continue; }

                $lat = $pos['latitude']  ?? null;
                $lng = $pos['longitude'] ?? null;
                if ($lat === null || $lng === null) { $omitidas++; continue; }

                $fecha = $pos['fixTime']
                      ?? $pos['deviceTime']
                      ?? $pos['serverTime']
                      ?? now();

                // Última posición guardada desde Traccar para este usuario
                $ultima = UbicacionUsuario::where('usuario_id', $usuarioId)
                    ->where('fuente', 'traccar')
                    ->orderByDesc('fecha_hora')
                    ->first();

                // Evitar duplicados por fecha
                if ($ultima && $ultima->fecha_hora >= $fecha) {
                    $omitidas++;
                    continue;
                }

                // Evitar guardar si no se movió lo suficiente
                if ($ultima && $minDist > 0) {
                    $distancia = $this->distanciaMetros(
                        (float) $ultima->latitud,
                        (float) $ultima->longitud,
                        (float) $lat,
                        (float) $lng
                    );

                    if ($distancia < $minDist) {
                        $omitidas++;
                        continue;
                    }
                }

                $ubicacion = UbicacionUsuario::create([
                    'usuario_id'     => $usuarioId,
                    'latitud'        => $lat,
                    'longitud'       => $lng,
                    'fecha_hora'     => $fecha,
                    'fuente'         => 'traccar',
                    'tipo'           => 'auto',
                    'instalacion_id' => null,
                    'detalles'       => json_encode($pos),
                ]);

                // Geocercas (opcional)
                try {
                    app(\App\Services\GeocercaService::class)
                        ->procesarUbicacion($ubicacion);
                } catch (\Throwable $e) {
                    Log::warning('Error geocercas Traccar: ' . $e->getMessage());
                }

                $guardadas++;
            }

            DB::commit();
            $this->info("✅ Posiciones: {$guardadas} guardadas, {$omitidas} omitidas.");

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('❌ Error sincronizando posiciones: ' . $e->getMessage());
            Log::error('Traccar sync positions error: ' . $e->getMessage());
        }
    }

    /* ============================================================
     *  HELPER: Distancia Haversine en metros
     * ============================================================ */
    protected function distanciaMetros(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R    = 6371000; // radio de la Tierra en metros
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLng / 2) ** 2;

        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
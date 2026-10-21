<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SyncPullRequest;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Estatus;
use App\Models\Instalacion;
use App\Models\InstalacionEvento;
use App\Models\InstalacionFoto;
use App\Models\Inventario;
use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class SyncController extends Controller
{
    /**
     * GET /api/sync/pull
     * Pull incremental (D-6). Sin `since` = bootstrap completo.
     * Scope: instalaciones del instalador autenticado (pivot instalador_usuario).
     */
    public function pull(SyncPullRequest $request): JsonResponse
    {
        $usuario = $request->user()->usuario;
        $sinceRaw = $request->input('since');
        // Carbon::parse respeta 'Z' como UTC. Comparar strings con 'Z' contra
        // TIMESTAMP en MySQL/MariaDB es dependiente de la zona de sesión.
        // Convertir a la zona de la app (NO UTC). Los updated_at se guardan
        // con toDateTimeString() en app.timezone; la comparación directa
        // contra UTC produce 0 filas por drift de 6h. Ver Q-145.
        $since    = $sinceRaw ? \Carbon\Carbon::parse($sinceRaw)->setTimezone(config('app.timezone')) : null;

        $instalacionesQuery = Instalacion::query()->porInstalador($usuario);
        if ($since) {
            $instalacionesQuery->where('updated_at', '>=', $since);
        }
        $instalaciones  = $instalacionesQuery->get();
        $instalacionIds = $instalaciones->pluck('id');

        $eventosQuery = InstalacionEvento::query()->whereIn('instalacion_id', $instalacionIds);
        if ($since) {
            $eventosQuery->where('updated_at', '>=', $since);
        }
        $eventos = $eventosQuery->get();

        $fotosQuery = InstalacionFoto::query()->whereIn('instalacion_id', $instalacionIds);
        if ($since) {
            $fotosQuery->where('updated_at', '>=', $since);
        }
        $fotos = $fotosQuery->get();

        $catalogos = [
            'proyectos'  => $this->pullCatalog(Proyecto::class,   $since),
            'clientes'   => $this->pullCatalog(Cliente::class,    $since),
            'categorias' => $this->pullCatalog(Categoria::class,  $since),
            'inventario' => $this->pullCatalog(Inventario::class, $since),
            'estatus'    => $this->pullCatalog(Estatus::class,    $since),
        ];

        $maxUpdatedAt = collect()
            ->merge($instalaciones->pluck('updated_at'))
            ->merge($eventos->pluck('updated_at'))
            ->merge($fotos->pluck('updated_at'))
            ->merge(collect($catalogos)->flatMap(fn ($rows) => $rows->pluck('updated_at')))
            ->filter()
            ->max();

        $nextSince = $maxUpdatedAt
            ? \Carbon\Carbon::parse($maxUpdatedAt)->utc()->format('Y-m-d\TH:i:s\Z')
            : $since;

        return response()->json([
            'since'               => $sinceRaw,
            'next_since'          => $nextSince,
            'instalaciones'       => $instalaciones,
            'instalacion_eventos' => $eventos,
            'instalacion_fotos'   => $fotos,
            'salidas'             => [],
            'evidencias_entrega'  => [],
            'catalogos'           => $catalogos,
        ]);
    }

    private function pullCatalog(string $modelClass, ?string $since)
    {
        /** @var Model $model */
        $model = new $modelClass();
        $query = $modelClass::query();

        if ($since && Schema::hasColumn($model->getTable(), 'updated_at')) {
            $query->where('updated_at', '>=', $since);
        }

        return $query->get();
    }
}

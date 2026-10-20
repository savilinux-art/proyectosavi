<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstalacionEventoRequest;
use App\Models\Instalacion;
use App\Models\InstalacionEvento;
use App\Models\InstalacionFoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InstalacionEventoController extends Controller
{
    public function iniciar(InstalacionEventoRequest $request, Instalacion $instalacion): JsonResponse
    {
        $this->autorizar($request, $instalacion);

        if (!in_array($instalacion->estatus_instalacion, ['preparacion', 'programacion', 'Programada', 'en_proceso'], true)) {
            return $this->rechazo('No se puede iniciar: estatus actual ' . $instalacion->estatus_instalacion);
        }

        $inicioPrevio = $instalacion->fecha_hora_inicio;
        $payload = [];
        if ($inicioPrevio) {
            $payload['inicio_previo'] = Carbon::parse($inicioPrevio)->toIso8601String();
        }

        $capturadoAt = Carbon::parse($request->input('capturado_at'));

        DB::transaction(function () use ($instalacion, $capturadoAt, $payload, $request) {
            $instalacion->fecha_hora_inicio   = $capturadoAt;
            $instalacion->estatus_instalacion = 'en_proceso';
            $instalacion->save();

            $this->crearEvento($instalacion, 'iniciada', $request, $payload);
            $this->guardarFotoSiViene($instalacion, $request, 'inicio');
        });

        return $this->respuesta($instalacion, 'Evento iniciada registrado');
    }

    public function pausar(InstalacionEventoRequest $request, Instalacion $instalacion): JsonResponse
    {
        $this->autorizar($request, $instalacion);

        if ($instalacion->estatus_instalacion !== 'en_proceso') {
            return $this->rechazo('Solo se puede pausar una instalación en proceso');
        }

        $ultimo = $this->ultimoEvento($instalacion);
        if ($ultimo && $ultimo->tipo === 'pausada') {
            return $this->rechazo('La instalación ya está pausada');
        }

        DB::transaction(function () use ($instalacion, $request) {
            $payload = [];
            if ($request->filled('notas')) {
                $payload['motivo'] = $request->input('notas');
            }
            $this->crearEvento($instalacion, 'pausada', $request, $payload);
            $this->guardarFotoSiViene($instalacion, $request, 'proceso');
        });

        return $this->respuesta($instalacion, 'Evento pausada registrado');
    }

    public function reanudar(InstalacionEventoRequest $request, Instalacion $instalacion): JsonResponse
    {
        $this->autorizar($request, $instalacion);

        if ($instalacion->estatus_instalacion !== 'en_proceso') {
            return $this->rechazo('Solo se puede reanudar una instalación en proceso');
        }

        $ultimo = $this->ultimoEvento($instalacion);
        if (!$ultimo || $ultimo->tipo !== 'pausada') {
            return $this->rechazo('La instalación no está pausada; no hay nada que reanudar');
        }

        DB::transaction(function () use ($instalacion, $request) {
            $this->crearEvento($instalacion, 'reanudada', $request, []);
            $this->guardarFotoSiViene($instalacion, $request, 'proceso');
        });

        return $this->respuesta($instalacion, 'Evento reanudada registrado');
    }

    public function finalizar(InstalacionEventoRequest $request, Instalacion $instalacion): JsonResponse
    {
        $this->autorizar($request, $instalacion);

        if ($instalacion->estatus_instalacion !== 'en_proceso') {
            return $this->rechazo('Solo se puede finalizar una instalación en proceso');
        }

        $capturadoAt = Carbon::parse($request->input('capturado_at'));

        DB::transaction(function () use ($instalacion, $capturadoAt, $request) {
            $instalacion->fecha_hora_fin      = $capturadoAt;
            $instalacion->estatus_instalacion = 'terminada';
            $instalacion->save();

            $this->crearEvento($instalacion, 'finalizada', $request, []);
            $this->guardarFotoSiViene($instalacion, $request, 'fin');
        });

        return $this->respuesta($instalacion, 'Evento finalizada registrado');
    }

    private function autorizar(InstalacionEventoRequest $request, Instalacion $instalacion): void
    {
        $usuario = $request->user()->usuario;
        $asignado = DB::table('instalacion_instalador')
            ->where('instalacion_id', $instalacion->id)
            ->where('instalador_usuario', $usuario)
            ->exists();

        if (!$asignado) {
            abort(403, 'No estás asignado a esta instalación');
        }
    }

    private function crearEvento(Instalacion $instalacion, string $tipo, InstalacionEventoRequest $request, array $payload): InstalacionEvento
    {
        $capturadoAt = Carbon::parse($request->input('capturado_at'));

        if ($capturadoAt->gt(now()->addMinutes(5))) {
            abort(422, 'capturado_at está en el futuro (reloj del dispositivo desviado)');
        }

        return InstalacionEvento::create([
            'instalacion_id'   => $instalacion->id,
            'tipo'             => $tipo,
            'latitud'          => $request->input('latitud'),
            'longitud'         => $request->input('longitud'),
            'precision_metros' => $request->input('precision_metros'),
            'fuente'           => $request->input('fuente'),
            'payload_json'     => $payload ?: null,
            'capturado_at'     => $capturadoAt,
            'por'              => $request->user()->usuario,
        ]);
    }

    private function guardarFotoSiViene(Instalacion $instalacion, InstalacionEventoRequest $request, string $tipoFoto): void
    {
        if (!$request->hasFile('foto')) {
            return;
        }

        $archivo = $request->file('foto');
        $ext     = $archivo->getClientOriginalExtension() ?: 'jpg';
        $path    = $archivo->storeAs(
            "instalaciones/{$instalacion->id}",
            "{$tipoFoto}_" . now()->format('YmdHis') . ".{$ext}",
            'local'
        );

        InstalacionFoto::create([
            'instalacion_id'     => $instalacion->id,
            'ruta'               => $path,
            'nombre_original'    => $archivo->getClientOriginalName(),
            'mime'               => $archivo->getMimeType(),
            'tamano_kb'          => (int) ceil($archivo->getSize() / 1024),
            'tipo'               => $tipoFoto,
            'descripcion'        => null,
            'subida_por_usuario' => $request->user()->usuario,
            'latitud'            => $request->input('latitud'),
            'longitud'           => $request->input('longitud'),
            'precision_metros'   => $request->input('precision_metros'),
            'fuente'             => $request->input('fuente'),
        ]);
    }

    private function ultimoEvento(Instalacion $instalacion): ?InstalacionEvento
    {
        return InstalacionEvento::where('instalacion_id', $instalacion->id)
            ->orderByDesc('capturado_at')
            ->orderByDesc('id')
            ->first();
    }

    private function respuesta(Instalacion $instalacion, string $mensaje): JsonResponse
    {
        return response()->json([
            'message'     => $mensaje,
            'instalacion' => [
                'id'                  => $instalacion->id,
                'estatus_instalacion' => $instalacion->estatus_instalacion,
                'fecha_hora_inicio'   => $instalacion->fecha_hora_inicio,
                'fecha_hora_fin'      => $instalacion->fecha_hora_fin,
            ],
        ]);
    }

    private function rechazo(string $mensaje): JsonResponse
    {
        return response()->json(['message' => $mensaje], 422);
    }
}

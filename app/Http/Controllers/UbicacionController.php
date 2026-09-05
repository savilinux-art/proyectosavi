<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use MrWolfGb\Traccar\TraccarService;

class UbicacionController extends Controller
{
    protected $traccar;

    public function __construct(TraccarService $traccar)
    {
        $this->traccar = $traccar;
    }

    /**
     * Muestra el mapa con las ubicaciones de todos los instaladores
     */
    public function index()
    {
        return view('ubicaciones.index');
    }

    /**
     * Devuelve las ubicaciones en tiempo real en formato JSON
     */
    public function getUbicaciones()
    {
        $usuarios = Usuario::whereNotNull('traccar_device_id')->get();
        $ubicaciones = [];

        foreach ($usuarios as $usuario) {
            try {
                // Obtener la última posición del dispositivo
                $posicion = $this->traccar->positions()->latest($usuario->traccar_device_id);
                
                if ($posicion) {
                    $ubicaciones[] = [
                        'id' => $usuario->id,
                        'nombre' => $usuario->nombre,
                        'usuario' => $usuario->usuario,
                        'lat' => $posicion->latitude,
                        'lng' => $posicion->longitude,
                        'velocidad' => $posicion->speed ?? 0,
                        'fecha' => $posicion->deviceTime ?? now(),
                    ];
                }
            } catch (\Exception $e) {
                // Si el dispositivo no existe o hay error, lo ignoramos
                continue;
            }
        }

        return response()->json($ubicaciones);
    }

    /**
     * Obtiene la ubicación de un instalador específico
     */
    public function getUbicacion($userId)
    {
        $usuario = Usuario::findOrFail($userId);
        
        if (!$usuario->traccar_device_id) {
            return response()->json(['error' => 'Usuario no tiene dispositivo Traccar'], 404);
        }

        try {
            $posicion = $this->traccar->positions()->latest($usuario->traccar_device_id);
            return response()->json([
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'lat' => $posicion->latitude,
                'lng' => $posicion->longitude,
                'velocidad' => $posicion->speed ?? 0,
                'fecha' => $posicion->deviceTime ?? now(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo obtener la ubicación'], 404);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\TraccarService;  // ← Tu servicio personalizado
use Illuminate\Http\Request;

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
                // Usa el método de TU servicio
                $posicion = $this->traccar->getLatestPosition($usuario->traccar_device_id);
                
                if ($posicion && isset($posicion['latitude'])) {
                    $ubicaciones[] = [
                        'id' => $usuario->id,
                        'nombre' => $usuario->nombre,
                        'usuario' => $usuario->usuario,
                        'lat' => $posicion['latitude'],
                        'lng' => $posicion['longitude'],
                        'velocidad' => $posicion['speed'] ?? 0,
                        'fecha' => $posicion['deviceTime'] ?? now(),
                        'device_id' => $usuario->traccar_device_id,
                    ];
                }
            } catch (\Exception $e) {
                // Si el dispositivo no existe o hay error, lo ignoramos
                continue;
            }
        }

        // Si no hay ubicaciones reales, devolver datos de prueba
        if (empty($ubicaciones)) {
            return response()->json([
                [
                    'id' => 999,
                    'nombre' => 'Dispositivo de Prueba',
                    'usuario' => 'test',
                    'lat' => 20.6597,
                    'lng' => -105.2252,
                    'velocidad' => 0,
                    'fecha' => now(),
                    'device_id' => 'TEST123'
                ]
            ]);
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
            $posicion = $this->traccar->getLatestPosition($usuario->traccar_device_id);
            
            if (!$posicion || !isset($posicion['latitude'])) {
                return response()->json(['error' => 'No se encontró posición'], 404);
            }

            return response()->json([
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'lat' => $posicion['latitude'],
                'lng' => $posicion['longitude'],
                'velocidad' => $posicion['speed'] ?? 0,
                'fecha' => $posicion['deviceTime'] ?? now(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo obtener la ubicación'], 500);
        }
    }
}
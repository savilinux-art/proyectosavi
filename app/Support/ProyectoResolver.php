<?php

namespace App\Support;

use App\Exceptions\ProyectoNoEncontradoException;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Log;

class ProyectoResolver
{
    /**
     * Resuelve un Proyecto por nombre con trim + case-insensitive.
     *
     * REGLA DE NEGOCIO: NUNCA crea. Solo lee. Los proyectos los crean
     * Admin o Ventas desde la web. Si no resuelve → 422 al móvil.
     *
     * - Nombre vacío o solo espacios → ProyectoNoEncontradoException
     * - Sin coincidencias              → Log::warning + ProyectoNoEncontradoException (HTTP 422)
     * - Múltiples coincidencias       → Log::warning + devuelve el más reciente (id DESC)
     *
     * @throws ProyectoNoEncontradoException
     */
    public static function resolver(string $nombre): Proyecto
    {
        $nombreLimpio = trim($nombre);

        if ($nombreLimpio === '') {
            Log::warning('ProyectoResolver: nombre vacío', ['nombre_original' => $nombre]);
            throw new ProyectoNoEncontradoException($nombre);
        }

        // LOWER(TRIM(...)) no usa índice, pero la tabla es pequeña (11 filas en prod).
        // Si en F2 crece, considerar columna generada o índice funcional.
        $candidatos = Proyecto::query()
            ->whereRaw('LOWER(TRIM(nombre_proyecto)) = ?', [mb_strtolower($nombreLimpio)])
            ->orderByDesc('id')
            ->get();

        if ($candidatos->isEmpty()) {
            Log::warning('ProyectoResolver: proyecto no encontrado', [
                'nombre_buscado' => $nombre,
                'nombre_limpio'  => $nombreLimpio,
            ]);
            throw new ProyectoNoEncontradoException($nombre);
        }

        if ($candidatos->count() > 1) {
            Log::warning('ProyectoResolver: múltiples proyectos con el mismo nombre, se elige el más reciente', [
                'nombre_buscado' => $nombre,
                'ids'            => $candidatos->pluck('id')->all(),
                'elegido_id'     => $candidatos->first()->id,
            ]);
        }

        return $candidatos->first();
    }
}
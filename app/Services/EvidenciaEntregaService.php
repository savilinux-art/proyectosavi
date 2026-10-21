<?php

namespace App\Services;

use App\Models\EvidenciaEntrega;
use App\Models\SalidaInventario;
use App\Support\ProyectoResolver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EvidenciaEntregaService
{
    /**
     * Guarda evidencia, invalidando las previas vigentes del mismo tipo.
     * Lógica extraída de B-08 (SalidaApiController::uploadEvidenciaEntrega).
     * Usado por B-05 (web). B-08 conserva su copia inline; Q-131 unificará.
     */
    public function guardar(
        SalidaInventario $salida,
        UploadedFile $archivo,
        string $tipo,
        string $subidoPor,
        ?string $notas = null
    ): EvidenciaEntrega {
        $mime  = $archivo->getMimeType() ?: 'application/octet-stream';
        $esPdf = $archivo->getClientOriginalExtension() === 'pdf' || $mime === 'application/pdf';

        if ($esPdf) {
            $this->validarMagicBytesPdf($archivo);
        }

        $proyecto = ProyectoResolver::resolver($salida->nombre_proyecto);

        $this->invalidarPrevias($salida, $tipo, $subidoPor);

        $extension = $archivo->getClientOriginalExtension() ?: ($archivo->extension() ?: 'bin');
        $nombre    = Str::uuid()->toString() . '.' . strtolower($extension);
        $path      = $archivo->storeAs("evidencias_entrega/{$salida->id}", $nombre, 'local');

        return EvidenciaEntrega::create([
            'salida_id'               => $salida->id,
            'proyecto_id'             => $proyecto->id,
            'archivo_path'            => $path,
            'archivo_nombre_original' => $archivo->getClientOriginalName(),
            'archivo_mime'            => $mime,
            'archivo_tamano'          => $archivo->getSize(),
            'tipo'                    => $tipo,
            'estado'                  => 'vigente',
            'notas'                   => $notas,
            'subido_por'              => $subidoPor,
        ]);
    }

    private function validarMagicBytesPdf(UploadedFile $archivo): void
    {
        $realPath = $archivo->getRealPath();
        $handle   = $realPath ? fopen($realPath, 'rb') : false;
        $header   = $handle ? (string) fread($handle, 1024) : '';
        if ($handle) {
            fclose($handle);
        }
        if (strpos($header, '%PDF') === false) {
            throw ValidationException::withMessages([
                'archivo' => ['El PDF no es válido (no contiene la cabecera %PDF).'],
            ]);
        }
    }

    private function invalidarPrevias(SalidaInventario $salida, string $tipo, string $por): void
    {
        EvidenciaEntrega::query()
            ->where('salida_id', $salida->id)
            ->where('tipo', $tipo)
            ->where('estado', 'vigente')
            ->update([
                'estado'            => 'invalidada_por_edicion',
                'invalidada_at'     => now(),
                'invalidada_por'    => $por,
                'invalidada_motivo' => "Reemplazada por nueva subida de tipo {$tipo}",
            ]);
    }
}

<?php

namespace App\Services;

use App\Models\Instalacion;
use App\Models\InstalacionFoto;
use App\Models\Usuario;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class InstalacionFotoService
{
    /** Ajustar al mismo disco que use EvidenciaEntregaService (grep 'Storage::disk' app/Services/EvidenciaEntregaService.php). */
    public const DISCO = 'local';

    /** Límite duro del servicio (la validación del Request es la primera línea de defensa). */
    public const MAX_KB = 10240;

    /** MIMEs aceptados en el almacenamiento. */
    public const MIMES = ['image/jpeg', 'image/png'];

    /**
     * Guarda UNA foto en Storage y registra la fila en instalacion_fotos.
     */
    public function guardar(
        Instalacion $instalacion,
        UploadedFile $archivo,
        string $tipo,
        ?Usuario $subidaPor = null,
        ?string $descripcion = null,
        ?float $latitud = null,
        ?float $longitud = null,
        ?int $precisionMetros = null,
        ?string $fuente = null,
    ): InstalacionFoto {
        $this->assertArchivoValido($archivo);

        $ruta = $this->almacenarEnDisk($instalacion, $archivo, $tipo);

        return InstalacionFoto::create([
            'instalacion_id'     => $instalacion->id,
            'ruta'               => $ruta,
            'nombre_original'    => $archivo->getClientOriginalName(),
            'mime'               => $archivo->getMimeType(),
            'tamano_kb'          => (int) ceil($archivo->getSize() / 1024),
            'tipo'               => $tipo,
            'descripcion'        => $descripcion,
            'subida_por_usuario' => $subidaPor?->usuario,
            'latitud'            => $latitud,
            'longitud'           => $longitud,
            'precision_metros'   => $precisionMetros,
            'fuente'             => $fuente,
        ]);
    }

    /**
     * Guarda N fotos en una única transacción.
     *
     * @param  array<int, array{archivo: UploadedFile, tipo: string, descripcion?: ?string}>  $fotos
     * @return array<int, InstalacionFoto>
     */
    public function guardarBatch(
        Instalacion $instalacion,
        array $fotos,
       ?Usuario $subidaPor = null,
        ?float $latitud = null,
        ?float $longitud = null,
        ?int $precisionMetros = null,
        ?string $fuente = null,
    ): array {
        return DB::transaction(function () use ($instalacion, $fotos, $subidaPor, $latitud, $longitud, $precisionMetros, $fuente) {
            $creadas = [];
            foreach ($fotos as $foto) {
                $creadas[] = $this->guardar(
                    $instalacion,
                    $foto['archivo'],
                    $foto['tipo'],
                    $subidaPor,
                    $foto['descripcion'] ?? null,
                    $latitud,
                    $longitud,
                    $precisionMetros,
                    $fuente,
                );
            }
            return $creadas;
        });
    }

    /** URL temporal firmada para descargar la foto (paralelo a B-06). */
    public function urlTemporal(InstalacionFoto $foto, int $minutos = 10): string
    {
        return Storage::disk(self::DISCO)->temporaryUrl(
            $foto->ruta,
            now()->addMinutes($minutos)
        );
    }

    /** Elimina el blob de Storage. El borrado del modelo es responsabilidad del caller. */
    public function eliminarBlob(InstalacionFoto $foto): void
    {
        Storage::disk(self::DISCO)->delete($foto->ruta);
    }

    // =========================================================================
    // MÉTODOS PROTEGIDOS
    // =========================================================================

    /**
     * Defensa en profundidad: valida MIME y tamaño aunque el Request ya lo haya hecho.
     * Necesario porque B-16/B-17 (sync) pueden llamar al servicio desde jobs en cola
     * sin pasar por un FormRequest HTTP.
     */
    protected function assertArchivoValido(UploadedFile $archivo): void
    {
        if (!in_array($archivo->getMimeType(), self::MIMES, true)) {
            throw new \InvalidArgumentException(
                'MIME no permitido: ' . $archivo->getMimeType()
        );
        }
        if (ceil($archivo->getSize() / 1024) > self::MAX_KB) {
            throw new \InvalidArgumentException(
                'Archivo excede ' . self::MAX_KB . ' KB.'
            );
        }
    }

    protected function almacenarEnDisk(Instalacion $instalacion, UploadedFile $archivo, string $tipo): string
    {
        $ext = strtolower(
            $archivo->getClientOriginalExtension()
            ?: $archivo->extension()
            ?: 'jpg'
        );

        $ruta = sprintf(
            'instalaciones/%d/%s/%s-%s.%s',
            $instalacion->id,
            $tipo,
            now()->format('Ymd-His'),
            Str::uuid()->toString(),
            $ext
        );

        Storage::disk(self::DISCO)->putFileAs(
            dirname($ruta),
            $archivo,
            basename($ruta),
            ['visibility' => 'private']
        );

        return $ruta;
    }
}
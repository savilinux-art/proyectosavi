<?php

namespace Tests\Feature;

use App\Models\EvidenciaEntrega;
use App\Models\Proyecto;
use App\Models\SalidaInventario;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SalidaDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        // B-06: aislamos la lógica de downloadPDF de la middleware
        // auth.session/permiso:ver-salidas (que requiere permiso_rol sembrado).
        $this->withoutMiddleware();
    }

    /**
     * Patrón copiado de SalidaEvidenciaTest::crearSalidaConProyecto.
     */
    private function crearSalidaConProyecto(Usuario $receptor): SalidaInventario
    {
        $venta = Venta::factory()->create();
        Proyecto::factory()->create(['nombre_proyecto' => $venta->nombre_proyecto]);

        return SalidaInventario::forceCreate([
            'nombre_proyecto'   => $venta->nombre_proyecto,
            'entregado_por'     => $venta->vendedor,
            'entregado_a'       => $receptor->usuario,
            'fecha_hora_salida' => now(),
            'observaciones'     => 'test',
        ]);
    }

    /**
     * Crea una evidencia vigente (por defecto) con archivo en el disco fake.
     */
    private function crearEvidencia(
        SalidaInventario $salida,
        string $tipo,
        string $contenido,
        string $mime,
        string $estado = 'vigente'
    ): EvidenciaEntrega {
        $proyecto  = Proyecto::where('nombre_proyecto', $salida->nombre_proyecto)->first();
        $extension = $tipo === 'pdf_cliente_firmado' ? 'pdf' : 'jpg';
        $path      = "evidencias_entrega/{$salida->id}/" . uniqid() . '.' . $extension;

        Storage::disk('local')->put($path, $contenido);

        return EvidenciaEntrega::create([
            'salida_id'               => $salida->id,
            'proyecto_id'             => $proyecto?->id,
            'archivo_path'            => $path,
            'archivo_nombre_original' => basename($path),
            'archivo_mime'            => $mime,
            'archivo_tamano'          => strlen($contenido),
            'tipo'                    => $tipo,
            'estado'                  => $estado,
            'subido_por'              => 'tester',
        ]);
    }

    public function test_sin_evidencia_no_sirve_imagen_de_firma(): void
    {
        $user   = Usuario::factory()->instalador()->create();
        $salida = $this->crearSalidaConProyecto($user);

        $response = $this->get("/salidas/{$salida->id}/download");

        // Sin evidencia → NO debe servir imagen (cae al PDF generado)
        $this->assertNotSame(
            'image/jpeg',
            $response->headers->get('Content-Type'),
            'Sin evidencia vigente no debe servirse una imagen'
        );
    }

    public function test_con_firma_cliente_vigente_sirve_la_foto(): void
    {
        $user   = Usuario::factory()->instalador()->create();
        $salida = $this->crearSalidaConProyecto($user);

        $imgContent = UploadedFile::fake()->image('firma.jpg', 600, 400)->getContent();
        
        $this->crearEvidencia($salida, 'firma_cliente', $imgContent, 'image/jpeg');

        $response = $this->get("/salidas/{$salida->id}/download");

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with(
                (string) $response->headers->get('Content-Type'),
                'image/'
            ),
            'Con firma_cliente vigente debe servirse la imagen'
        );
    }

    public function test_con_pdf_firmado_vigente_sirve_el_pdf_firmado(): void
    {
        $user   = Usuario::factory()->instalador()->create();
        $salida = $this->crearSalidaConProyecto($user);

        $pdfContent = "%PDF-1.4\nfake pdf content\n%%EOF";
        $this->crearEvidencia($salida, 'pdf_cliente_firmado', $pdfContent, 'application/pdf');

        $response = $this->get("/salidas/{$salida->id}/download");

        $response->assertStatus(200);
        $this->assertStringContainsString(
            'application/pdf',
            (string) $response->headers->get('Content-Type')
        );

        // Distinguir el PDF firmado del generado por Dompdf
        $body = $response->streamedContent();
        $this->assertStringContainsString(
            'fake pdf content',
            $body,
            'Debe servirse el PDF firmado (contiene marcador), no el generado'
        );
    }

    public function test_con_ambos_vigentes_prioriza_pdf_firmado(): void
    {
        $user   = Usuario::factory()->instalador()->create();
        $salida = $this->crearSalidaConProyecto($user);

        $imgContent = UploadedFile::fake()->image('firma.jpg', 600, 400)->getContent();
        
        $pdfContent = "%PDF-1.4\nprioritized pdf\n%%EOF";

        $this->crearEvidencia($salida, 'firma_cliente', $imgContent, 'image/jpeg');
        $this->crearEvidencia($salida, 'pdf_cliente_firmado', $pdfContent, 'application/pdf');

        $response = $this->get("/salidas/{$salida->id}/download");

        $response->assertStatus(200);
        $this->assertStringContainsString(
            'application/pdf',
            (string) $response->headers->get('Content-Type')
        );

        $body = $response->streamedContent();
        $this->assertStringContainsString(
            'prioritized pdf',
            $body,
            'Con ambas vigentes, debe priorizar pdf_cliente_firmado sobre firma_cliente'
        );
    }

    public function test_firma_invalidada_no_se_sirve(): void
    {
        $user   = Usuario::factory()->instalador()->create();
        $salida = $this->crearSalidaConProyecto($user);

        $imgContent = UploadedFile::fake()->image('firma.jpg', 600, 400)->getContent();
        $this->crearEvidencia(
            $salida,
            'firma_cliente',
            $imgContent,
            'image/jpeg',
            'invalidada_por_edicion'
        );

        $response = $this->get("/salidas/{$salida->id}/download");

        // scopeVigente() la excluye → cae al PDF generado, NO sirve la imagen
        $this->assertNotSame(
            'image/jpeg',
            $response->headers->get('Content-Type'),
            'Una firma invalidada no debe servirse'
        );
    }

    public function test_copia_cliente_ignora_evidencia_y_genera_pdf(): void
    {
        $user   = Usuario::factory()->instalador()->create();
        $salida = $this->crearSalidaConProyecto($user);

        $imgContent = UploadedFile::fake()->image('firma.jpg', 600, 400)->getContent();

        $this->crearEvidencia($salida, 'firma_cliente', $imgContent, 'image/jpeg');

        // Con copia=cliente, la rama de evidencia se salta (solo aplica $copia === null)
        $response = $this->get("/salidas/{$salida->id}/download/cliente");

        $this->assertNotSame(
            'image/jpeg',
            $response->headers->get('Content-Type'),
            'copia=cliente debe generar PDF, no servir la evidencia'
        );
    }
}

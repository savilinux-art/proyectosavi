<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VentaDocumentosTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $vendedor;

  protected function setUp(): void
{
    parent::setUp();

    $u = new Usuario();
    $u->usuario    = 'test_vendedor';
    $u->nombre     = 'Test Vendedor';
    $u->correo     = 'test_vendedor@example.test';
    $u->contraseña = bcrypt('secret');
    $u->rol        = 'Ventas';
    $u->save();

    $this->vendedor = $u;
}

       /**
     * Arma una request autenticada vía sesión (el proyecto NO usa Auth::login).
     * PermisoMiddleware se bypasea aquí: su cobertura pertenece a Fase 1 de tests
     * (deuda anotada como Q-NEW en v31). El objetivo de este archivo es validar
     * el flujo de Storage::disk('local'), no permisos.
     */
    private function comoVendedor(): static
    {
        return $this->withoutMiddleware(\App\Http\Middleware\PermisoMiddleware::class)
            ->withSession([
                'user_usuario' => 'test_vendedor',
                'user_rol'     => 'Administrador',
            ]);
    }

    private function ventaBase(array $overrides = []): Venta
    {
        return Venta::create(array_merge([
            'nombre_proyecto'          => 'proyecto-test-' . uniqid(),
            'moneda'                   => 'MXN',
            'requerimiento_venta'      => 'req',
            'fecha_hora_levantamiento' => now(),
            'vendedor'                 => 'test_vendedor',
            'venta_ganada'             => 0,
            'estatus'                  => 'prospeccion',
        ], $overrides));
    }

    #[Test]
    public function store_guarda_pdf_en_disco_y_no_en_blob(): void
    {
        Storage::fake('local');

        $pdf = UploadedFile::fake()->createWithContent(
            'cotizacion.pdf',
            "%PDF-1.4\n%fake\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF"
        );

        $this->comoVendedor()
            ->post(route('ventas.store'), [
                'nombre_proyecto'          => 'test-store-' . uniqid(),
                'moneda'                   => 'MXN',
                'requerimiento_venta'      => 'req',
                'fecha_hora_levantamiento' => now()->format('Y-m-d H:i:s'),
                'venta_ganada'             => 0,
                'estatus'                  => 'prospeccion',
                'cotizacion'               => $pdf,
            ])
            ->assertRedirect(route('ventas.index'));

        $venta = Venta::latest('id')->first();

        $this->assertNotNull($venta->cotizacion_path, 'cotizacion_path debe estar seteado');
        Storage::disk('local')->assertExists($venta->cotizacion_path);
        $this->assertNull($venta->cotizacion, 'El blob legacy no debe usarse');
    }

    #[Test]
    public function descargar_documento_sirve_pdf_desde_disco(): void
    {
        Storage::fake('local');

        $venta = $this->ventaBase();
        $contenido = "%PDF-1.4\nfake content";
        $path = "ventas/{$venta->id}/cotizacion.pdf";

        Storage::disk('local')->put($path, $contenido);
        $venta->update(['cotizacion_path' => $path]);

        $response = $this->comoVendedor()
            ->get(route('ventas.documento', [$venta->id, 'cotizacion']));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString(
            'inline',
            $response->headers->get('Content-Disposition') ?? ''
        );
    }

    #[Test]
    public function descargar_documento_404_si_no_existe(): void
    {
        Storage::fake('local');

        $venta = $this->ventaBase();

        $this->comoVendedor()
            ->get(route('ventas.documento', [$venta->id, 'cotizacion']))
            ->assertNotFound();
    }
}
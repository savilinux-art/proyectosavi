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

class SalidaUploadWebTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->withoutMiddleware();
    }

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

    public function test_upload_web_exitoso(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst50']);
        $salida = $this->crearSalidaConProyecto($user);

        $response = $this->actingAs($user)->post(
            route('salidas.evidencias.store', $salida->id),
            [
                'archivo' => UploadedFile::fake()->image('firma.jpg'),
                'tipo'    => 'firma_cliente',
            ]
        );

        $response->assertRedirect(route('salidas.show', $salida->id));
        $response->assertSessionHas('success');

        $ev = EvidenciaEntrega::first();
        $this->assertNotNull($ev);
        $this->assertSame('vigente', $ev->estado);
        $this->assertSame('inst50', $ev->subido_por);
        Storage::disk('local')->assertExists($ev->archivo_path);
    }

    public function test_upload_web_otro_usuario_da_403(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst51']);
        $otro   = Usuario::factory()->instalador()->create(['usuario' => 'inst52']);
        $salida = $this->crearSalidaConProyecto($otro);

        $this->actingAs($user)
            ->post(route('salidas.evidencias.store', $salida->id), [
                'archivo' => UploadedFile::fake()->image('firma.jpg'),
            ])
            ->assertForbidden();

        $this->assertSame(0, EvidenciaEntrega::count());
    }

    public function test_upload_web_mime_invalido_da_422(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst53']);
        $salida = $this->crearSalidaConProyecto($user);

        // withoutMiddleware() deshabilita ShareErrorsFromSession, así que
        // atrapamos la ValidationException directamente en vez de leer sesión.
        $this->withoutExceptionHandling();

        try {
            $this->actingAs($user)->post(
                route('salidas.evidencias.store', $salida->id),
                [
                    'archivo' => UploadedFile::fake()->create('script.exe', 100, 'application/x-msdownload'),
                ]
            );
            $this->fail('Se esperaba ValidationException por mime inválido');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('archivo', $e->errors(), 'Debe fallar la regla mimes');
        }

        $this->assertSame(0, EvidenciaEntrega::count(), 'No debe crear evidencia con mime inválido');
    }

    public function test_upload_web_tipo_invalido_da_422(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst54']);
        $salida = $this->crearSalidaConProyecto($user);

        $this->withoutExceptionHandling();

        try {
            $this->actingAs($user)->post(
                route('salidas.evidencias.store', $salida->id),
                [
                    'archivo' => UploadedFile::fake()->image('firma.jpg'),
                    'tipo'    => 'no_existe',
                ]
            );
            $this->fail('Se esperaba ValidationException por tipo inválido');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('tipo', $e->errors(), 'Debe fallar la regla in:');
        }

        $this->assertSame(0, EvidenciaEntrega::count(), 'No debe crear evidencia con tipo inválido');
    }
}

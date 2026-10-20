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
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SalidaEvidenciaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /**
     * Crea una salida donde $receptor es el entregado_a, con Venta + Proyecto
     * válidos (el resolver busca en Proyecto, no en Venta).
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

    public function test_subir_firma_cliente_exitosa(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst10']);
        $salida = $this->crearSalidaConProyecto($user);
        $file   = UploadedFile::fake()->image('firma.jpg', 600, 400);

        Sanctum::actingAs($user);

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => $file,
            'tipo'    => 'firma_cliente',
            'notas'   => 'Firmada en sitio',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('evidencia.tipo', 'firma_cliente')
            ->assertJsonPath('evidencia.estado', 'vigente')
            ->assertJsonPath('evidencia.mime', 'image/jpeg');

        $evidencia = EvidenciaEntrega::first();
        $this->assertNotNull($evidencia);
        $this->assertSame('vigente', $evidencia->estado);
        $this->assertSame('inst10', $evidencia->subido_por);
        Storage::disk('local')->assertExists($evidencia->archivo_path);
    }

    public function test_subir_segunda_firma_invalida_primera(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst11']);
        $salida = $this->crearSalidaConProyecto($user);

        Sanctum::actingAs($user);

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => UploadedFile::fake()->image('v1.jpg'),
            'tipo'    => 'firma_cliente',
        ])->assertCreated();

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => UploadedFile::fake()->image('v2.jpg'),
            'tipo'    => 'firma_cliente',
        ])->assertCreated();

        $evidencias = EvidenciaEntrega::orderBy('id')->get();
        $this->assertCount(2, $evidencias);
        $this->assertSame('invalidada_por_edicion', $evidencias[0]->estado);
        $this->assertNotNull($evidencias[0]->invalidada_at);
        $this->assertSame('inst11', $evidencias[0]->invalidada_por);
        $this->assertSame('vigente', $evidencias[1]->estado);
    }

    public function test_salida_de_otro_usuario_da_404(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst12']);
        $otro   = Usuario::factory()->instalador()->create(['usuario' => 'inst13']);
        $salida = $this->crearSalidaConProyecto($otro);

        Sanctum::actingAs($user);

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => UploadedFile::fake()->image('firma.jpg'),
        ])->assertNotFound();
    }

    public function test_sin_archivo_da_422(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst14']);
        $salida = $this->crearSalidaConProyecto($user);

        Sanctum::actingAs($user);

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'tipo' => 'firma_cliente',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['archivo']);
    }

    public function test_archivo_mayor_10mb_da_422(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst15']);
        $salida = $this->crearSalidaConProyecto($user);

        Sanctum::actingAs($user);

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => UploadedFile::fake()->create('big.pdf', 11000, 'application/pdf'),
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['archivo']);
    }

    public function test_pdf_sin_magic_bytes_da_422(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst16']);
        $salida = $this->crearSalidaConProyecto($user);

        Sanctum::actingAs($user);

        // Mime application/pdf declarado, pero contenido es basura (sin %PDF)
        $fakePdf = UploadedFile::fake()->create('fake.pdf', 100, 'application/pdf');

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => $fakePdf,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['archivo']);
    }

    public function test_proyecto_inexistente_da_422_con_accion_sugerida(): void
    {
        $user = Usuario::factory()->instalador()->create(['usuario' => 'inst17']);

        // Salida cuyo nombre_proyecto NO tiene Proyecto asociado
        $venta = Venta::factory()->create();
        $salida = SalidaInventario::forceCreate([
            'nombre_proyecto'   => $venta->nombre_proyecto,
            'entregado_por'     => $venta->vendedor,
            'entregado_a'       => $user->usuario,
            'fecha_hora_salida' => now(),
            'observaciones'     => 'test',
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => UploadedFile::fake()->image('firma.jpg'),
        ])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('accion_sugerida', 'contactar_administrador');
    }

    public function test_tipo_invalido_da_422(): void
    {
        $user   = Usuario::factory()->instalador()->create(['usuario' => 'inst18']);
        $salida = $this->crearSalidaConProyecto($user);

        Sanctum::actingAs($user);

        $this->postJson("/api/salidas/{$salida->id}/evidencias-entrega", [
            'archivo' => UploadedFile::fake()->image('firma.jpg'),
            'tipo'    => 'no_existe',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['tipo']);
    }

    public function test_sin_token_da_401(): void
    {
        $this->postJson('/api/salidas/1/evidencias-entrega', [])
            ->assertUnauthorized();
    }
}
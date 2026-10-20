<?php

namespace Tests\Feature;

use App\Models\Instalacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstalacionFlujoTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    #[Test]
    public function iniciar_cambia_estatus_y_crea_evento(): void
    {
        [$user, $inst] = $this->setupInstalacion('en_proceso');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/instalaciones/{$inst->id}/iniciar", [
                'capturado_at'     => now()->toIso8601String(),
                'latitud'          => 20.6708140,
                'longitud'         => -105.2126830,
                'precision_metros' => 12,
                'fuente'           => 'gps',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('instalacion_eventos', [
            'instalacion_id' => $inst->id,
            'tipo'           => 'iniciada',
            'por'            => $user->usuario,
        ]);
        $this->assertSame('en_proceso', $inst->fresh()->estatus_instalacion);
    }

    #[Test]
    public function pausar_requiere_estatus_en_proceso(): void
    {
        [$user, $inst] = $this->setupInstalacion('preparacion');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/instalaciones/{$inst->id}/pausar", [
                'capturado_at' => now()->toIso8601String(),
            ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function pausar_y_reanudar_registran_eventos(): void
    {
        [$user, $inst] = $this->setupInstalacion('en_proceso');

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/instalaciones/{$inst->id}/pausar", [
                'capturado_at' => now()->toIso8601String(),
                'notas'        => 'Se acabó la luz',
            ])->assertOk();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/instalaciones/{$inst->id}/reanudar", [
                'capturado_at' => now()->toIso8601String(),
            ])->assertOk();

        $tipos = DB::table('instalacion_eventos')
            ->where('instalacion_id', $inst->id)
            ->orderBy('id')
            ->pluck('tipo')
            ->all();

        $this->assertSame(['pausada', 'reanudada'], $tipos);
    }

    #[Test]
    public function finalizar_exige_foto(): void
    {
        [$user, $inst] = $this->setupInstalacion('en_proceso');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/instalaciones/{$inst->id}/finalizar", [
                'capturado_at' => now()->toIso8601String(),
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('foto');
    }

    #[Test]
    public function finalizar_guarda_estatus_y_evento(): void
    {
        [$user, $inst] = $this->setupInstalacion('en_proceso');
        $foto = UploadedFile::fake()->image('fin.jpg', 800, 600)->size(200);

        $response = $this->actingAs($user, 'sanctum')
            ->post("/api/instalaciones/{$inst->id}/finalizar", [
                'capturado_at' => now()->toIso8601String(),
                'foto'         => $foto,
                'latitud'      => 20.67,
                'longitud'     => -105.21,
                'fuente'       => 'gps',
            ]);

        $response->assertOk();
        $this->assertSame('terminada', $inst->fresh()->estatus_instalacion);
        $this->assertDatabaseHas('instalacion_eventos', [
            'instalacion_id' => $inst->id,
            'tipo'           => 'finalizada',
        ]);
        $this->assertDatabaseHas('instalacion_fotos', [
            'instalacion_id' => $inst->id,
            'tipo'           => 'fin',
        ]);
    }

    #[Test]
    public function usuario_no_asignado_recibe_403(): void
    {
        [$user, $inst] = $this->setupInstalacion('en_proceso');

        $otro = $this->crearUsuario('intruso_' . uniqid());

        $response = $this->actingAs($otro, 'sanctum')
            ->postJson("/api/instalaciones/{$inst->id}/iniciar", [
                'capturado_at' => now()->toIso8601String(),
            ]);

        $response->assertForbidden();
    }

    // ────────────────────────────────────────────────────────────
    // Helpers
    // ────────────────────────────────────────────────────────────

    private function setupInstalacion(string $estatus): array
    {
        $user = $this->crearUsuario('lino_test_' . uniqid());

        DB::table('estatus')->insertOrIgnore([
            'estatus' => 'preparacion', 'tipo' => 'instalacion',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('estatus')->insertOrIgnore([
            'estatus' => 'en_proceso', 'tipo' => 'instalacion',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('estatus')->insertOrIgnore([
            'estatus' => 'terminada', 'tipo' => 'instalacion',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('proyectos')->insertOrIgnore([
            'nombre_proyecto' => 'PROY-FLUJO',
            'modificado_por'  => $user->usuario,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $inst = Instalacion::create([
            'nombre_proyecto'     => 'PROY-FLUJO',
            'fecha_hora_inicio'   => now(),
            'estatus_instalacion' => $estatus,
            'nombre_instalacion'  => 'Instalación Flujo',
        ]);

        DB::table('instalacion_instalador')->insert([
            'instalacion_id'     => $inst->id,
            'instalador_usuario' => $user->usuario,
            'es_principal'       => 0,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return [$user, $inst];
    }

    private function crearUsuario(string $nombre): Usuario
    {
        DB::table('usuarios')->insertOrIgnore([
            'usuario'    => $nombre,
            'nombre'     => $nombre,
            'correo'     => "{$nombre}@example.com",
            'rol'        => 'Instalador',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Usuario::where('usuario', $nombre)->firstOrFail();
    }
}
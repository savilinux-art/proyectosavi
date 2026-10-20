<?php

namespace Tests\Feature;

use App\Models\Instalacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InstalacionApiTest extends TestCase
{
    use RefreshDatabase;

    private function asignar(Instalacion $inst, Usuario $user): void
    {
        $inst->instaladores()->attach($user->usuario, ['es_principal' => true]);
    }

    private function crearInstalacionConTimestamp($ts): Instalacion
    {
        $i = Instalacion::factory()->create();
        $i->timestamps = false;
        $i->updated_at = $ts;
        $i->save();
        return $i;
    }

    public function test_instalador_solo_ve_sus_instalaciones_via_pivote(): void
    {
        $inst01 = Usuario::factory()->instalador()->create(['usuario' => 'inst01']);
        $inst02 = Usuario::factory()->instalador()->create(['usuario' => 'inst02']);

        $mia   = Instalacion::factory()->create();
        $ajena = Instalacion::factory()->create();
        $this->asignar($mia, $inst01);
        $this->asignar($ajena, $inst02);

        Sanctum::actingAs($inst01);

        $this->getJson('/api/instalaciones')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $mia->id);
    }

    public function test_admin_ve_todas(): void
    {
        $admin = Usuario::factory()->admin()->create(['usuario' => 'admin1']);
        Instalacion::factory()->count(3)->create();

        Sanctum::actingAs($admin);

        $this->getJson('/api/instalaciones')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_sin_token_rechaza(): void
    {
        $this->getJson('/api/instalaciones')->assertUnauthorized();
    }

    public function test_filtro_estatus(): void
    {
        $admin = Usuario::factory()->admin()->create(['usuario' => 'admin2']);

        // Aseguramos que los estatus referenciados existan (FK a tabla `estatus`)
        \App\Models\Estatus::firstOrCreate(['estatus' => 'en_proceso', 'tipo' => 'instalacion']);
        \App\Models\Estatus::firstOrCreate(['estatus' => 'terminada',  'tipo' => 'instalacion']);

        Instalacion::factory()->create(['estatus_instalacion' => 'en_proceso']);
        Instalacion::factory()->create(['estatus_instalacion' => 'terminada']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/instalaciones?estatus=terminada')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_paginacion(): void
    {
        $admin = Usuario::factory()->admin()->create(['usuario' => 'admin3']);
        Instalacion::factory()->count(5)->create();

        Sanctum::actingAs($admin);

        $this->getJson('/api/instalaciones?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 5);
    }

    public function test_since_filtra(): void
    {
        $admin = Usuario::factory()->admin()->create(['usuario' => 'admin4']);

        $this->crearInstalacionConTimestamp(now()->subDays(2));
        Instalacion::factory()->create();

        Sanctum::actingAs($admin);

        $since = now()->subDay()->toIso8601String();

        $this->getJson('/api/instalaciones?since=' . urlencode($since))
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_show_no_rompe(): void
    {
        $admin = Usuario::factory()->admin()->create(['usuario' => 'admin5']);
        $inst  = Instalacion::factory()->create();

        Sanctum::actingAs($admin);

        $this->getJson('/api/instalaciones/' . $inst->id)
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}

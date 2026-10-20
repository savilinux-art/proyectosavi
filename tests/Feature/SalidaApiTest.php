<?php

namespace Tests\Feature;

use App\Models\SalidaInventario;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SalidaApiTest extends TestCase
{
    use RefreshDatabase;

    private function crearSalida(Usuario $receptor): SalidaInventario
    {
        $venta = Venta::factory()->create();

        return SalidaInventario::forceCreate([
            'nombre_proyecto'   => $venta->nombre_proyecto,
            'entregado_por'     => $venta->vendedor,
            'entregado_a'       => $receptor->usuario,
            'fecha_hora_salida' => now(),
            'observaciones'     => 'test',
        ]);
    }

    private function crearSalidaConTimestamp(Usuario $receptor, $ts): SalidaInventario
    {
        $s = $this->crearSalida($receptor);
        $s->timestamps = false;
        $s->updated_at = $ts;
        $s->save();
        return $s;
    }

    public function test_lista_solo_salidas_propias(): void
    {
        $user = Usuario::factory()->instalador()->create(['usuario' => 'inst01']);
        $otro = Usuario::factory()->instalador()->create(['usuario' => 'inst02']);

        $this->crearSalida($user);
        $this->crearSalida($user);
        $this->crearSalida($otro);

        Sanctum::actingAs($user);

        $this->getJson('/api/salidas')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('total', 2);
    }

    public function test_no_lista_salidas_de_otros(): void
    {
        $user = Usuario::factory()->instalador()->create(['usuario' => 'inst03']);
        $otro = Usuario::factory()->instalador()->create(['usuario' => 'inst04']);
        $this->crearSalida($otro);

        Sanctum::actingAs($user);

        $this->getJson('/api/salidas')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_sin_token_rechaza(): void
    {
        $this->getJson('/api/salidas')->assertUnauthorized();
    }

    public function test_per_page_respeta_limite(): void
    {
        $user = Usuario::factory()->instalador()->create(['usuario' => 'inst05']);
        for ($i = 0; $i < 5; $i++) {
            $this->crearSalida($user);
        }

        Sanctum::actingAs($user);

        $this->getJson('/api/salidas?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 5);
    }

    public function test_since_filtra_por_updated_at(): void
    {
        $user = Usuario::factory()->instalador()->create(['usuario' => 'inst06']);

        $this->crearSalidaConTimestamp($user, now()->subDays(2));
        $nueva = $this->crearSalida($user);

        Sanctum::actingAs($user);

        $since = now()->subDay()->toIso8601String();

        $this->getJson('/api/salidas?since=' . urlencode($since))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $nueva->id);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Instalacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstalacionShowTest extends TestCase
{
    use DatabaseTransactions;

    private function actingAsAdmin(): void
    {
        Sanctum::actingAs(Usuario::factory()->admin()->create());
    }

    #[Test]
    public function show_devuelve_200_con_estructura_esperada(): void
    {
        $this->actingAsAdmin();
        $inst = Instalacion::factory()->create([
            'estatus_instalacion' => 'Programada',
        ]);

        $this->getJson("/api/instalaciones/{$inst->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $inst->id)
            ->assertJsonPath('data.nombre_instalacion', $inst->nombre_instalacion)
            ->assertJsonPath('data.estatus_instalacion', 'Programada');
    }

    #[Test]
    public function show_devuelve_404_si_no_existe(): void
    {
        $this->actingAsAdmin();

        $this->getJson('/api/instalaciones/999999')
            ->assertNotFound();
    }

    #[Test]
    public function show_expone_check_list_como_array(): void
    {
        $this->actingAsAdmin();
        $inst = Instalacion::factory()->create([
            'check_list' => ['paso1' => true, 'paso2' => false],
        ]);

        $response = $this->getJson("/api/instalaciones/{$inst->id}")->assertOk();

        $this->assertSame(
            ['paso1' => true, 'paso2' => false],
            $response->json('data.check_list')
        );
    }
}
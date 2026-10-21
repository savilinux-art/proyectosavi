<?php

namespace Tests\Feature;

use App\Models\Instalacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class InstalacionChecklistTest extends TestCase
{
    use DatabaseTransactions;

    private function actingAsAdmin(): void
    {
        Sanctum::actingAs(Usuario::factory()->admin()->create());
    }

    #[Test]
    public function persiste_checklist_y_lo_devuelve(): void
    {
        $this->actingAsAdmin();
        $inst = Instalacion::factory()->create(['check_list' => null]);

        $payload = [
            'check_list' => [
                'items' => [
                    ['id' => 1, 'ok' => true],
                    ['id' => 2, 'ok' => false],
                ],
                'observaciones' => 'todo bien',
            ],
        ];

        $this->postJson("/api/instalaciones/{$inst->id}/checklist", $payload)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.items.0.ok', true)
            ->assertJsonPath('data.observaciones', 'todo bien');

        $inst->refresh();
        $this->assertSame($payload['check_list'], $inst->check_list);
    }

    #[Test]
    public function rechaza_sin_check_list(): void
    {
        $this->actingAsAdmin();
        $inst = Instalacion::factory()->create();

        $this->postJson("/api/instalaciones/{$inst->id}/checklist", [])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['check_list']]);
    }

    #[Test]
    public function rechaza_check_list_que_no_es_array(): void
    {
        $this->actingAsAdmin();
        $inst = Instalacion::factory()->create();

        $this->postJson("/api/instalaciones/{$inst->id}/checklist", [
            'check_list' => 'no soy array',
        ])
            ->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    #[Test]
    public function devuelve_404_si_instalacion_no_existe(): void
    {
        $this->actingAsAdmin();

        $this->postJson('/api/instalaciones/999999/checklist', [
            'check_list' => ['a' => true],
        ])->assertNotFound();
    }

    public function updateChecklist(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'check_list' => 'required|array',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors(),
        ], 422);
    }

    $instalacion = Instalacion::findOrFail($id);      // 404 fuera del try
    $instalacion->check_list = $request->check_list;  // el cast 'array' se encarga
    $instalacion->save();

    return response()->json([
        'success' => true,
        'message' => 'Checklist actualizado exitosamente',
        'data'    => $instalacion->check_list,
    ]);
}

}
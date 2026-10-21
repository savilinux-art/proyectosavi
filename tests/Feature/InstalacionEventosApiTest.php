<?php

use App\Models\Instalacion;
use App\Models\InstalacionEvento;
use App\Models\InstalacionFoto;
use App\Models\Usuario;
use App\Services\InstalacionFotoService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake(InstalacionFotoService::DISCO);

    $this->tecnico = Usuario::factory()->create();

    $this->instalacion = Instalacion::factory()->create();
    $this->instalacion->instaladores()->attach(
        $this->tecnico->usuario,
        ['es_principal' => true]
    );

    $this->actingAs($this->tecnico, 'sanctum');
});

it('registra_nota_con_shortcut_descripcion', function () {
    $texto = 'Cliente solicitó instalar sensor en pared norte.';

    $resp = $this->postJson("/api/instalaciones/{$this->instalacion->id}/eventos", [
        'tipo'         => 'nota_agregada',
        'capturado_at' => now()->subMinute()->toIso8601String(),
        'descripcion'  => $texto,
    ]);

    $resp->assertCreated();
    $resp->assertJsonPath('message', 'Evento nota_agregada registrado');

    $evento = InstalacionEvento::where('instalacion_id', $this->instalacion->id)
        ->where('tipo', 'nota_agregada')
        ->firstOrFail();

    $evento->refresh();

    expect($evento->payload_json)->toBeArray();
    expect($evento->payload_json['descripcion'])->toBe($texto);
    expect($evento->por)->toBe($this->tecnico->usuario);
});

it('registra_incidencia_con_payload_json', function () {
    $payload = [
        'severidad'  => 'alta',
        'categoria'  => 'red',
        'componente' => 'switch-piso-2',
    ];

    $resp = $this->postJson("/api/instalaciones/{$this->instalacion->id}/eventos", [
        'tipo'         => 'incidencia',
        'capturado_at' => now()->subMinute()->toIso8601String(),
        'payload_json' => $payload,
        'descripcion'  => 'Switch no enciende tras corte de luz.',
    ]);

    $resp->assertCreated();

    $evento = InstalacionEvento::where('instalacion_id', $this->instalacion->id)
        ->where('tipo', 'incidencia')
        ->firstOrFail();

    $evento->refresh();

    expect($evento->payload_json)->toBeArray();
    expect($evento->payload_json['severidad'])->toBe('alta');
    expect($evento->payload_json['categoria'])->toBe('red');
    expect($evento->payload_json['descripcion'])
        ->toBe('Switch no enciende tras corte de luz.');
});

it('rechaza_tipo_no_permitido', function () {
    $resp = $this->postJson("/api/instalaciones/{$this->instalacion->id}/eventos", [
        'tipo'         => 'iniciada',
        'capturado_at' => now()->subMinute()->toIso8601String(),
        'descripcion'  => 'intento por endpoint equivocado',
    ]);

    $resp->assertStatus(422);
    $resp->assertJsonValidationErrors('tipo');

    expect(
        InstalacionEvento::where('instalacion_id', $this->instalacion->id)->count()
    )->toBe(0);
});

it('rechaza_sin_descripcion_ni_payload', function () {
    $resp = $this->postJson("/api/instalaciones/{$this->instalacion->id}/eventos", [
        'tipo'         => 'nota_agregada',
        'capturado_at' => now()->subMinute()->toIso8601String(),
    ]);

    $resp->assertStatus(422);
    $resp->assertJsonValidationErrors('descripcion');

    expect(
        InstalacionEvento::where('instalacion_id', $this->instalacion->id)->count()
    )->toBe(0);
});

it('rechaza_si_no_esta_asignado', function () {
    $otroTecnico = Usuario::factory()->create();
    $this->actingAs($otroTecnico, 'sanctum');

    $resp = $this->postJson("/api/instalaciones/{$this->instalacion->id}/eventos", [
        'tipo'         => 'nota_agregada',
        'capturado_at' => now()->subMinute()->toIso8601String(),
        'descripcion'  => 'intento no autorizado',
    ]);

    $resp->assertForbidden();

    expect(
        InstalacionEvento::where('instalacion_id', $this->instalacion->id)->count()
    )->toBe(0);
});

it('rechaza_capturado_at_en_futuro', function () {
    $resp = $this->postJson("/api/instalaciones/{$this->instalacion->id}/eventos", [
        'tipo'         => 'nota_agregada',
        'capturado_at' => now()->addHour()->toIso8601String(),
        'descripcion'  => 'timestamp manipulado',
    ]);

    $resp->assertStatus(422);

    expect(
        InstalacionEvento::where('instalacion_id', $this->instalacion->id)->count()
    )->toBe(0);
});

it('guarda_foto_de_incidencia_en_storage', function () {
    $foto = UploadedFile::fake()->image('incidencia.jpg', 800, 600);

    $resp = $this->post(
        "/api/instalaciones/{$this->instalacion->id}/eventos",
        [
            'tipo'         => 'incidencia',
            'capturado_at' => now()->subMinute()->toIso8601String(),
            'descripcion'  => 'Cable expuesto en rack, riesgo eléctrico.',
            'foto'         => $foto,
        ],
        ['Accept' => 'application/json']
    );

    $resp->assertCreated();

    $evento = InstalacionEvento::where('instalacion_id', $this->instalacion->id)
        ->where('tipo', 'incidencia')
        ->firstOrFail();
    $evento->refresh();
    expect($evento->payload_json['descripcion'])
        ->toBe('Cable expuesto en rack, riesgo eléctrico.');

    $registro = InstalacionFoto::where('instalacion_id', $this->instalacion->id)
        ->firstOrFail();
    expect($registro->tipo)->toBe('incidencia');
    expect($registro->tamano_kb)->toBeGreaterThan(0);

    Storage::disk(InstalacionFotoService::DISCO)->assertExists($registro->ruta);
});
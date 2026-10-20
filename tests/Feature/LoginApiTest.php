<?php

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

function crearUsuarioLogin(string $pass = 'secret123'): Usuario
{
    static $i = 0;
    $i++;

    return Usuario::create([
        'usuario'    => 'tecnico_'.$i.'_'.uniqid(),
        'nombre'     => 'Técnico Test',
        'correo'     => 'test_'.$i.'_'.uniqid().'@example.test',   // ← FIX 1364
        'contraseña' => Hash::make($pass),
        'rol'        => 'Instalador',
    ]);
}

test('POST /api/login con contraseña retorna token', function () {
    $u = crearUsuarioLogin('secret123');

    $res = $this->postJson('/api/login', [
        'usuario'    => $u->usuario,
        'contraseña' => 'secret123',
    ]);

    $res->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['token', 'user' => ['id', 'usuario']]);

    expect($res->json('token'))->toBeString()->not->toBeEmpty();
    expect($res->json('user.id'))->toBe($u->id);
});

test('POST /api/login con alias password también funciona', function () {
    $u = crearUsuarioLogin('secret123');

    $this->postJson('/api/login', [
        'usuario'  => $u->usuario,
        'password' => 'secret123',   // ← alias
    ])->assertOk()->assertJsonPath('success', true);
});

test('POST /api/login falla con contraseña incorrecta', function () {
    $u = crearUsuarioLogin('secret123');

    $this->postJson('/api/login', [
        'usuario'    => $u->usuario,
        'contraseña' => 'wrong',
    ])->assertStatus(401)
      ->assertJsonPath('success', false);
});

test('POST /api/login falla con usuario inexistente', function () {
    $this->postJson('/api/login', [
        'usuario'    => 'noexiste_'.uniqid(),
        'contraseña' => 'x',
    ])->assertStatus(401);
});

test('POST /api/login valida campos requeridos', function () {
    $this->postJson('/api/login', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['usuario', 'contraseña']);
});

test('GET /api/me retorna usuario autenticado', function () {
    $u = crearUsuarioLogin();

    $this->actingAs($u, 'sanctum')
        ->getJson('/api/me')
        ->assertOk()
        ->assertJsonPath('user.id', $u->id)
        ->assertJsonPath('user.usuario', $u->usuario);
});

test('GET /api/user (legacy) sigue funcionando', function () {
    $u = crearUsuarioLogin();

    $this->actingAs($u, 'sanctum')
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('user.id', $u->id);
});

test('GET /api/me rechaza sin token', function () {
    $this->getJson('/api/me')->assertStatus(401);
});

test('POST /api/logout revoca el token actual', function () {
    $u = crearUsuarioLogin();
    $token = $u->createToken('test-device');

    $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
        ->postJson('/api/logout')
        ->assertOk();

    expect(DB::table('personal_access_tokens')->where('id', $token->accessToken->id)->exists())
        ->toBeFalse();
});
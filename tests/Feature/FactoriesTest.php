<?php

use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Venta;
use App\Models\Estatus;
use App\Models\Inventario;
use App\Models\Proyecto;
use App\Models\Instalacion;
use App\Models\MovimientoInventario;

test('RolFactory crea un rol', function () {
    $r = Rol::factory()->create();
    expect($r->rol)->not->toBeNull();
    expect($r->getKey())->not->toBeNull();
});

test('UsuarioFactory crea un usuario con rol', function () {
    $u = Usuario::factory()->create();
    expect($u->id)->not->toBeNull();
    expect($u->rol)->not->toBeNull();
    expect($u->contraseña)->not->toBeNull();
});

test('UsuarioFactory estado admin', function () {
    $u = Usuario::factory()->admin()->create();
    expect($u->rol)->toBe('Administrador');
});

test('ClienteFactory crea un cliente', function () {
    expect(Cliente::factory()->create()->rfc)->not->toBeNull();
});

test('CategoriaFactory crea una categoría', function () {
    expect(Categoria::factory()->create()->nombre_categoria)->not->toBeNull();
});

test('VentaFactory crea una venta con vendedor', function () {
    $v = Venta::factory()->create();
    expect($v->vendedor)->not->toBeNull();
});

test('EstatusFactory crea estatus por tipo', function () {
    $e = Estatus::factory()->deTipo('instalacion', 'Programada')->create();
    expect($e->tipo)->toBe('instalacion');
});

test('InventarioFactory crea un ítem con categoría y usuario', function () {
    $i = Inventario::factory()->create();
    expect($i->categoria)->not->toBeNull();
    expect($i->modificado_por)->not->toBeNull();
});

test('ProyectoFactory crea un proyecto ligado a venta', function () {
    $p = Proyecto::factory()->create();
    expect($p->nombre_proyecto)->not->toBeNull();
});

test('InstalacionFactory crea una instalación', function () {
    $i = Instalacion::factory()->create();
    expect($i->estatus_instalacion)->toBe('Programada');
});

test('MovimientoInventarioFactory crea un movimiento', function () {
    $m = MovimientoInventario::factory()->create();
    expect($m->inventario_id)->not->toBeNull();
});

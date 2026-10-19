<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Sembrar roles mínimos si la tabla existe.
        // Los FKs (usuarios.rol → roles.rol, permisos_rol.rol → roles.rol)
        // exigen que el rol exista antes de crear Usuario/PermisoRol.
        // insertOrIgnore protege contra duplicados si la DB ya venía sembrada.
        // Rollback automático por DatabaseTransactions/RefreshDatabase.
        if (Schema::hasTable('roles')) {
            foreach (['Administrador', 'Contabilidad', 'Inventarios', 'Instalador', 'Ventas', 'Sistemas'] as $rol) {
                DB::table('roles')->insertOrIgnore([
                    'rol'        => $rol,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
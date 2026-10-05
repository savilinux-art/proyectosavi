<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoVentasMostradorSeeder extends Seeder
{
    public function run(): void
    {
        $permiso = DB::table('permisos')
            ->where('slug', 'ventas-mostrador')
            ->first();

        if (!$permiso) {
            $id = DB::table('permisos')->insertGetId([
                'nombre'      => 'Ventas Mostrador',
                'slug'        => 'ventas-mostrador',
                'descripcion' => 'Acceder al modulo de ventas de mostrador',
                'modulo'      => 'ventas',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } else {
            $id = $permiso->id;
        }

        foreach (['Administrador', 'Contabilidad', 'Ventas'] as $rol) {
            DB::table('permiso_rol')->updateOrInsert(
                ['rol' => $rol, 'permiso_id' => $id],
                [
                    'permitido'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
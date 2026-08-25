<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Administrador', 'Contabilidad', 'Inventarios', 'Instalador', 'Ventas', 'Sistemas'];
        
        foreach ($roles as $rol) {
            Rol::create(['rol' => $rol]);
        }
    }
}
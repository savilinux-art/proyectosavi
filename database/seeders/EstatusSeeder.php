<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estatus;

class EstatusSeeder extends Seeder
{
    public function run(): void
    {
        // Estatus para ventas
        $ventasEstatus = ['prospeccion', 'levantamiento', 'cotizacion', 'cierre_venta'];
        foreach ($ventasEstatus as $estatus) {
            Estatus::create(['estatus' => $estatus, 'tipo' => 'venta']);
        }
        
        // Estatus para instalaciones
        $instalacionesEstatus = ['preparacion', 'en_proceso', 'programacion', 'pruebas', 'entrega'];
        foreach ($instalacionesEstatus as $estatus) {
            Estatus::create(['estatus' => $estatus, 'tipo' => 'instalacion']);
        }
        
        // Estatus para proyectos
        $proyectosEstatus = ['en_desarrollo', 'detenido', 'pruebas_finales', 'terminado'];
        foreach ($proyectosEstatus as $estatus) {
            Estatus::create(['estatus' => $estatus, 'tipo' => 'proyecto']);
        }
    }
}
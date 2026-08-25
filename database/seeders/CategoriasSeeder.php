<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Lutron', 'Iluminacion', 'Redes', 'CCTV', 
            'Audio', 'Audio y Video', 'Cableado', 'Accesorios','Automatizacion','Control de acceso','Domotica',
            'Seguridad',
              ];
        
        foreach ($categorias as $categoria) {
            Categoria::create(['nombre_categoria' => $categoria]);
        }
    }
}
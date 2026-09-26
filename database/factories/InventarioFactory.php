<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventarioFactory extends Factory
{
    protected $model = Inventario::class;

    public function definition(): array
    {
        return [
            'existencia'         => $this->faker->numberBetween(0, 100),
            'modelo'             => strtoupper($this->faker->bothify('MOD-####')),
            'descripcion'        => $this->faker->sentence(4),
            'marca'              => $this->faker->randomElement(['Hikvision', 'Dahua', 'TP-Link', 'Ezviz', 'Genérico']),
            'categoria'          => Categoria::factory()->create()->nombre_categoria,
            'almacen'            => $this->faker->randomElement(['Bodega Central', 'Bodega Norte', 'Camioneta 1']),
            'apea'               => null,
            'imagen'             => null,
            'imagen_url'         => null,
            'fecha_modificacion' => now(),
            'comentarios'        => null,
            'apartados'          => null,
            'cantidad_apartados' => null,
            'modificado_por'     => Usuario::factory()->create()->usuario,
            'codigo_origen'      => null,
            'fecha_alta'         => now(),
            'precio'             => $this->faker->randomFloat(2, 50, 5000),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovimientoInventarioFactory extends Factory
{
    protected $model = MovimientoInventario::class;

    public function definition(): array
    {
        return [
            'inventario_id'        => Inventario::factory(),
            'entrada'              => $this->faker->numberBetween(1, 20),
            'salida'               => null,
            'ajuste'               => null,
            'devolucion'           => null,
            'apartado'             => null,
            'instalacion'          => null,
            'devolucion_proveedor' => null,
            'modificado_por'       => Usuario::factory()->create()->usuario,
            'comentarios'          => $this->faker->sentence(),
        ];
    }

    public function salida(int $cantidad = 5): static
    {
        return $this->state(fn () => [
            'entrada' => null,
            'salida'  => $cantidad,
        ]);
    }
}

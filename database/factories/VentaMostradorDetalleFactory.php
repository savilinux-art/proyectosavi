<?php

namespace Database\Factories;

use App\Models\Inventario;
use App\Models\VentaMostrador;
use App\Models\VentaMostradorDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaMostradorDetalleFactory extends Factory
{
    protected $model = VentaMostradorDetalle::class;

    public function definition(): array
    {
        $cantidad = $this->faker->numberBetween(1, 10);
        $precio = $this->faker->randomFloat(2, 50, 5000);
        $descuento = 0;
        $subtotal = $cantidad * ($precio - $descuento);

        return [
            'venta_mostrador_id' => VentaMostrador::factory(),
            'inventario_id' => Inventario::factory(),
            'cantidad' => $cantidad,
            'precio_unitario' => $precio,
            'descuento' => $descuento,
            'subtotal' => $subtotal,
        ];
    }

    public function conDescuento(float $descuento): static
    {
        return $this->state(function (array $attrs) use ($descuento) {
            $cantidad = $attrs['cantidad'] ?? 1;
            $precio = $attrs['precio_unitario'] ?? 100;
            return [
                'descuento' => $descuento,
                'subtotal' => $cantidad * ($precio - $descuento),
            ];
        });
    }
}
<?php

namespace Database\Factories;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'usuario'           => $this->faker->unique()->userName(),
            'nombre'            => $this->faker->name(),
            'correo'            => $this->faker->unique()->safeEmail(),
            'telegram_chat_id'  => null,
            'contraseña'        => Hash::make('password'),
            'rol'               => Rol::firstOrCreate(['rol' => 'Administrador'])->rol,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'rol' => Rol::firstOrCreate(['rol' => 'Administrador'])->rol,
        ]);
    }

    public function instalador(): static
    {
        return $this->state(fn () => [
            'rol' => Rol::firstOrCreate(['rol' => 'Instalador'])->rol,
        ]);
    }

    public function ventas(): static
    {
        return $this->state(fn () => [
            'rol' => Rol::firstOrCreate(['rol' => 'Ventas'])->rol,
        ]);
    }

    public function contabilidad(): static
    {
        return $this->state(fn () => [
            'rol' => Rol::firstOrCreate(['rol' => 'Contabilidad'])->rol,
        ]);
    }
}

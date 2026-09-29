<?php

namespace Database\Factories;

use App\Models\Recordatorio;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recordatorio>
 */
class RecordatorioFactory extends Factory
{
    protected $model = Recordatorio::class;

    public function definition(): array
    {
        return [
            'tipo'                  => 'general',
            'usuario_id'            => Usuario::factory(),
            'created_by'            => null,
            'titulo'                => $this->faker->sentence(3),
            'descripcion'           => $this->faker->optional()->paragraph(),
            // Por defecto ya vencido → el scope pendientesDeEnviar lo agarra.
            'fecha_hora_programada' => now()->subMinute(),
            'estatus'               => 'pendiente',
            'recurrencia'           => 'una_vez',
            'regla_recurrencia'     => null,
            'canal'                 => ['telegram'],
            'enviado_at'            => null,
            'intentos'              => 0,
            'ultimo_error'          => null,
        ];
    }

    // ---- Estados ----------------------------------------------------------

    public function general(): static
    {
        return $this->state(fn () => ['tipo' => 'general']);
    }

    public function certificado(): static
    {
        return $this->state(fn () => [
            'tipo'                   => 'certificado',
            'titulo'                 => 'Certificado SSL ' . $this->faker->domainName(),
            'cert_nombre'            => 'Certificado de prueba',
            'cert_tipo'              => 'ssl',
            'cert_emisor'            => 'Let\'s Encrypt',
            'cert_fecha_vencimiento' => now()->addDays(30)->toDateString(),
            'cert_avisos_dias'       => [0, 1, 3, 7, 15],
        ]);
    }

    /** Certificado cuyo vencimiento es HOY → ya en último aviso. */
    public function certificadoVencidoHoy(): static
    {
        return $this->state(fn () => [
            'tipo'                   => 'certificado',
            'titulo'                 => 'Certificado por vencer HOY',
            'cert_nombre'            => 'Certificado de prueba (vence hoy)',
            'cert_tipo'              => 'ssl',
            'cert_emisor'            => 'Let\'s Encrypt',
            'cert_fecha_vencimiento' => now()->toDateString(),
            'cert_avisos_dias'       => [0, 1, 3, 7, 15],
        ]);
    }

    public function recurrente(string $recurrencia = 'diario'): static
    {
        return $this->state(fn () => ['recurrencia' => $recurrencia]);
    }

    public function conIntentos(int $n): static
    {
        return $this->state(fn () => ['intentos' => $n]);
    }

    public function porCanal(array $canales): static
    {
        return $this->state(fn () => ['canal' => $canales]);
    }
}

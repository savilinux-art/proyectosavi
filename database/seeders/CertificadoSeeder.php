<?php

namespace Database\Seeders;

use App\Models\Recordatorio;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CertificadoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Usuario::where('rol', 'Administrador')->first() ?? Usuario::first();
        if (!$admin) {
            $this->command->error('No hay usuarios.');
            return;
        }

        $avisos = [15, 7, 3, 1, 0];

        $certs = [
            [
                'titulo'                 => 'SSL Tailscale — ProyectoSAVI',
                'descripcion'            => 'Certificado SSL del dominio *.ts.net de Tailscale',
                'cert_nombre'            => 'SSL Tailscale ProyectoSAVI',
                'cert_tipo'              => 'ssl',
                'cert_emisor'            => "Let's Encrypt / Tailscale",
                'cert_fecha_vencimiento' => '2026-12-21',
            ],
            [
                'titulo'                 => 'SSL DEMO VENCIDO (prueba)',
                'descripcion'            => 'Certificado de prueba ya vencido',
                'cert_nombre'            => 'SSL Demo Vencido',
                'cert_tipo'              => 'ssl',
                'cert_emisor'            => 'Demo CA',
                'cert_fecha_vencimiento' => now()->subDay()->toDateString(),
            ],
            [
                'titulo'                 => 'Dominio DEMO por vencer (prueba)',
                'descripcion'            => 'Certificado de prueba que vence en 5 días',
                'cert_nombre'            => 'Dominio Demo Por Vencer',
                'cert_tipo'              => 'dominio',
                'cert_emisor'            => 'Demo Registrar',
                'cert_fecha_vencimiento' => now()->addDays(5)->toDateString(),
            ],
        ];

        foreach ($certs as $c) {
            $venc = Carbon::parse($c['cert_fecha_vencimiento']);
            Recordatorio::updateOrCreate(
                ['titulo' => $c['titulo']],
                array_merge($c, [
                    'tipo'                  => 'certificado',
                    'usuario_id'            => $admin->id,
                    'created_by'            => $admin->id,
                    'canal'                 => ['telegram', 'web'],
                    'recurrencia'           => 'personalizado',
                    'cert_avisos_dias'      => $avisos,
                    'estatus'               => 'pendiente',
                    'intentos'              => 0,
                    'fecha_hora_programada' => $venc->copy()->subDays(15)->setTime(9, 0, 0),
                ])
            );
        }

        $this->command->info('✅ ' . count($certs) . ' certificados de prueba creados/actualizados.');
    }
}

<?php

namespace App\Console\Commands;

use App\Services\Recordatorios\Procesador;
use Illuminate\Console\Command;

class ProcesarRecordatorios extends Command
{
    protected $signature = 'recordatorios:procesar';
    protected $description = 'Envía los recordatorios pendientes cuya hora ya pasó';

    public function handle(Procesador $procesador): int
    {
        $resultado = $procesador->procesarPendientes();

        $this->info(sprintf(
            '✅ Enviados: %d | ❌ Fallidos: %d',
            $resultado['enviados'],
            $resultado['fallidos']
        ));

        return self::SUCCESS;
    }
}
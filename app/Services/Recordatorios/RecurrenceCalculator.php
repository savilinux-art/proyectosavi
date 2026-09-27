<?php

namespace App\Services\Recordatorios;

use App\Models\Recordatorio;
use Carbon\Carbon;

class RecurrenceCalculator
{
    /**
     * Devuelve la siguiente fecha de ejecución a partir de la fecha original.
     * Devuelve null si el recordatorio no es recurrente.
     */
    public function siguiente(Recordatorio $recordatorio): ?Carbon
    {
        if (!$recordatorio->esRecurrente()) {
            return null;
        }

        $base = $recordatorio->fecha_hora_programada->copy();

        return match ($recordatorio->recurrencia) {
            'diario'   => $base->addDay(),
            'semanal'  => $base->addWeek(),
            'mensual'  => $base->addMonthNoOverflow(),
            'personalizado' => $this->aplicarReglaPersonalizada($base, $recordatorio->regla_recurrencia ?? []),
            default    => null,
        };
    }

    protected function aplicarReglaPersonalizada(Carbon $base, array $regla): ?Carbon
    {
        // Ejemplo de estructura de regla:
        // {"cada_dias": 5}        → cada 5 días
        // {"dias_semana": [1,3,5]} → lunes, miércoles, viernes
        if (isset($regla['cada_dias'])) {
            return $base->addDays((int) $regla['cada_dias']);
        }

        return null;
    }
}
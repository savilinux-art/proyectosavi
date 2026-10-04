<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VentaMostradorSalida extends Pivot
{
    protected $table = 'venta_mostrador_salida';
    public $incrementing = true;
    protected $keyType = 'int';
}
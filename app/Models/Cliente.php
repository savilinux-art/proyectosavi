<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'rfc',
        'razon_social',
        'nombre_proyecto',
        'regimen_fiscal',
        'constancia_situacion_fiscal',
        'codigo_postal',
        'correo_electronico'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }
}
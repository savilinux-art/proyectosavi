<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;

    protected $table = 'estatus';
    protected $primaryKey = 'estatus';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['estatus', 'tipo'];
    protected $casts = [
        'tipo' => 'string',
    ];

    public function instalaciones()
    {
        return $this->hasMany(Instalacion::class, 'estatus_instalacion', 'estatus');
    }
}
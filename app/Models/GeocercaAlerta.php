<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeocercaAlerta extends Model
{
    protected $fillable = [
        'geocerca_id', 'usuario_id', 'tipo',
        'latitud', 'longitud', 'fecha_hora', 'notificado',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'notificado' => 'boolean',
    ];

    public function geocerca()
    {
        return $this->belongsTo(Geocerca::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // ── Scopes para el dashboard ─────────────────────
    public function scopeNoLeidas($query)
    {
        return $query->where('notificado', false);
    }

    public function scopeRecientes($query, int $limit = 20)
    {
        return $query->orderByDesc('fecha_hora')->limit($limit);
    }
}
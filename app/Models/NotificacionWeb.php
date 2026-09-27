<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class NotificacionWeb extends Model
{
    protected $table = 'notificaciones_web';

    protected $fillable = ['usuario_id', 'recordatorio_id', 'titulo', 'cuerpo', 'leida_at'];

    protected $casts = ['leida_at' => 'datetime'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario');
    }

    public function recordatorio(): BelongsTo
    {
        return $this->belongsTo(Recordatorio::class, 'recordatorio_id');
    }

    public function scopeNoLeidas(Builder $q): Builder
    {
        return $q->whereNull('leida_at');
    }
}

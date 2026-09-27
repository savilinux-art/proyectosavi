<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Recordatorio extends Model
{
    protected $table = 'recordatorios';

    protected $fillable = [
        'tipo', 'usuario_id', 'created_by',
        'titulo', 'descripcion',
        'fecha_hora_programada', 'estatus', 'recurrencia', 'regla_recurrencia',
        'canal', 'enviado_at', 'intentos', 'ultimo_error',
        'recordable_type', 'recordable_id', 'metadata',
        'cert_nombre', 'cert_tipo', 'cert_emisor', 'cert_serie',
        'cert_fecha_emision', 'cert_fecha_vencimiento',
        'cert_link_renovacion', 'cert_archivo_path', 'cert_avisos_dias',
        'cert_renovado_at',
    ];

    protected $casts = [
        'fecha_hora_programada'   => 'datetime',
        'enviado_at'              => 'datetime',
        'cert_renovado_at'        => 'datetime',
        'regla_recurrencia'       => 'array',
        'canal'                   => 'array',
        'metadata'                => 'array',
        'cert_avisos_dias'        => 'array',
        'cert_fecha_emision'      => 'date',
        'cert_fecha_vencimiento'  => 'date',
    ];

    // --- Relaciones ---

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function recordable(): MorphTo
    {
        return $this->morphTo();
    }

    public function historialCertificado(): HasMany
    {
        return $this->hasMany(CertificadoHistorial::class, 'recordatorio_id');
    }

    // --- Scopes ---

    public function scopePendientesDeEnviar(Builder $q): Builder
    {
        return $q->where('estatus', 'pendiente')
                 ->where('fecha_hora_programada', '<=', now())
                 ->where('intentos', '<', 3);
    }

    public function scopeCertificados(Builder $q): Builder
    {
        return $q->where('tipo', 'certificado');
    }

    // --- Helpers ---

    public function esCertificado(): bool
    {
        return $this->tipo === 'certificado';
    }

    public function esRecurrente(): bool
    {
        return $this->recurrencia !== 'una_vez';
    }

    public function diasRestantesCertificado(): ?int
    {
        if (!$this->cert_fecha_vencimiento) {
            return null;
        }
        return (int) now()->startOfDay()->diffInDays(
            $this->cert_fecha_vencimiento->startOfDay(),
            false
        );
    }
}
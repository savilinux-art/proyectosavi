@extends('layouts.app')
@section('page-title', 'Detalle de Notificación')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-bell"></i> Detalle de Notificación</h1>
    <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div class="card">
    <div class="card-header"><div class="d-flex justify-content-between"><span><span class="badge bg-{{ $notificacion->tipo == 'asignacion' ? 'primary' : ($notificacion->tipo == 'reasignacion' ? 'warning' : 'secondary') }}">{{ ucfirst($notificacion->tipo) }}</span> @if(!$notificacion->leida)<span class="badge bg-danger">No leída</span>@else<span class="badge bg-success">Leída</span>@endif</span><small class="text-muted">{{ \Carbon\Carbon::parse($notificacion->fecha_creacion)->format('d/m/Y H:i:s') }}</small></div></div>
    <div class="card-body">
        <h5>{{ $notificacion->mensaje }}</h5>
        @if($notificacion->referencia_id)<hr><p><strong>Referencia ID:</strong> {{ $notificacion->referencia_id }}</p>@endif
        <hr><p class="text-muted"><small>Recibida: {{ \Carbon\Carbon::parse($notificacion->fecha_creacion)->diffForHumans() }}</small></p>
        <div class="mt-3">
            @if(!$notificacion->leida)
            <form action="{{ route('notificaciones.markAsRead', $notificacion->id) }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn btn-success"><i class="bi bi-check"></i> Marcar como leída</button></form>
            @endif
            <form action="{{ route('notificaciones.destroy', $notificacion->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta notificación?')">@csrf @method('DELETE')<button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</button></form>
        </div>
    </div>
</div>
@endsection
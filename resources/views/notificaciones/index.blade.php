@extends('layouts.app')
@section('page-title', 'Notificaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-bell"></i> Mis Notificaciones</h1>
    <div>
        <form action="{{ route('notificaciones.markAllAsRead') }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn btn-success" {{ $noLeidas == 0 ? 'disabled' : '' }}><i class="bi bi-check-all"></i> Marcar todas como leídas</button></form>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-6"><div class="card text-white bg-primary"><div class="card-body"><h6>Total</h6><h3>{{ $notificaciones->count() }}</h3></div></div></div>
    <div class="col-md-6"><div class="card text-white bg-warning"><div class="card-body"><h6>No leídas</h6><h3>{{ $noLeidas }}</h3></div></div></div>
</div>
<div class="card"><div class="card-body">
    @if($notificaciones->count()>0)
    <div class="list-group">
        @foreach($notificaciones as $n)
        <div class="list-group-item list-group-item-action {{ $n->leida ? '' : 'list-group-item-warning' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div><span class="badge bg-{{ $n->tipo == 'asignacion' ? 'primary' : ($n->tipo == 'reasignacion' ? 'warning' : 'secondary') }} me-2">{{ ucfirst($n->tipo) }}</span><small class="text-muted">{{ \Carbon\Carbon::parse($n->fecha_creacion)->diffForHumans() }}</small></div>
                    <p class="mb-1 mt-2">{{ $n->mensaje }}</p>
                </div>
                <div class="btn-group">
                    @if(!$n->leida)
                    <form action="{{ route('notificaciones.markAsRead', $n->id) }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check"></i></button></form>
                    @endif
                    <a href="{{ route('notificaciones.show', $n->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <form action="{{ route('notificaciones.destroy', $n->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta notificación?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5"><i class="bi bi-inbox" style="font-size:64px;color:#ccc;"></i><h5 class="mt-3 text-muted">No tienes notificaciones</h5></div>
    @endif
</div></div>
@endsection
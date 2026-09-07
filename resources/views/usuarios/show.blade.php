@extends('layouts.app')

@section('page-title', 'Detalles del Usuario')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-person"></i> Detalles del Usuario: {{ $usuario->nombre }}</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">ID:</div>
                <div class="col-md-9">{{ $usuario->id }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Usuario:</div>
                <div class="col-md-9">{{ $usuario->usuario }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nombre:</div>
                <div class="col-md-9">{{ $usuario->nombre }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Correo:</div>
                <div class="col-md-9">{{ $usuario->correo }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Rol:</div>
                <div class="col-md-9">{{ $usuario->rol }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Telegram Chat ID:</div>
                <div class="col-md-9">
                    @if($usuario->telegram_chat_id)
                        <span class="badge bg-success">{{ $usuario->telegram_chat_id }}</span>
                    @else
                        <span class="badge bg-secondary">No registrado</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Traccar Device ID:</div>
                <div class="col-md-9">
                    @if($usuario->traccar_device_id)
                        <span class="badge bg-info">{{ $usuario->traccar_device_id }}</span>
                    @else
                        <span class="badge bg-secondary">No asignado</span>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
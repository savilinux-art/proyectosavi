@extends('layouts.app')

@section('page-title', 'Editar Usuario')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-pencil"></i> Editar Usuario: {{ $usuario->nombre }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>
                    @error('usuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo', $usuario->correo) }}" required>
                    @error('correo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                    <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                        <option value="">Seleccionar...</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->rol }}" {{ old('rol', $usuario->rol) == $rol->rol ? 'selected' : '' }}>{{ $rol->rol }}</option>
                        @endforeach
                    </select>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="telegram_chat_id" class="form-label">Telegram Chat ID</label>
                    <input type="text" class="form-control @error('telegram_chat_id') is-invalid @enderror" id="telegram_chat_id" name="telegram_chat_id" value="{{ old('telegram_chat_id', $usuario->telegram_chat_id) }}" placeholder="Ej: 123456789">
                    @error('telegram_chat_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NUEVO CAMPO --}}
                <div class="mb-3">
                    <label for="traccar_device_id" class="form-label">Traccar Device ID</label>
                    <input type="text" class="form-control @error('traccar_device_id') is-invalid @enderror" id="traccar_device_id" name="traccar_device_id" value="{{ old('traccar_device_id', $usuario->traccar_device_id) }}" placeholder="Ej: 62608172">
                    <small class="text-muted">El ID único del dispositivo en Traccar (opcional).</small>
                    @error('traccar_device_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contraseña" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control @error('contraseña') is-invalid @enderror" id="contraseña" name="contraseña" placeholder="Dejar en blanco para mantener la actual">
                    @error('contraseña')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
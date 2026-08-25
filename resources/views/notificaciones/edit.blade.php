@extends('layouts.app')

@section('page-title', 'Editar Notificación')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Notificación</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('notificaciones.update', $notificacion->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="usuario_id" class="form-label">Usuario *</label>
                    <select class="form-select @error('usuario_id') is-invalid @enderror" 
                            id="usuario_id" name="usuario_id" required>
                        <option value="">Seleccionar usuario</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->usuario }}" 
                                    {{ old('usuario_id', $notificacion->usuario_id) == $usuario->usuario ? 'selected' : '' }}>
                                {{ $usuario->nombre }} ({{ $usuario->usuario }})
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo *</label>
                    <select class="form-select @error('tipo') is-invalid @enderror" 
                            id="tipo" name="tipo" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="asignacion" {{ old('tipo', $notificacion->tipo) == 'asignacion' ? 'selected' : '' }}>Asignación</option>
                        <option value="reasignacion" {{ old('tipo', $notificacion->tipo) == 'reasignacion' ? 'selected' : '' }}>Reasignación</option>
                        <option value="liberacion" {{ old('tipo', $notificacion->tipo) == 'liberacion' ? 'selected' : '' }}>Liberación</option>
                        <option value="asignacion_masiva" {{ old('tipo', $notificacion->tipo) == 'asignacion_masiva' ? 'selected' : '' }}>Asignación Masiva</option>
                        <option value="sistema" {{ old('tipo', $notificacion->tipo) == 'sistema' ? 'selected' : '' }}>Sistema</option>
                    </select>
                    @error('tipo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje *</label>
                <textarea class="form-control @error('mensaje') is-invalid @enderror" 
                          id="mensaje" name="mensaje" rows="4" required>{{ old('mensaje', $notificacion->mensaje) }}</textarea>
                @error('mensaje')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="referencia_id" class="form-label">ID de Referencia</label>
                    <input type="number" class="form-control @error('referencia_id') is-invalid @enderror" 
                           id="referencia_id" name="referencia_id" value="{{ old('referencia_id', $notificacion->referencia_id) }}">
                    @error('referencia_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="leida" class="form-label">Estado</label>
                    <select class="form-select @error('leida') is-invalid @enderror" 
                            id="leida" name="leida" required>
                        <option value="0" {{ old('leida', $notificacion->leida) == 0 ? 'selected' : '' }}>No leída</option>
                        <option value="1" {{ old('leida', $notificacion->leida) == 1 ? 'selected' : '' }}>Leída</option>
                    </select>
                    @error('leida')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Notificación
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('page-title', 'Editar Permiso')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Permiso</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('permisos.update', $permiso->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre del Permiso *</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" name="nombre" value="{{ old('nombre', $permiso->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="slug" class="form-label">Slug *</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" name="slug" value="{{ old('slug', $permiso->slug) }}" required>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Identificador único para el permiso</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                          id="descripcion" name="descripcion" rows="2">{{ old('descripcion', $permiso->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="modulo" class="form-label">Módulo *</label>
                <select class="form-select @error('modulo') is-invalid @enderror" 
                        id="modulo" name="modulo" required>
                    <option value="">Seleccionar módulo</option>
                    @foreach($modulos as $value => $label)
                        <option value="{{ $value }}" {{ old('modulo', $permiso->modulo) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('modulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('permisos.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Permiso
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
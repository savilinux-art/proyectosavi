@extends('layouts.app')

@section('page-title', 'Nuevo Rol')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-plus-circle"></i> Crear Nuevo Rol</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rol" class="form-label">Nombre del Rol *</label>
                    <input type="text" class="form-control @error('rol') is-invalid @enderror" 
                           id="rol" name="rol" value="{{ old('rol') }}" 
                           placeholder="Ej: Supervisores" required>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Nombre único para el rol</small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Permisos</label>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach($permisos as $modulo => $permisosModulo)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">
                                                <i class="bi bi-folder"></i> 
                                                {{ ucfirst($modulo) }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($permisosModulo as $permiso)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="permisos[]" value="{{ $permiso->id }}" 
                                                           id="permiso_{{ $permiso->id }}">
                                                    <label class="form-check-label" for="permiso_{{ $permiso->id }}">
                                                        {{ $permiso->nombre }}
                                                        <br>
                                                        <small class="text-muted">{{ $permiso->descripcion }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <small class="text-muted">Selecciona los permisos que tendrá este rol</small>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Rol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('page-title', 'Editar Rol')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Rol</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.update', $rol->rol) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rol" class="form-label">Nombre del Rol *</label>
                    <input type="text" class="form-control @error('rol') is-invalid @enderror" 
                           id="rol" name="rol" value="{{ old('rol', $rol->rol) }}" required>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($rol->rol == 'Administrador')
                        <small class="text-warning">El rol Administrador es el principal del sistema</small>
                    @endif
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
                                                           id="permiso_{{ $permiso->id }}"
                                                           {{ in_array($permiso->id, $rolPermisos) ? 'checked' : '' }}
                                                           {{ $rol->rol == 'Administrador' ? 'disabled' : '' }}>
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
                @if($rol->rol == 'Administrador')
                    <small class="text-warning">El Administrador tiene todos los permisos automáticamente</small>
                @endif
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Rol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
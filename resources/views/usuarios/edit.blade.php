@extends('layouts.app')

@section('page-title', 'Editar Usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Usuario</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('usuarios.update', $usuario->usuario) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="usuario" class="form-label">Usuario *</label>
                    <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                           id="usuario" name="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>
                    @error('usuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre Completo *</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            

             <div class="col-md-6 mb-3">
                    <label for="telegram_chat_id" class="form-label">Telegram chat id *</label>
                    <input type="text" class="form-control @error('telegram_chat_id') is-invalid @enderror" 
                           id="telegram_chat_id" name="telegram_chat_id" value="{{ old('telegram_chat_id') }}" 
                           placeholder="Ej: 123456789" >
                            @error('telegram_chat_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>




            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label">Correo Electrónico *</label>
                    <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                           id="correo" name="correo" value="{{ old('correo', $usuario->correo) }}" required>
                    @error('correo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="rol" class="form-label">Rol *</label>
                    <select class="form-select @error('rol') is-invalid @enderror" 
                            id="rol" name="rol" required>
                        <option value="">Seleccionar rol</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->rol }}" 
                                    {{ old('rol', $usuario->rol) == $rol->rol ? 'selected' : '' }}>
                                {{ $rol->rol }}
                            </option>
                        @endforeach
                    </select>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contraseña" class="form-label">Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('contraseña') is-invalid @enderror" 
                               id="contraseña" name="contraseña" placeholder="Dejar vacío para mantener la actual">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('contraseña')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Mínimo 6 caracteres. Dejar vacío para mantener la contraseña actual</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="contraseña_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" 
                               id="contraseña_confirmation" name="contraseña_confirmation" 
                               placeholder="Repite la nueva contraseña">
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            @if($usuario->usuario == 'admin')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Usuario Administrador Principal</strong>
                    <p class="mb-0">Este es el usuario administrador principal del sistema. Algunas opciones de edición están limitadas por seguridad.</p>
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('contraseña');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            password.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const password = document.getElementById('contraseña_confirmation');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            password.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
@endpush
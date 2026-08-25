@extends('layouts.app')

@section('page-title', 'Roles y Permisos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-shield-lock"></i> Roles y Permisos</h1>
    <div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Rol
        </a>
        <a href="{{ route('permisos.index') }}" class="btn btn-info">
            <i class="bi bi-list-check"></i> Gestionar Permisos
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-person-badge"></i> Roles del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="rolesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rol</th>
                                <th>Usuarios</th>
                                <th>Permisos</th>
                                <th>Fecha Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $rol)
                            <tr>
                                <td>{{ $rol->id }}</td>
                                <td>
                                    <strong>{{ $rol->rol }}</strong>
                                    @if($rol->rol == 'Administrador')
                                        <span class="badge bg-danger">Principal</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $rol->usuarios_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $rol->permisos->count() }}</span>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" 
                                            data-bs-target="#permisosModal{{ $rol->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                                <td>{{ $rol->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('roles.edit', $rol->rol) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($rol->rol != 'Administrador')
                                            <form action="{{ route('roles.destroy', $rol->rol) }}" method="POST" 
                                                  onsubmit="return confirm('¿Eliminar este rol?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales para ver permisos -->
@foreach($roles as $rol)
<div class="modal fade" id="permisosModal{{ $rol->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Permisos de: {{ $rol->rol }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($rol->permisos->count() > 0)
                    <div class="list-group">
                        @foreach($rol->permisos as $permiso)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $permiso->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $permiso->descripcion }}</small>
                                    </div>
                                    <span class="badge bg-{{ $permiso->pivot->permitido ? 'success' : 'danger' }}">
                                        {{ $permiso->pivot->permitido ? 'Permitido' : 'Denegado' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">Este rol no tiene permisos asignados</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#rolesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
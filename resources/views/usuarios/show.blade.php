@extends('layouts.app')

@section('page-title', 'Detalle de Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person"></i> Detalle de Usuario</h1>
    <div>
        <a href="{{ route('usuarios.edit', $usuario->usuario) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Información del Usuario</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID</th>
                        <td>{{ $usuario->id }}</td>
                    </tr>
                    <tr>
                        <th>Usuario</th>
                        <td>
                            <strong>{{ $usuario->usuario }}</strong>
                            @if($usuario->usuario == 'admin')
                                <span class="badge bg-danger">Administrador Principal</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Nombre Completo</th>
                        <td>{{ $usuario->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Telegram chat id</th>
                        <td>{{ $usuario->telegram_chat_id }}</td>
                    </tr>
                    <tr>
                        <th>Correo Electrónico</th>
                        <td>{{ $usuario->correo }}</td>
                    </tr>
                    <tr>
                        <th>Rol</th>
                        <td>
                            @php
                                $rolColors = [
                                    'Administrador' => 'danger',
                                    'Ventas' => 'success',
                                    'Instalador' => 'primary',
                                    'Inventarios' => 'warning',
                                    'Contabilidad' => 'info',
                                    'Sistemas' => 'secondary'
                                ];
                                $color = $rolColors[$usuario->rol] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">
                                {{ $usuario->rol }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Fecha de Creación</th>
                        <td>{{ $usuario->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Última Actualización</th>
                        <td>{{ $usuario->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Resumen de Actividad</h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Ventas realizadas
                        <span class="badge bg-primary">{{ $usuario->ventas->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Instalaciones asignadas
                        <span class="badge bg-warning">{{ $usuario->instalacionesAsignadas->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Proyectos modificados
                        <span class="badge bg-info">{{ $usuario->proyectosModificados->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Inventario modificado
                        <span class="badge bg-secondary">{{ $usuario->inventarioModificado->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
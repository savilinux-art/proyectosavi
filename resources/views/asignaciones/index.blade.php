@extends('layouts.app')

@section('page-title', 'Asignaciones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-plus"></i> Gestión de Asignaciones</h1>
    <div>
        <a href="{{ route('asignaciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Asignación
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Instalaciones Pendientes</h5>
                <h2>{{ $estadisticas['total_pendientes'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Instaladores Activos</h5>
                <h2>{{ $estadisticas['instaladores_activos'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Instaladores Disponibles</h5>
                <h2>{{ $estadisticas['instaladores_disponibles'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Instalaciones Pendientes -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h5><i class="bi bi-clock"></i> Instalaciones Pendientes</h5>
            </div>
            <div class="card-body">
                @if($instalacionesPendientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($instalacionesPendientes as $instalacion)
                                <tr>
                                    <td>
                                        <strong>{{ $instalacion->proyecto->nombre_proyecto ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $instalacion->proyecto->titulo_venta ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $colors = ['preparacion' => 'secondary', 'en_proceso' => 'primary', 'programacion' => 'info'];
                                            $color = $colors[$instalacion->estatus_instalacion] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($instalacion->estatus_instalacion ?? 'N/A') }}
                                        </span>
                                        @if($instalacion->id_usuario_asignado)
                                            <br>
                                            <small class="text-success">
                                                <i class="bi bi-person-check"></i> 
                                                {{ $instalacion->usuarioAsignado->nombre ?? 'Asignado' }}
                                            </small>
                                        @else
                                            <br>
                                            <small class="text-danger">
                                                <i class="bi bi-person-x"></i> Sin asignar
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if(!$instalacion->id_usuario_asignado)
                                                <a href="{{ route('asignaciones.edit', $instalacion->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-person-plus"></i> Asignar
                                                </a>
                                            @else
                                                <a href="{{ route('asignaciones.edit', $instalacion->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                <form action="{{ route('asignaciones.destroy', $instalacion->id) }}" method="POST" 
                                                      onsubmit="return confirm('¿Liberar esta instalación?')" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-person-x"></i> Liberar
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
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 48px;"></i>
                        <p class="mt-2">No hay instalaciones pendientes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Instaladores -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-people"></i> Instaladores</h5>
            </div>
            <div class="card-body">
                @if($instaladores->count() > 0)
                    <div class="list-group">
                        @foreach($instaladores as $instalador)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $instalador->nombre }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $instalador->usuario }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $instalador->instalaciones_asignadas_count > 0 ? 'warning' : 'success' }}">
                                        {{ $instalador->instalaciones_asignadas_count }} asignaciones
                                    </span>
                                    <br>
                                    <small class="text-{{ $instalador->instalaciones_asignadas_count > 3 ? 'danger' : ($instalador->instalaciones_asignadas_count > 1 ? 'warning' : 'success') }}">
                                        {{ $instalador->instalaciones_asignadas_count > 3 ? 'Sobrecargado' : ($instalador->instalaciones_asignadas_count > 1 ? 'Carga media' : 'Disponible') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-person-x text-danger" style="font-size: 48px;"></i>
                        <p class="mt-2">No hay instaladores registrados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
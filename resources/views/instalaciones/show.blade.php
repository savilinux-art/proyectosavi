@extends('layouts.app')
@section('page-title', 'Detalle de Instalación')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tools"></i> Detalle de Instalación</h1>
    <div><a href="{{ route('instalaciones.edit', $instalacion->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a><a href="{{ route('instalaciones.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card"><div class="card-header"><h5>Información</h5></div><div class="card-body">
            <table class="table table-bordered">
                <tr><th width="30%">ID</th><td>{{ $instalacion->id }}</td></tr>
                <tr><th>Proyecto</th><td>{{ $instalacion->proyecto->nombre_proyecto ?? 'N/A' }}</td></tr>
                <tr><th>Instaladores</th><td>@foreach($instalacion->instaladores as $inst)<span class="badge bg-primary">{{ $inst->nombre }}</span>@endforeach @if($instalacion->instaladores->isEmpty())<span class="text-muted">Sin asignar</span>@endif</td></tr>
                <tr><th>Ubicación</th><td>{{ $instalacion->ubicacion_actual ?? 'No especificada' }}</td></tr>
                <tr><th>Fecha Inicio</th><td>{{ \Carbon\Carbon::parse($instalacion->fecha_hora_inicio)->format('d/m/Y H:i:s') }}</td></tr>
                <tr><th>Fecha Fin</th><td>{{ $instalacion->fecha_hora_fin ? \Carbon\Carbon::parse($instalacion->fecha_hora_fin)->format('d/m/Y H:i:s') : 'Pendiente' }}</td></tr>
                <tr><th>Estatus</th><td><span class="badge bg-{{ $instalacion->estatus_instalacion == 'entrega' ? 'success' : ($instalacion->estatus_instalacion == 'pruebas' ? 'warning' : 'primary') }}">{{ ucfirst($instalacion->estatus_instalacion ?? 'N/A') }}</span></td></tr>
                <tr><th>Checklist</th><td>@if($instalacion->check_list && count($instalacion->check_list)>0)@foreach($instalacion->check_list as $item)<span class="badge bg-success me-1">✓ {{ str_replace('_', ' ', ucfirst($item)) }}</span>@endforeach@else<span class="text-muted">No hay items</span>@endif</td></tr>
              
            </table>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header"><h6>Evidencias</h6></div><div class="card-body">
            <div class="list-group">
                @if($instalacion->evidencia_inicio)<div class="list-group-item"><i class="bi bi-file-image text-primary"></i> Evidencia Inicio <a href="#" class="btn btn-sm btn-primary float-end"><i class="bi bi-eye"></i></a></div>@endif
                @if($instalacion->incidencias)<div class="list-group-item"><i class="bi bi-exclamation-triangle text-danger"></i> Incidencias <a href="#" class="btn btn-sm btn-danger float-end"><i class="bi bi-eye"></i></a></div>@endif
                @if($instalacion->evidencia_fin)<div class="list-group-item"><i class="bi bi-check-circle text-success"></i> Evidencia Fin <a href="#" class="btn btn-sm btn-success float-end"><i class="bi bi-eye"></i></a></div>@endif
                @if(!$instalacion->evidencia_inicio && !$instalacion->incidencias && !$instalacion->evidencia_fin)<div class="text-center py-3 text-muted"><i class="bi bi-file-earmark" style="font-size:48px;"></i><p>No hay evidencias</p></div>@endif
            </div>
        </div></div>
    </div>
</div>
<h3 class="mt-4">📍 Ubicaciones registradas</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Instalador</th>
            <th>Tipo</th>
            <th>Fecha/Hora</th>
            <th>Ubicación</th>
        </tr>
    </thead>
    <tbody>
        @forelse($instalacion->ubicaciones->sortBy('fecha_hora') as $ubicacion)
        <tr>
            <td>{{ $ubicacion->usuario->nombre ?? 'N/A' }}</td>
            <td>
                <span class="badge {{ $ubicacion->tipo == 'inicio' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($ubicacion->tipo) }}
                </span>
            </td>
            <td>{{ \Carbon\Carbon::parse($ubicacion->fecha_hora)->format('d/m/Y H:i:s') }}</td>
            <td>
                <a href="https://www.google.com/maps?q={{ $ubicacion->latitud }},{{ $ubicacion->longitud }}" target="_blank">
                    <i class="bi bi-geo-alt"></i> Ver en mapa
                </a>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center text-muted">No hay ubicaciones registradas para esta instalación.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
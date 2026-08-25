@extends('layouts.app')
@section('page-title', 'Instalaciones de Instalador')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person"></i> Instalaciones de {{ $instalador->nombre }}</h1>
    <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div class="row mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Total</h6><h2>{{ $instalaciones->count() }}</h2></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-warning"><div class="card-body"><h6>En Proceso</h6><h2>{{ $instalaciones->where('estatus_instalacion','en_proceso')->count() }}</h2></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-info"><div class="card-body"><h6>Programadas</h6><h2>{{ $instalaciones->where('estatus_instalacion','programacion')->count() }}</h2></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-success"><div class="card-body"><h6>Completadas</h6><h2>{{ $instalaciones->where('estatus_instalacion','entrega')->count() }}</h2></div></div></div>
</div>
<div class="card"><div class="card-body">
    <table class="table table-striped" id="instalacionesTable">
        <thead><tr><th>ID</th><th>Proyecto</th><th>Inicio</th><th>Fin</th><th>Estatus</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($instalaciones as $i)
            <tr>
                <td>{{ $i->id }}</td>
                <td>{{ $i->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($i->fecha_hora_inicio)->format('d/m/Y H:i') }}</td>
                <td>{{ $i->fecha_hora_fin ? \Carbon\Carbon::parse($i->fecha_hora_fin)->format('d/m/Y H:i') : 'Pendiente' }}</td>
                <td><span class="badge bg-{{ $i->estatus_instalacion == 'entrega' ? 'success' : ($i->estatus_instalacion == 'pruebas' ? 'warning' : 'primary') }}">{{ ucfirst($i->estatus_instalacion ?? 'N/A') }}</span></td>
                <td><a href="{{ route('instalaciones.show', $i->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
@endsection
@push('scripts')
<script>$(document).ready(function(){$('#instalacionesTable').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'},order:[[0,'desc']]});});</script>
@endpush
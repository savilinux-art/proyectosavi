@extends('layouts.app')
@section('page-title', 'Devoluciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-arrow-return-left"></i> Devoluciones de Inventario</h1>
    <a href="{{ route('devoluciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva Devolución</a>
</div>
<div class="card"><div class="card-body">
    <table class="table table-striped" id="devolucionesTable">
        <thead><tr><th>ID</th><th>Proyecto</th><th>Devuelto por</th><th>Recibido por</th><th>Fecha</th><th>Productos</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($devoluciones as $d)
            <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->nombre_proyecto ?? 'N/A' }}</td>
                <td>{{ $d->devueltoPor->nombre ?? 'N/A' }}</td>
                <td>{{ $d->recibidoPor->nombre ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($d->fecha_hora_devolucion)->format('d/m/Y H:i') }}</td>
                <td><span class="badge bg-info">{{ count(json_decode($d->productos, true)) }}</span></td>
                <td><a href="{{ route('devoluciones.show', $d->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
@endsection
@push('scripts')
<script>$(document).ready(function(){$('#devolucionesTable').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'},order:[[0,'desc']]});});</script>
@endpush
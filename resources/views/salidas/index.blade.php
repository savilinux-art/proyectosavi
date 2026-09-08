@extends('layouts.app')
@section('page-title', 'Salidas de Inventario')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box-arrow-right"></i> Salidas de Inventario</h1>
    <a href="{{ route('salidas.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva Salida</a>
</div>
<div class="card"><div class="card-body">
    <table class="table table-striped" id="salidasTable">
        <thead><tr><th>ID</th><th>Proyecto</th><th>Entregado por</th><th>Entregado a</th><th>Fecha</th><th>Productos</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($salidas as $salida)
            <tr>
                <td>{{ $salida->id }}</td>
                <td>{{ $salida->nombre_proyecto }}</td>
                <td>{{ $salida->entregadoPor->nombre ?? 'N/A' }}</td>
                <td>{{ $salida->entregadoA->nombre ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($salida->fecha_hora_salida)->format('d/m/Y H:i') }}</td>
                <td><span class="badge bg-info">{{ $salida->detalles->count() }}</span></td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('salidas.show', $salida->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('salidas.download', $salida->id) }}" class="btn btn-sm btn-success"><i class="bi bi-file-pdf"></i></a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-printer"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('salidas.download', ['id' => $salida->id, 'copia' => 'administracion']) }}">Administración</a></li>
                                <li><a class="dropdown-item" href="{{ route('salidas.download', ['id' => $salida->id, 'copia' => 'cliente']) }}">Cliente</a></li>
                                <li><a class="dropdown-item" href="{{ route('salidas.download', ['id' => $salida->id, 'copia' => 'instalador']) }}">Instalador</a></li>
                            </ul>
                        </div>
                        <form action="{{ route('salidas.destroy', $salida->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta salida? Se restaurará el stock.')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
@endsection
@push('scripts')
<script>$(document).ready(function(){$('#salidasTable').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'},order:[[0,'desc']]});});</script>
@endpush
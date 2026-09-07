@extends('layouts.app')
@section('page-title', 'Ventas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-cart"></i> Ventas</h1>
    <div>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva Venta</a>
        <a href="{{ route('ventas.export') }}" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Exportar</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3"><div class="card text-white bg-primary"><div class="card-body"><h6>Total Ventas</h6><h3>{{ $ventas->count() }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-success"><div class="card-body"><h6>Ventas Ganadas</h6><h3>{{ $ventas->where('venta_ganada', true)->count() }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-danger"><div class="card-body"><h6>Ventas Perdidas</h6><h3>{{ $ventas->where('venta_ganada', false)->count() }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-warning"><div class="card-body"><h6>Monto Total</h6><h3>${{ number_format($ventas->sum('monto_venta'), 2) }}</h3></div></div></div>
</div>

<div class="card"><div class="card-body">
    <table class="table table-striped" id="ventasTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Proyecto</th>
                <th>Moneda</th>
                <th>Monto</th>
                <th>Vendedor</th>
                <th>Estatus</th>
                <th>Ganada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td><strong>{{ Str::limit($venta->titulo_venta, 30) }}</strong></td>
                <td>{{ $venta->nombre_proyecto }}</td>
                <td>{{ $venta->moneda }}</td>
                <td>${{ number_format($venta->monto_venta, 2) }}</td>
                <td>{{ $venta->vendedor->nombre ?? 'N/A' }}</td>
                <td><span class="badge bg-{{ $venta->estatus == 'cierre_venta' ? 'success' : ($venta->estatus == 'cotizacion' ? 'warning' : ($venta->estatus == 'levantamiento' ? 'info' : 'secondary')) }}">{{ ucfirst($venta->estatus ?? 'N/A') }}</span></td>
                <td>@if($venta->venta_ganada)<span class="badge bg-success"><i class="bi bi-check-circle"></i> Sí</span>@else<span class="badge bg-danger"><i class="bi bi-x-circle"></i> No</span>@endif</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('ventas.show', $venta->nombre_proyecto) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('ventas.edit', $venta->nombre_proyecto) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('ventas.destroy', $venta->nombre_proyecto) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta venta?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
@endsection
@push('scripts')
<script>$(document).ready(function(){$('#ventasTable').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'},order:[[0,'desc']]});});</script>
@endpush
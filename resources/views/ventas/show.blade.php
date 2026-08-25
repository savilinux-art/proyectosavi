@extends('layouts.app')
@section('page-title', 'Detalle de Venta')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-cart"></i> Detalle de Venta</h1>
    <div><a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a><a href="{{ route('ventas.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card"><div class="card-header"><h5>Información</h5></div><div class="card-body">
            <table class="table table-bordered">
                <tr><th width="30%">ID</th><td>{{ $venta->id }}</td></tr>
                <tr><th>Título</th><td>{{ $venta->titulo_venta }}</td></tr>
                <tr><th>Proyecto</th><td>{{ $venta->nombre_proyecto }}</td></tr>
                <tr><th>Moneda</th><td>{{ $venta->moneda }}</td></tr>
                <tr><th>Monto</th><td>${{ number_format($venta->monto_venta, 2) }}</td></tr>
                <tr><th>Requerimiento</th><td>{{ $venta->requerimiento_venta }}</td></tr>
                <tr><th>Vendedor</th><td>{{ $venta->vendedor->nombre ?? 'N/A' }}</td></tr>
                <tr><th>Fecha Levantamiento</th><td>{{ \Carbon\Carbon::parse($venta->fecha_hora_levantamiento)->format('d/m/Y H:i') }}</td></tr>
                <tr><th>Estatus</th><td><span class="badge bg-{{ $venta->estatus == 'cierre_venta' ? 'success' : ($venta->estatus == 'cotizacion' ? 'warning' : ($venta->estatus == 'levantamiento' ? 'info' : 'secondary')) }}">{{ ucfirst($venta->estatus ?? 'N/A') }}</span></td></tr>
                <tr><th>Venta Ganada</th><td>@if($venta->venta_ganada)<span class="badge bg-success">Sí</span>@else<span class="badge bg-danger">No</span>@endif</td></tr>
                <tr><th>Razón de Pérdida</th><td>{{ $venta->razon_perdida_venta ?? 'N/A' }}</td></tr>
            </table>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header"><h6>Documentos</h6></div><div class="card-body">
            <div class="list-group">
                @if($venta->cotizacion)<div class="list-group-item"><i class="bi bi-file-pdf text-danger"></i> Cotización <a href="#" class="btn btn-sm btn-danger float-end"><i class="bi bi-download"></i></a></div>@endif
                @if($venta->levantamiento)<div class="list-group-item"><i class="bi bi-file-earmark text-primary"></i> Levantamiento <a href="#" class="btn btn-sm btn-primary float-end"><i class="bi bi-download"></i></a></div>@endif
                @if(!$venta->cotizacion && !$venta->levantamiento)<div class="text-center py-3 text-muted"><i class="bi bi-file-earmark" style="font-size:48px;"></i><p>No hay documentos</p></div>@endif
            </div>
        </div></div>
    </div>
</div>
@endsection
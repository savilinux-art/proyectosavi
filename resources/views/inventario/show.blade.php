@extends('layouts.app')
@section('page-title', 'Detalle de Salida')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box-arrow-right"></i> Detalle de Salida #{{ $salida->id }}</h1>
    <div>
        <a href="{{ route('salidas.download', $salida->id) }}" class="btn btn-success"><i class="bi bi-file-pdf"></i> PDF General</a>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div class="card"><div class="card-header"><h5>Información</h5></div><div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr><th width="30%">ID</th><td>{{ $salida->id }}</td></tr>
                <tr><th>Proyecto</th><td>{{ $salida->nombre_proyecto }}</td></tr>
                <tr><th>Entregado por</th><td>{{ $salida->entregadoPor->nombre ?? 'N/A' }}</td></tr>
                <tr><th>Entregado a</th><td>{{ $salida->entregadoA->nombre ?? 'N/A' }}</td></tr>
                <tr><th>Fecha y hora</th><td>{{ \Carbon\Carbon::parse($salida->fecha_hora_salida)->format('d/m/Y H:i:s') }}</td></tr>
                <tr><th>Observaciones</th><td>{{ $salida->observaciones ?? 'Sin observaciones' }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <div class="card"><div class="card-header bg-success text-white"><h6>Productos entregados</h6></div><div class="card-body">
                <table class="table table-striped"><thead><tr><th>#</th><th>Modelo</th><th>Descripción</th><th>Cantidad</th></tr></thead><tbody>
                @php $total=0; @endphp
                @foreach(json_decode($salida->productos, true) as $idx => $item)
                <tr><td>{{ $idx+1 }}</td><td>{{ $item['modelo'] ?? 'N/A' }}</td><td>{{ $item['descripcion'] ?? 'N/A' }}</td><td>{{ $item['cantidad'] }}</td></tr>
                @php $total += $item['cantidad']; @endphp
                @endforeach
                <tr class="fw-bold"><td colspan="3" class="text-end">Total:</td><td>{{ $total }}</td></tr>
                </tbody></table>
            </div></div>
        </div>
    </div>
</div></div>
@endsection
@extends('layouts.app')
@section('page-title', 'Detalle de Devolución')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-arrow-return-left"></i> Detalle de Devolución #{{ $devolucion->id }}</h1>
    <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div class="card"><div class="card-header"><h5>Información</h5></div><div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr><th width="30%">ID</th><td>{{ $devolucion->id }}</td></tr>
                <tr><th>Proyecto</th><td>{{ $devolucion->nombre_proyecto ?? 'N/A' }}</td></tr>
                <tr><th>Devuelto por</th><td>{{ $devolucion->devueltoPor->nombre ?? 'N/A' }}</td></tr>
                <tr><th>Recibido por</th><td>{{ $devolucion->recibidoPor->nombre ?? 'N/A' }}</td></tr>
                <tr><th>Fecha y hora</th><td>{{ \Carbon\Carbon::parse($devolucion->fecha_hora_devolucion)->format('d/m/Y H:i:s') }}</td></tr>
                <tr><th>Observaciones</th><td>{{ $devolucion->observaciones ?? 'Sin observaciones' }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <div class="card"><div class="card-header bg-success text-white"><h6>Productos devueltos</h6></div><div class="card-body">
                <table class="table table-striped"><thead><tr><th>#</th><th>Modelo</th><th>Descripción</th><th>Cantidad</th></tr></thead><tbody>
                @php $total=0; @endphp
                @foreach(json_decode($devolucion->productos, true) as $idx => $item)
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
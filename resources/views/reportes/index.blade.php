@extends('layouts.app')
@section('page-title', 'Reportes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-bar-chart"></i> Reportes</h1>
    <a href="{{ route('reportes.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Generar Reporte</a>
</div>

<div class="row mb-4">
    <div class="col-md-3"><div class="card text-white bg-primary stat-card"><div class="card-body"><h6>Ventas Totales</h6><h2>{{ $reportes['ventas']['total'] }}</h2></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-success stat-card"><div class="card-body"><h6>Ventas Ganadas</h6><h2>{{ $reportes['ventas']['ganadas'] }}</h2></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-warning stat-card"><div class="card-body"><h6>Instalaciones</h6><h2>{{ $reportes['instalaciones']['total'] }}</h2></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-info stat-card"><div class="card-body"><h6>Clientes</h6><h2>{{ $reportes['clientes']['total'] }}</h2></div></div></div>
</div>

<div class="row">
    <div class="col-md-6 mb-4"><div class="card"><div class="card-header bg-primary text-white"><h5>Ventas</h5></div><div class="card-body"><div class="row"><div class="col-6"><div class="text-center p-3 border rounded"><h6>Monto Total</h6><h4 class="text-success">${{ number_format($reportes['ventas']['monto_total'] ?? 0, 2) }}</h4></div></div><div class="col-6"><div class="text-center p-3 border rounded"><h6>Ventas Perdidas</h6><h4 class="text-danger">{{ $reportes['ventas']['perdidas'] ?? 0 }}</h4></div></div></div><hr><h6>Por Estatus</h6>@foreach($reportes['ventas']['por_estatus'] ?? [] as $e)<div class="d-flex justify-content-between"><span>{{ ucfirst($e->estatus) }}</span><span class="badge bg-primary">{{ $e->total }}</span></div>@endforeach</div></div></div>
    <div class="col-md-6 mb-4"><div class="card"><div class="card-header bg-success text-white"><h5>Inventario</h5></div><div class="card-body"><div class="row"><div class="col-6"><div class="text-center p-3 border rounded"><h6>Total Productos</h6><h4>{{ $reportes['inventario']['total_productos'] ?? 0 }}</h4></div></div><div class="col-6"><div class="text-center p-3 border rounded"><h6>Existencia Total</h6><h4>{{ $reportes['inventario']['total_existencia'] ?? 0 }}</h4></div></div></div><hr><div class="row"><div class="col-6"><div class="text-center p-2 border rounded bg-danger text-white"><h6>Stock Bajo</h6><h4>{{ $reportes['inventario']['bajo_inventario'] ?? 0 }}</h4></div></div><div class="col-6"><div class="text-center p-2 border rounded bg-dark text-white"><h6>Sin Stock</h6><h4>{{ $reportes['inventario']['sin_stock'] ?? 0 }}</h4></div></div></div><hr><h6>Por Categoría</h6>@foreach($reportes['inventario']['por_categoria'] ?? [] as $c)<div class="d-flex justify-content-between"><span>{{ $c->categoria }}</span><span class="badge bg-secondary">{{ $c->total }} ({{ $c->existencia }})</span></div>@endforeach</div></div></div>
</div>
@endsection
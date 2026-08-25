@extends('layouts.app')

@section('page-title', 'Detalle de Salida')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box-arrow-right"></i> Detalle de Salida</h1>
    <div>
        <a href="{{ route('salidas.download', $salida->id) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Descargar PDF
        </a>
        <a href="{{ route('salidas.imprimir', $salida->id) }}" class="btn btn-warning" target="_blank">
            <i class="bi bi-printer"></i> Imprimir (3 copias)
        </a>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Información General</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Folio</th>
                        <td><strong>{{ $salida->folio }}</strong></td>
                    </tr>
                    <tr>
                        <th>Proyecto</th>
                        <td>{{ $salida->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Entregado por</th>
                        <td>{{ $salida->entregadoPor->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Entregado a</th>
                        <td>{{ $salida->entregadoA->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha y hora</th>
                        <td>{{ $salida->fecha_hora_salida->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Observaciones</th>
                        <td>{{ $salida->observaciones ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Resumen de Productos</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h2 class="text-primary">{{ count($salida->productos) }}</h2>
                    <p class="text-muted">Productos entregados</p>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <div class="text-center border rounded p-2">
                            <h6>Total unidades</h6>
                            <h3>{{ collect($salida->productos)->sum('cantidad') }}</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center border rounded p-2">
                            <h6>Fecha</h6>
                            <h5>{{ $salida->fecha_hora_salida->format('d/m/Y') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>Lista de Productos</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Modelo</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salida->productos as $index => $producto)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $producto['modelo'] }}</td>
                        <td>{{ $producto['descripcion'] }}</td>
                        <td><span class="badge bg-primary">{{ $producto['cantidad'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>{{ collect($salida->productos)->sum('cantidad') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
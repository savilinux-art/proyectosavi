@extends('layouts.app')
@section('page-title', 'Detalle de Salida #' . $salida->id)
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="bi bi-box-arrow-right"></i> Detalle de Salida #{{ $salida->id }}</h4>
        <div>
            <a href="{{ route('salidas.download', $salida->id) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-pdf"></i> PDF General
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-printer"></i> Copias
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('salidas.download', ['id' => $salida->id, 'copia' => 'administracion']) }}">Administración</a></li>
                    <li><a class="dropdown-item" href="{{ route('salidas.download', ['id' => $salida->id, 'copia' => 'cliente']) }}">Cliente</a></li>
                    <li><a class="dropdown-item" href="{{ route('salidas.download', ['id' => $salida->id, 'copia' => 'instalador']) }}">Instalador</a></li>
                </ul>
            </div>
            <a href="{{ route('salidas.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Información general -->
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Proyecto:</strong>
                <span class="badge bg-primary">{{ $salida->nombre_proyecto }}</span>
            </div>
            <div class="col-md-6">
                <strong>Fecha:</strong>
                {{ \Carbon\Carbon::parse($salida->fecha_hora_salida)->format('d/m/Y H:i') }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Entregado por:</strong>
                {{ $salida->entregadoPor->nombre ?? 'N/A' }}
                <small class="text-muted">({{ $salida->entregadoPor->usuario ?? '' }})</small>
            </div>
            <div class="col-md-6">
                <strong>Entregado a:</strong>
                {{ $salida->entregadoA->nombre ?? 'N/A' }}
                <small class="text-muted">({{ $salida->entregadoA->usuario ?? '' }})</small>
            </div>
        </div>

        <!-- Tabla de productos -->
        <h5 class="mt-4">Productos entregados</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Modelo</th>
                        <th>Descripción</th>
                        <th>Marca</th>
                        <th class="text-end">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $item['modelo'] ?? 'N/A' }}</strong></td>
                            <td>{{ $item['descripcion'] ?? 'N/A' }}</td>
                            <td>{{ $item['marca'] ?? 'N/A' }}</td>
                            <td class="text-end">
                                <span class="badge bg-info">{{ $item['cantidad'] ?? 0 }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size:24px;"></i>
                                <p class="mb-0">No hay productos en esta salida</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <th colspan="4" class="text-end">Total de productos:</th>
                        <th class="text-end">{{ count($productos) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Observaciones -->
        @if($salida->observaciones)
            <div class="mt-4">
                <strong>Observaciones:</strong>
                <div class="p-2 bg-light rounded">{{ $salida->observaciones }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
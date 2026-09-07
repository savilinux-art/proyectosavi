@extends('layouts.app')

@section('page-title', 'Detalle de Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-cart"></i> Detalle de Venta</h1>
    <div>
        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Imagen -->
            <div class="col-md-3 text-center">
                @if($inventario->imagen_url)
                    <img src="{{ $inventario->imagen_url }}" class="img-fluid img-thumbnail" alt="Producto" style="max-height: 200px;">
                @else
                    <i class="bi bi-image" style="font-size: 100px; color: #ccc;"></i>
                @endif
            </div>

            <!-- Datos del producto -->
            <div class="col-md-9">
                <h3>{{ $inventario->modelo }}</h3>
                <p><strong>Descripción:</strong> {{ $inventario->descripcion }}</p>
                <p><strong>Marca:</strong> {{ $inventario->marca }}</p>
                <p><strong>Categoría:</strong> {{ $inventario->categoriaRelacion->nombre_categoria ?? 'N/A' }}</p>
                <p>
                    <strong>Existencia:</strong>
                    @if($inventario->existencia == 0)
                        <span class="badge bg-danger">{{ $inventario->existencia }}</span>
                    @elseif($inventario->existencia < 10)
                        <span class="badge bg-warning">{{ $inventario->existencia }}</span>
                    @else
                        <span class="badge bg-success">{{ $inventario->existencia }}</span>
                    @endif
                </p>
                <p><strong>Almacén:</strong> {{ $inventario->almacen }}</p>
                <p><strong>APEA:</strong> {{ $inventario->apea ?? 'N/A' }}</p>
                <p><strong>Comentarios:</strong> {{ $inventario->comentarios ?? 'N/A' }}</p>
                <p><strong>Última modificación:</strong> {{ \Carbon\Carbon::parse($inventario->fecha_modificacion)->format('d/m/Y H:i') }}</p>
                <p><strong>Modificado por:</strong> {{ $inventario->modificadoPor->nombre ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Resumen de movimientos (opcional) -->
        <div class="mt-4">
            <h5><i class="bi bi-arrow-left-right"></i> Resumen de movimientos</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h6 class="card-title">Entradas</h6>
                            <h3>{{ $totalEntradas ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h6 class="card-title">Salidas</h6>
                            <h3>{{ $totalSalidas ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h6 class="card-title">Ajustes</h6>
                            <h3>{{ $totalAjustes ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h6 class="card-title">Devoluciones</h6>
                            <h3>{{ $totalDevoluciones ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de movimientos detallados (opcional) -->
        @if($movimientos->count() > 0)
        <div class="mt-4">
            <h5><i class="bi bi-list-ul"></i> Historial de movimientos</h5>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Modificado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $mov)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($mov->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($mov->entrada) <span class="badge bg-success">Entrada</span>
                                @elseif($mov->salida) <span class="badge bg-danger">Salida</span>
                                @elseif($mov->ajuste) <span class="badge bg-warning">Ajuste</span>
                                @elseif($mov->devolucion) <span class="badge bg-info">Devolución</span>
                                @elseif($mov->apartado) <span class="badge bg-secondary">Apartado</span>
                                @endif
                            </td>
                            <td>
                                @if($mov->entrada) +{{ $mov->entrada }}
                                @elseif($mov->salida) -{{ $mov->salida }}
                                @elseif($mov->ajuste) {{ $mov->ajuste }}
                                @elseif($mov->devolucion) +{{ $mov->devolucion }}
                                @elseif($mov->apartado) -{{ $mov->apartado }}
                                @endif
                            </td>
                            <td>{{ $mov->modificadoPor->nombre ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.app')

@section('page-title', 'Cotización ' . $cotizacion->folio)

@section('content')

@if (!$cotizacion)
    <div class="alert alert-danger">La cotización no existe o ha sido eliminada.</div>
    <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Volver al listado</a>
@else



<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-file-earmark-text"></i> Cotización {{ $cotizacion->folio }}</h1>
    <div>
        <a href="{{ route('cotizaciones.pdf', $cotizacion) }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-pdf"></i> PDF
        </a>
        <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Información general -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width:40%;">Folio:</th>
                        <td><strong>{{ $cotizacion->folio }}</strong></td>
                    </tr>
                    <tr>
                        <th>Fecha de emisión:</th>
                        <td>{{ \Carbon\Carbon::parse($cotizacion->fecha_emision)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de validez:</th>
                        <td>{{ $cotizacion->fecha_validez ? \Carbon\Carbon::parse($cotizacion->fecha_validez)->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Moneda:</th>
                        <td><span class="badge bg-info">{{ $cotizacion->moneda }}</span></td>
                    </tr>
                    <tr>
                        <th>Estatus:</th>
                        <td>
                            @php
                                $estatusColors = [
                                    'borrador' => 'secondary',
                                    'enviada' => 'primary',
                                    'aprobada' => 'success',
                                    'rechazada' => 'danger',
                                    'facturada' => 'info'
                                ];
                            @endphp
                            <span class="badge bg-{{ $estatusColors[$cotizacion->estatus] ?? 'secondary' }}">
                                {{ ucfirst($cotizacion->estatus) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Creado por:</th>
                        <td>{{ $cotizacion->creador->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de creación:</th>
                        <td>{{ \Carbon\Carbon::parse($cotizacion->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Cliente y Proyecto -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-people"></i> Cliente y Proyecto</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width:40%;">Cliente:</th>
                        <td><strong>{{ $cotizacion->cliente->razon_social ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <th>RFC:</th>
                        <td>{{ $cotizacion->cliente->rfc ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Correo:</th>
                        <td>{{ $cotizacion->cliente->correo_electronico ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Proyecto:</th>
                        <td><strong>{{ $cotizacion->proyecto->nombre_proyecto ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Condiciones:</th>
                        <td style="white-space: pre-wrap;">{{ $cotizacion->condiciones ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Productos -->
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="bi bi-list-ul"></i> Productos / Servicios</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Precio Unitario</th>
                        <th class="text-end">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cotizacion->detalles as $index => $detalle)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $detalle->descripcion }}
                                @if($detalle->inventario)
                                    <br><small class="text-muted">Modelo: {{ $detalle->inventario->modelo ?? 'N/A' }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $detalle->cantidad }}</td>
                            <td class="text-end">{{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td class="text-end"><strong>{{ number_format($detalle->importe, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size: 24px;"></i>
                                <p class="mb-0">No hay productos en esta cotización</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <th colspan="4" class="text-end">Subtotal:</th>
                        <th class="text-end">{{ number_format($cotizacion->subtotal, 2) }}</th>
                    </tr>
                    <tr class="table-secondary">
                        <th colspan="4" class="text-end">IVA (16%):</th>
                        <th class="text-end">{{ number_format($cotizacion->iva, 2) }}</th>
                    </tr>
                    <tr class="table-success">
                        <th colspan="4" class="text-end"><strong>TOTAL:</strong></th>
                        <th class="text-end"><strong>{{ number_format($cotizacion->total, 2) }}</strong></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Botones de acción rápida -->
<div class="mt-4 d-flex justify-content-end gap-2">
    <button type="button" class="btn btn-success" onclick="window.print()">
        <i class="bi bi-printer"></i> Imprimir
    </button>
    <button type="button" class="btn btn-primary" onclick="window.open('{{ route('cotizaciones.pdf', $cotizacion) }}', '_blank')">
        <i class="bi bi-file-pdf"></i> Descargar PDF
    </button>
    @if($cotizacion->estatus == 'borrador')
        <form action="{{ route('cotizaciones.enviar', $cotizacion) }}" method="POST" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-warning" onclick="return confirm('¿Enviar esta cotización al cliente?')">
                <i class="bi bi-send"></i> Enviar
            </button>
        </form>
    @endif
</div>
@endif
@endsection
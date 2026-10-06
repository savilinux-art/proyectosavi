@extends('layouts.app')

@section('page-title', 'Venta #' . $ventaMostrador->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-cart-check"></i> Venta #{{ $ventaMostrador->id }}</h1>
    <div>
        @if($ventaMostrador->puedeEditarse())
            <a href="{{ route('ventas_mostrador.edit', $ventaMostrador) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
        @endif
        @if(!empty($ventaMostrador->estadosPermitidos()))
            <form action="{{ route('ventas_mostrador.cambiarEstado', $ventaMostrador) }}" method="POST" class="d-inline-flex align-items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="estado" class="form-select form-select-sm" style="width:auto;">
                    <option value="">Cambiar estado a...</option>
                    @foreach($ventaMostrador->estadosPermitidos() as $est)
                        <option value="{{ $est }}">{{ ucfirst($est) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary" onclick="return confirm('¿Cambiar el estado de esta venta?')">
                    <i class="bi bi-arrow-repeat"></i> Aplicar
                </button>
            </form>
        @endif
        <a href="{{ route('ventas_mostrador.pdf', $ventaMostrador) }}" class="btn btn-outline-primary" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Imprimir PDF
        </a>
        <a href="{{ route('ventas_mostrador.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><strong>Datos generales</strong></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Proyecto:</dt>
                    <dd class="col-sm-8">{{ $ventaMostrador->proyecto->nombre_proyecto ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Estado:</dt>
                    <dd class="col-sm-8">
                        @php
                            $badge = match($ventaMostrador->estado) {
                                'completada' => 'success',
                                'cancelada'  => 'danger',
                                default      => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($ventaMostrador->estado) }}</span>
                    </dd>

                    <dt class="col-sm-4">Moneda:</dt>
                    <dd class="col-sm-8">{{ $ventaMostrador->moneda ?? 'MXN' }}</dd>

                    <dt class="col-sm-4">Subtotal:</dt>
                    <dd class="col-sm-8">{{ $ventaMostrador->moneda }} {{ number_format($ventaMostrador->subtotal, 2) }}</dd>

                    <dt class="col-sm-4">IVA (16%):</dt>
                    <dd class="col-sm-8">{{ $ventaMostrador->moneda }} {{ number_format($ventaMostrador->iva, 2) }}</dd>

                    <dt class="col-sm-4">Total:</dt>
                    <dd class="col-sm-8"><strong>{{ $ventaMostrador->moneda }} {{ number_format($ventaMostrador->total, 2) }}</strong></dd>

                    <dt class="col-sm-4">Creado por:</dt>
                    <dd class="col-sm-8">{{ $ventaMostrador->creado_por }}</dd>

                    <dt class="col-sm-4">Fecha:</dt>
                    <dd class="col-sm-8">{{ $ventaMostrador->created_at?->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
           <div class="card-header"><strong>Condiciones</strong></div>
            <div class="card-body">
                {!! nl2br(e($ventaMostrador->observaciones ?? '—')) !!}
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header"><strong>Productos</strong></div>
    <div class="card-body">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>#</th><th>Modelo</th><th>Descripción</th>
                    <th class="text-end">Cantidad</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Descuento</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventaMostrador->detalles as $i => $d)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $d->inventario->modelo ?? '—' }}</td>
                    <td>{{ $d->inventario->descripcion ?? '—' }}</td>
                    <td class="text-end">{{ $d->cantidad }}</td>
                    <td class="text-end">{{ $ventaMostrador->moneda }} {{ number_format($d->precio_unitario, 2) }}</td>
                    <td class="text-end">{{ $ventaMostrador->moneda }} {{ number_format($d->descuento, 2) }}</td>
                    <td class="text-end">{{ $ventaMostrador->moneda }} {{ number_format($d->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" class="text-end">Subtotal:</th>
                    <th class="text-end">{{ $ventaMostrador->moneda }} {{ number_format($ventaMostrador->subtotal, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="6" class="text-end">IVA (16%):</th>
                    <th class="text-end">{{ $ventaMostrador->moneda }} {{ number_format($ventaMostrador->iva, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="6" class="text-end">Total:</th>
                    <th class="text-end">{{ $ventaMostrador->moneda }} {{ number_format($ventaMostrador->total, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>Salidas de inventario vinculadas</strong></div>
    <div class="card-body">
        @if($ventaMostrador->salidas->isEmpty())
            <p class="text-muted mb-2">Aún no hay salidas de inventario generadas.</p>
            <button class="btn btn-primary" disabled title="Disponible en E.3">
                <i class="bi bi-box-arrow-up"></i> Generar salida de inventario
            </button>
            <small class="text-muted d-block mt-1">(Función disponible en la próxima fase)</small>
        @else
            <ul class="list-group">
                @foreach($ventaMostrador->salidas as $s)
                    <li class="list-group-item">
                        Salida #{{ $s->id }} — {{ $s->fecha_hora_salida?->format('d/m/Y H:i') }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
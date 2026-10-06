@extends('layouts.app')

@section('page-title', 'Ventas de Mostrador')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-cart-check"></i> Ventas de Mostrador</h1>
    <a href="{{ route('ventas_mostrador.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Venta
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Proyecto</label>
                <select name="proyecto_id" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id }}" {{ request('proyecto_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre_proyecto }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Estado</label>
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(\App\Models\VentaMostrador::ESTADOS as $e)
                        <option value="{{ $e }}" {{ request('estado') == $e ? 'selected' : '' }}>
                            {{ ucfirst($e) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel"></i> Filtrar</button>
                <a href="{{ route('ventas_mostrador.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped" id="ventasMostradorTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Estado</th>
                    <th>Moneda</th>
                    <th>Subtotal</th>
                    <th>IVA</th>
                    <th>Total</th>
                    <th>Creado por</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $v)
                <tr>
                    <td><strong>#{{ $v->id }}</strong></td>
                    <td>{{ $v->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                    <td>
                        @php
                            $badge = match($v->estado) {
                                'completada' => 'success',
                                'cancelada'  => 'danger',
                                default      => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($v->estado) }}</span>
                    </td>
                    <td>{{ $v->moneda ?? 'MXN' }}</td>
                    <td>{{ $v->moneda }} {{ number_format($v->subtotal, 2) }}</td>
                    <td>{{ $v->moneda }} {{ number_format($v->iva, 2) }}</td>
                    <td><strong>{{ $v->moneda }} {{ number_format($v->total, 2) }}</strong></td>
                    <td>{{ $v->creado_por }}</td>
                    <td>{{ $v->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('ventas_mostrador.show', $v) }}" class="btn btn-sm btn-info" title="Ver"><i class="bi bi-eye"></i></a>
                            @if($v->puedeEditarse())
                                <a href="{{ route('ventas_mostrador.edit', $v) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            @endif
                            <a href="{{ route('ventas_mostrador.pdf', $v) }}" class="btn btn-sm btn-outline-danger" title="PDF" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#ventasMostradorTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            order: [[0, 'desc']],
            pageLength: 10
        });
    });
</script>
@endpush
@endsection
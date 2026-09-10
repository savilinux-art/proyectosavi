@extends('layouts.app')

@section('page-title', 'Cotizaciones / Presupuestos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-file-earmark-text"></i> Cotizaciones</h1>
    <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Cotización
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped" id="cotizacionesTable">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Proyecto</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cotizaciones as $c)
                <tr>
                    <td><strong>{{ $c->folio }}</strong></td>
                    <td>{{ $c->cliente->razon_social ?? 'N/A' }}</td>
                    <td>{{ $c->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                    <td>{{ $c->fecha_emision->format('d/m/Y') }}</td>
                    <td>{{ $c->moneda }} {{ number_format($c->total, 2) }}</td>
                    <td>
                        @php
                            $badgeColor = match($c->estatus) {
                                'aprobada' => 'success',
                                'enviada' => 'info',
                                'rechazada' => 'danger',
                                'facturada' => 'primary',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }}">
                            {{ ucfirst($c->estatus) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('cotizaciones.show', $c) }}" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('cotizaciones.pdf', $c) }}" class="btn btn-sm btn-danger" title="PDF" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            <a href="{{ route('cotizaciones.edit', $c) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($c->estatus == 'borrador')
                            <form action="{{ route('cotizaciones.enviar', $c) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="Enviar" onclick="return confirm('¿Enviar esta cotización?')">
                                    <i class="bi bi-send"></i>
                                </button>
                            </form>
                            @endif
                            @if($c->estatus != 'aprobada' && $c->estatus != 'facturada')
                            <form action="{{ route('cotizaciones.destroy', $c) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar esta cotización?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
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
        $('#cotizacionesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 10
        });
    });
</script>
@endpush
@endsection
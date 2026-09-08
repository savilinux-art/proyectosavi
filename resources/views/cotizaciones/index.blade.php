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
                    <td>{{ $c->proyecto_id }}</td>
                    <td>{{ $c->fecha_emision->format('d/m/Y') }}</td>
                    <td>{{ $c->moneda }} {{ number_format($c->total, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $c->estatus == 'aprobada' ? 'success' : ($c->estatus == 'enviada' ? 'info' : 'secondary') }}">
                            {{ ucfirst($c->estatus) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('cotizaciones.show', $c) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('cotizaciones.pdf', $c) }}" class="btn btn-sm btn-danger"><i class="bi bi-file-pdf"></i></a>
                            <a href="{{ route('cotizaciones.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
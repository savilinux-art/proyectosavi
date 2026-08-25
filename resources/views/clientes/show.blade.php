@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person"></i> Detalle del Cliente</h1>
    <div>
        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Información del Cliente</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">ID</th>
                        <td>{{ $cliente->id }}</td>
                    </tr>
                    <tr>
                        <th>RFC</th>
                        <td>{{ $cliente->rfc }}</td>
                    </tr>
                    <tr>
                        <th>Razón Social</th>
                        <td>{{ $cliente->razon_social }}</td>
                    </tr>
                    <tr>
                        <th>Proyecto</th>
                        <td>{{ $cliente->nombre_proyecto ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Régimen Fiscal</th>
                        <td>{{ $cliente->regimen_fiscal }}</td>
                    </tr>
                    <tr>
                        <th>Código Postal</th>
                        <td>{{ $cliente->codigo_postal }}</td>
                    </tr>
                    <tr>
                        <th>Correo Electrónico</th>
                        <td>{{ $cliente->correo_electronico }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Creación</th>
                        <td>{{ $cliente->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Última Actualización</th>
                        <td>{{ $cliente->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Constancia de Situación Fiscal</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($cliente->constancia_situacion_fiscal)
                            <i class="bi bi-file-pdf" style="font-size: 64px; color: #dc3545;"></i>
                            <p class="mt-2">
                                <a href="#" class="btn btn-danger">
                                    <i class="bi bi-download"></i> Descargar PDF
                                </a>
                            </p>
                        @else
                            <i class="bi bi-file-earmark" style="font-size: 64px; color: #6c757d;"></i>
                            <p class="mt-2 text-muted">No hay documento disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
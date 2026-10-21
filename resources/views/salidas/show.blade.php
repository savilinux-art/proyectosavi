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

        <!-- Tabla de productos (usando la relación detalles) -->
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
                    @forelse($productos as $index => $detalle)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $detalle->inventario->modelo ?? 'N/A' }}</strong></td>
                            <td>{{ $detalle->inventario->descripcion ?? 'N/A' }}</td>
                            <td>{{ $detalle->inventario->marca ?? 'N/A' }}</td>
                            <td class="text-end">
                                <span class="badge bg-info">{{ $detalle->cantidad }}</span>
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

{{-- B-05: Evidencia de entrega --}}
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-paperclip"></i> Evidencia de entrega</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php
            $evidenciasVigentes = $salida->evidencias()->vigente()->orderByDesc('id')->get();
        @endphp

        @if($evidenciasVigentes->isNotEmpty())
            <h6 class="text-muted">Evidencias vigentes</h6>
            <ul class="list-group mb-3">
                @foreach($evidenciasVigentes as $ev)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <span class="badge bg-info">{{ $ev->tipo }}</span>
                            {{ $ev->archivo_nombre_original }}
                            <small class="text-muted">({{ number_format($ev->archivo_tamano / 1024, 1) }} KB)</small>
                        </span>
                        <small class="text-muted">{{ $ev->created_at?->format('d/m/Y H:i') }}</small>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Aún no hay evidencia vigente para esta salida.</p>
        @endif

        <form action="{{ route('salidas.evidencias.store', $salida->id) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="firma_cliente">Firma cliente</option>
                        <option value="pdf_cliente_firmado">PDF cliente firmado</option>
                        <option value="foto_entrega">Foto entrega</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Archivo (JPG/PNG/PDF, ≤10 MB)</label>
                    <input type="file" name="archivo" class="form-control form-control-sm"
                           accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Notas (opcional)</label>
                    <input type="text" name="notas" class="form-control form-control-sm" maxlength="2000">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100" type="submit">
                        <i class="bi bi-upload"></i> Subir
                    </button>
                </div>
            </div>
            @if($errors->any())
                <div class="alert alert-danger mt-2 mb-0">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box-seam"></i> Lista de materiales — {{ $cotizacion->folio }}</h1>
    <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            {{ $cotizacion->proyecto->nombre_proyecto ?? 'Proyecto' }}
           @if($cotizacion->proyecto?->cliente)
                — {{ $cotizacion->proyecto->cliente->razon_social }}
            @endif
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Vista de surtido — sin precios. Usar para preparar los materiales a entregar.
        </p>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th width="50">#</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Descripción</th>
                    <th class="text-end" width="100">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cotizacion->detalles as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->inventario->modelo ?? '—' }}</td>
                        <td>{{ $d->inventario->marca ?? '—' }}</td>
                        <td>{{ $d->descripcion }}</td>
                        <td class="text-end">{{ $d->cantidad }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Sin materiales en esta cotización.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('page-title', 'Detalle de Venta')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-cart"></i> Detalle de Venta</h1>
    <div><a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning">Editar</a>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card"><div class="card-header"><h5>Información</h5></div><div class="card-body">
            <table class="table table-bordered">
                <tr><th width="30%">ID</th><td>{{ $venta->id }}</td></tr>
                <tr><th>Proyecto</th><td>{{ $venta->nombre_proyecto }}</td></tr>
                <tr><th>Moneda</th><td>{{ $venta->moneda }}</td></tr>
                <tr><th>Requerimiento</th><td>{{ $venta->requerimiento_venta }}</td></tr>
                <tr><th>Vendedor</th><td>{{ $venta->vendedor->nombre ?? 'N/A' }}</td></tr>
                <tr><th>Fecha Levantamiento</th><td>{{ \Carbon\Carbon::parse($venta->fecha_hora_levantamiento)->format('d/m/Y H:i') }}</td></tr>
                <tr><th>Estatus</th><td><span class="badge bg-{{ $venta->estatus == 'cierre_venta' ? 'success' : ($venta->estatus == 'cotizacion' ? 'warning' : ($venta->estatus == 'levantamiento' ? 'info' : 'secondary')) }}">{{ ucfirst($venta->estatus ?? 'N/A') }}</span></td></tr>
                <tr><th>Venta Ganada</th><td>@if($venta->venta_ganada)<span class="badge bg-success">Sí</span>@else<span class="badge bg-danger">No</span>@endif</td></tr>
                <tr><th>Razón de Pérdida</th><td>{{ $venta->razon_perdida_venta ?? 'N/A' }}</td></tr>
            </table>
        </div></div>
    </div>
    <div class="col-md-4">
              <div class="card"><div class="card-header"><h6>Documentos</h6></div><div class="card-body">
    <div class="list-group">
        @if($venta->tieneDocumento('cotizacion'))
            <div class="list-group-item d-flex align-items-center gap-2">
                <i class="bi bi-file-pdf text-danger"></i>
                <span class="flex-grow-1">Cotización</span>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="verDocumento('{{ route('ventas.documento', [$venta, 'cotizacion']) }}', 'Cotización')">
                    <i class="bi bi-eye"></i> Ver
                </button>
                <a href="{{ route('ventas.documento', [$venta, 'cotizacion']) }}"
                   target="_blank" class="btn btn-sm btn-danger" title="Descargar">
                    <i class="bi bi-download"></i>
                </a>
            </div>
        @endif
        @if($venta->tieneDocumento('levantamiento'))
            <div class="list-group-item d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark text-primary"></i>
                <span class="flex-grow-1">Levantamiento</span>
                <button type="button" class="btn btn-sm btn-outline-primary"
                        onclick="verDocumento('{{ route('ventas.documento', [$venta, 'levantamiento']) }}', 'Levantamiento')">
                    <i class="bi bi-eye"></i> Ver
                </button>
                <a href="{{ route('ventas.documento', [$venta, 'levantamiento']) }}"
                   target="_blank" class="btn btn-sm btn-primary" title="Descargar">
                    <i class="bi bi-download"></i>
                </a>
            </div>
        @endif
        @if(!$venta->tieneDocumento('cotizacion') && !$venta->tieneDocumento('levantamiento'))
            <div class="text-center py-3 text-muted">
                <i class="bi bi-file-earmark" style="font-size:48px;"></i>
                <p>No hay documentos</p>
            </div>
        @endif
    </div>
</div></div>
</div>

{{-- Modal para previsualizar documentos --}}
<div class="modal fade" id="modalDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloDocumento">Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0" style="height: 75vh;">
                <iframe id="iframeDocumento" src="about:blank"
                        style="width:100%; height:100%; border:0;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listener único (no se re-registra en cada click)
    document.addEventListener('DOMContentLoaded', function () {
        const iframe  = document.getElementById('iframeDocumento');
        const modalEl = document.getElementById('modalDocumento');
        if (!iframe || !modalEl) return;

        modalEl.addEventListener('hidden.bs.modal', function () {
            iframe.src = 'about:blank';
            iframe.dataset.src = '';
        });
    });

    function verDocumento(url, titulo) {
        const tituloEl = document.getElementById('tituloDocumento');
        const iframe   = document.getElementById('iframeDocumento');
        const modalEl  = document.getElementById('modalDocumento');
        const modal    = bootstrap.Modal.getOrCreateInstance(modalEl);

        tituloEl.textContent = titulo;

        // Caso A: modal ya visible → cambiar src directo
        if (modalEl.classList.contains('show')) {
            iframe.src = url;
            return;
        }

        // Caso B: modal cerrado → guardar url y cargar en 'shown.bs.modal'
        iframe.dataset.src = url;
        modalEl.addEventListener('shown.bs.modal', function () {
            iframe.src = iframe.dataset.src || 'about:blank';
            iframe.dataset.src = '';
        }, { once: true });

        modal.show();
    }
</script>
@endpush

@endsection
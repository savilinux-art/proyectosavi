@extends('layouts.app')
@section('page-title', 'Recordatorios')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-alarm"></i> Recordatorios</h1>
    <a href="{{ route('recordatorios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Recordatorio
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card stat-card text-white bg-primary"><div class="card-body"><h6>Pendientes hoy</h6><h3>{{ $pendientesHoy }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card text-white bg-warning"><div class="card-body"><h6>Próximos 7 días</h6><h3>{{ $proximos7Dias }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card text-white bg-danger"><div class="card-body"><h6>Certs por vencer (30d)</h6><h3>{{ $certificadosPorVencer }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card text-white bg-secondary"><div class="card-body"><h6>Total</h6><h3>{{ $total }}</h3></div></div></div>
</div>

<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
    <label class="form-label small">Estatus</label>
    <select name="estatus" class="form-select form-select-sm">
        <option value="">Todos</option>
        @foreach(['pendiente' => 'Pendiente', 'enviado' => 'Enviado', 'cancelado' => 'Cancelado', 'completado' => 'Completado', 'error' => 'Error'] as $k => $v)
            <option value="{{ $k }}" @selected(request('estatus') === $k)>{{ $v }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2">
    <label class="form-label small">Tipo</label>
    <select name="tipo" class="form-select form-select-sm">
        <option value="">Todos</option>
        @foreach(['general' => 'General', 'certificado' => 'Certificado', 'sistema' => 'Sistema'] as $k => $v)
            <option value="{{ $k }}" @selected(request('tipo') === $k)>{{ $v }}</option>
        @endforeach
    </select>
</div>
        <div class="col-md-2">
            <label class="form-label small">Tipo</label>
            <select name="tipo" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach(['unico' => 'Único', 'recurrente' => 'Recurrente', 'certificado' => 'Certificado', 'sistema' => 'Sistema'] as $k => $v)
                    <option value="{{ $k }}" @selected(request('tipo') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        @if(session('user_rol') === 'Administrador')
        <div class="col-md-3">
            <label class="form-label small">Usuario</label>
            <select name="usuario_id" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuario }}" @selected(request('usuario_id') === $u->usuario)>{{ $u->nombre ?? $u->usuario }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-2">
            <label class="form-label small">Desde</label>
            <input type="date" name="desde" value="{{ request('desde') }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Hasta</label>
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-1">
            <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div></div>

<div class="card"><div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped" id="recordatoriosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Programado</th>
                    <th>Canal</th>
                    <th>Destinatario</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recordatorios as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>
                        {{ $r->titulo }}
                         @if($r->tipo === 'certificado' && $r->cert_fecha_vencimiento)
                         @php
                         $d = $r->diasRestantesCertificado();
                         $emoji = $d < 0 ? '🔴' : ($d <= 1 ? '🔴' : ($d <= 3 ? '🟠' : ($d <= 7 ? '🟡' : ($d <= 15 ? '🟢' : '⚪'))));
                         $color = $d < 0 ? 'danger' : ($d <= 3 ? 'danger' : ($d <= 7 ? 'warning' : 'muted'));
                         @endphp
                         <br><small class="text-{{ $color }}">
                        {{ $emoji }}
                        @if($d < 0) VENCIDO hace {{ abs($d) }} días
                        @elseif($d === 0) VENCE HOY
                        @else {{ $d }} días restantes
                        @endif
                        </small>
                         @endif
                    </td>
                    <td><span class="badge bg-info">{{ ucfirst($r->tipo) }}</span></td>
                    <td>{{ $r->fecha_hora_programada?->format('d/m/Y H:i') }}</td>
                    <td>
                        @foreach((array) $r->canal as $c)
                            <span class="badge bg-secondary">{{ $c }}</span>
                        @endforeach
                    </td>
                    <td>{{ $r->usuario->nombre ?? $r->usuario_id }}</td>
                    <td>
                        @php
                            $badge = match($r->estatus) {
                                'pendiente' => 'warning',
                                'enviado'   => 'success',
                                'cancelado' => 'secondary',
                                'error'     => 'danger',
                                default     => 'light',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($r->estatus) }}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            @if($r->tipo === 'certificado')
<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalRenovar{{ $r->id }}" title="Marcar como renovado">
    <i class="bi bi-arrow-repeat"></i>
</button>

{{-- Modal renovar --}}
<div class="modal fade" id="modalRenovar{{ $r->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('recordatorios.renovar', $r->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Renovar certificado: {{ $r->cert_nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Vencimiento anterior: <strong>{{ $r->cert_fecha_vencimiento?->format('d/m/Y') }}</strong></p>
                    <div class="mb-3">
                        <label class="form-label">Nueva fecha de vencimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_vencimiento_nueva" class="form-control" required
                               min="{{ now()->addDay()->toDateString() }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link de renovación</label>
                        <input type="url" name="cert_link_renovacion" class="form-control"
                               value="{{ $r->cert_link_renovacion }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea name="notas" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Marcar como renovado</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
                            <a href="{{ route('recordatorios.edit', $r->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            @if($r->estatus === 'pendiente')
                            <form action="{{ route('recordatorios.cancelar', $r->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Cancelar este recordatorio?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary" title="Cancelar"><i class="bi bi-x-circle"></i></button>
                            </form>
                            @endif
                            <form action="{{ route('recordatorios.destroy', $r->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este recordatorio?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#recordatoriosTable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
        order: [[0, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush

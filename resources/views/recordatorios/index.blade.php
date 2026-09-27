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
                @foreach(['pendientes' => 'Pendientes', 'enviados' => 'Enviados', 'cancelados' => 'Cancelados'] as $k => $v)
                    <option value="{{ $k }}" @selected(request('estatus') === $k)>{{ $v }}</option>
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
                            @php $d = $r->diasRestantesCertificado(); @endphp
                            <br><small class="text-{{ $d <= 3 ? 'danger' : ($d <= 7 ? 'warning' : 'muted') }}">
                                <i class="bi bi-patch-check"></i>
                                {{ $d }} días {{ $d < 0 ? '(VENCIDO)' : '' }}
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

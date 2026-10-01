@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-folder"></i> Detalle del Proyecto</h1>
    <div>
        <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Información del Proyecto</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID</th>
                        <td>{{ $proyecto->id }}</td>
                    </tr>
                    <tr>
                        <th>Nombre del Proyecto</th>
                        <td>{{ $proyecto->nombre_proyecto }}</td>
                    </tr>
                    <tr>
                        <th>Correo Electrónico</th>
                        <td>{{ $proyecto->correo_electronico }}</td>
                    </tr>
                    <tr>
                        <th>Ubicación</th>
                        <td>{{ $proyecto->ubicacion ?? 'No especificada' }}</td>
                    </tr>
                    <tr>
                        <th>Credenciales</th>
                        <td>{{ $proyecto->credenciales ?? 'No especificadas' }}</td>
                    </tr>
                    <tr>
                        <th>Modificado por</th>
                        <td>{{ $proyecto->modificadoPor->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Creación</th>
                        <td>{{ $proyecto->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Última Actualización</th>
                        <td>{{ $proyecto->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Documentos del Proyecto</h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                   @if($proyecto->propuesta_economica)
    <div class="list-group-item">
        <i class="bi bi-file-pdf text-danger"></i>
        Propuesta Económica
        <a href="{{ route('proyectos.propuesta-pdf', $proyecto->id) }}"
           target="_blank"
           class="btn btn-sm btn-danger float-end">
            <i class="bi bi-download"></i>
        </a>
    </div>
@endif

@if($proyecto->archivo_as_built)
    <div class="list-group-item">
        <i class="bi bi-file-earmark text-primary"></i>
        Archivo As-Built
        <a href="{{ route('proyectos.as-built', $proyecto->id) }}"
           target="_blank"
           class="btn btn-sm btn-primary float-end">
            <i class="bi bi-download"></i>
        </a>
    </div>
@endif
                    
                    @if($proyecto->salida_inventario)
    <div class="list-group-item">
        <i class="bi bi-file-pdf text-success"></i>
        Salida de Inventario
        <a href="{{ route('proyectos.salida-pdf', $proyecto->id) }}"
           target="_blank"
           class="btn btn-sm btn-success float-end">
            <i class="bi bi-download"></i>
        </a>
    </div>
@endif

@if($proyecto->devolucion_inventario)
    <div class="list-group-item">
        <i class="bi bi-file-pdf text-warning"></i>
        Devolución de Inventario
        <a href="{{ route('proyectos.devolucion-pdf', $proyecto->id) }}"
           target="_blank"
           class="btn btn-sm btn-warning float-end">
            <i class="bi bi-download"></i>
        </a>
    </div>
@endif
                    
                    @if(!$proyecto->propuesta_economica && !$proyecto->archivo_as_built && !$proyecto->salida_inventario && !$proyecto->devolucion_inventario)
                        <div class="list-group-item text-muted text-center">
                            <i class="bi bi-file-earmark"></i>
                            <p>No hay documentos disponibles</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ========== TRAZABILIDAD DE MATERIALES ========== --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-left-right"></i> Trazabilidad de Materiales</h5>
            </div>
            <div class="card-body">
                @if(empty($materiales))
                    <p class="text-muted mb-0">
                        No hay cotizaciones aprobadas ni salidas registradas para este proyecto.
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Modelo</th>
                                    <th>Marca</th>
                                    <th>Descripción</th>
                                    <th class="text-end">Vendido</th>
                                    <th class="text-end">Entregado</th>
                                    <th class="text-end">Devuelto</th>
                                    <th class="text-end">Neto</th>
                                    <th class="text-end">Faltante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materiales as $m)
                                    @php
                                        $faltante = $m['faltante'];
                                        $clase = $faltante > 0
                                            ? 'text-danger fw-bold'
                                            : ($faltante < 0 ? 'text-warning fw-bold' : 'text-success');
                                    @endphp
                                    <tr>
                                        <td>{{ $m['inventario']->modelo ?? '—' }}</td>
                                        <td>{{ $m['inventario']->marca ?? '—' }}</td>
                                        <td>{{ $m['inventario']->descripcion ?? '—' }}</td>
                                        <td class="text-end">{{ $m['vendido'] }}</td>
                                        <td class="text-end">{{ $m['entregado'] }}</td>
                                        <td class="text-end">{{ $m['devuelto'] }}</td>
                                        <td class="text-end">{{ $m['neto'] }}</td>
                                        <td class="text-end {{ $clase }}">{{ $faltante }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted">
                        <strong>Neto</strong> = Entregado − Devuelto.
                        <strong>Faltante</strong> = Vendido − Neto.
                        Rojo: falta entregar. Amarillo: se devolvió más de lo entregado.
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ========== HISTORIAL DE MOVIMIENTOS ========== --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Movimientos</h5>
            </div>
            <div class="card-body">
                @if($salidas->isEmpty() && $devoluciones->isEmpty())
                    <p class="text-muted mb-0">Sin movimientos registrados.</p>
                @else
                    @if($salidas->isNotEmpty())
                        <h6 class="mt-2">Salidas (entregas)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Entregado por</th>
                                        <th>Entregado a</th>
                                        <th>Materiales</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salidas as $s)
                                        <tr>
                                            <td>{{ $s->fecha_hora_salida->format('d/m/Y H:i') }}</td>
                                            <td>{{ $s->entregado_por }}</td>
                                            <td>{{ $s->entregado_a }}</td>
                                            <td>
                                                <ul class="mb-0 ps-3">
                                                    @foreach($s->detalles as $d)
                                                        <li>
                                                            {{ $d->cantidad }}×
                                                            {{ $d->inventario->descripcion ?? '—' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>{{ $s->observaciones ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($devoluciones->isNotEmpty())
                        <h6 class="mt-2">Devoluciones</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Devuelto por</th>
                                        <th>Recibido por</th>
                                        <th>Materiales</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devoluciones as $d)
                                        <tr>
                                            <td>{{ $d->fecha_hora_devolucion->format('d/m/Y H:i') }}</td>
                                            <td>{{ $d->devuelto_por }}</td>
                                            <td>{{ $d->recibido_por }}</td>
                                            <td>
                                                <ul class="mb-0 ps-3">
                                                    @foreach($d->detalles as $det)
                                                        <li>
                                                            {{ $det->cantidad }}×
                                                            {{ $det->inventario->descripcion ?? '—' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>{{ $d->observaciones ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>


@endsection
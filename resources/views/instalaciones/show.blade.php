@extends('layouts.app')
@section('page-title', 'Instalación: ' . ($instalacion->nombre_instalacion ?? 'Principal'))

@section('content')

@php
    $estatusColors = [
        'pendiente'    => 'secondary',
        'programacion' => 'info',
        'preparacion'  => 'warning',
        'asignada'     => 'primary',
        'en_proceso'   => 'primary',
        'pruebas'      => 'info',
        'entrega'      => 'success',
        'completada'   => 'success',
        'cancelada'    => 'danger',
    ];
    $colorActual = $estatusColors[$instalacion->estatus_instalacion] ?? 'secondary';
    $ubicacionInicio = $instalacion->ubicaciones->where('tipo', 'inicio')->first();
    $ubicacionFin    = $instalacion->ubicaciones->where('tipo', 'fin')->first();
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tools"></i> {{ $instalacion->nombre_instalacion ?? 'Instalación' }}</h1>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-arrow-repeat"></i> Cambiar estatus
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach(\App\Models\Estatus::where('tipo','instalacion')->orderBy('estatus')->get() as $e)
                    @if($e->estatus !== $instalacion->estatus_instalacion)
                        <li>
                            <form action="{{ route('instalaciones.cambiarEstatus', $instalacion->id) }}"
                                  method="POST" class="estatus-form">
                                @csrf
                                <input type="hidden" name="estatus_instalacion" value="{{ $e->estatus }}">
                                <button type="submit" class="dropdown-item">
                                    → {{ ucfirst(str_replace('_',' ',$e->estatus)) }}
                                </button>
                            </form>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <a href="{{ route('instalaciones.edit', $instalacion->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-info-circle"></i> Información General</h5></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th style="width:40%;">Proyecto:</th><td>{{ $instalacion->proyecto->nombre_proyecto ?? $instalacion->nombre_proyecto }}</td></tr>
                    <tr><th>Nombre:</th><td>{{ $instalacion->nombre_instalacion ?? 'Principal' }}</td></tr>
                    <tr>
                        <th>Estatus:</th>
                        <td><span class="badge bg-{{ $colorActual }}">{{ ucfirst(str_replace('_',' ',$instalacion->estatus_instalacion)) }}</span></td>
                    </tr>
                    <tr><th>Fecha Inicio:</th><td>{{ \Carbon\Carbon::parse($instalacion->fecha_hora_inicio)->format('d/m/Y H:i') }}</td></tr>
                    <tr><th>Fecha Fin:</th><td>{{ $instalacion->fecha_hora_fin ? \Carbon\Carbon::parse($instalacion->fecha_hora_fin)->format('d/m/Y H:i') : 'En proceso' }}</td></tr>
                    @if($instalacion->direccion)
                        <tr><th>Dirección:</th><td>{{ $instalacion->direccion }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-people"></i> Instaladores Asignados</h5></div>
            <div class="card-body">
                @if($instalacion->instaladores->isEmpty())
                    <p class="text-muted">No hay instaladores asignados.</p>
                @else
                    <ul class="list-group">
                        @foreach($instalacion->instaladores as $inst)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-person"></i> {{ $inst->nombre }}</span>
                                @if($inst->pivot->es_principal ?? false)
                                    <span class="badge bg-success">Principal</span>
                                @else
                                    <span class="badge bg-secondary">Apoyo</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Galería de fotos --}}
@if($instalacion->fotos->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-camera"></i>
            Evidencias ({{ $instalacion->fotos->count() }})
        </h5>
    </div>
    <div class="card-body">
        @php $porTipo = $instalacion->fotos->groupBy('tipo'); @endphp

        <ul class="nav nav-pills nav-sm mb-3" role="tablist">
            @foreach($porTipo as $tipo => $fotos)
                <li class="nav-item">
                    <button class="nav-link py-1 px-3 small {{ $loop->first ? 'active' : '' }}"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-{{ $tipo }}">
                        {{ ucfirst($tipo) }} ({{ $fotos->count() }})
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($porTipo as $tipo => $fotos)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                     id="tab-{{ $tipo }}">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($fotos as $foto)
                            @php
                                $rutaFisica = storage_path('app/public/' . $foto->ruta);
                                $existe = file_exists($rutaFisica);
                                $url = $existe ? asset('storage/' . $foto->ruta) : null;
                            @endphp

                            <div class="foto-item">
                                @if($url)
                                    <img src="{{ $url }}"
                                         class="foto-mini"
                                         onclick="abrirFoto('{{ $url }}', '{{ $foto->nombre_original }}')"
                                         title="{{ $foto->created_at->format('d/m/Y H:i') }}">
                                @else
                                    <div class="foto-mini foto-error d-flex align-items-center justify-content-center">
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Modal para ver la foto grande --}}
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoTitulo">Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFotoImg" src="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function abrirFoto(url, nombre) {
    document.getElementById('modalFotoImg').src = url;
    document.getElementById('modalFotoTitulo').textContent = nombre || 'Foto';
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}
</script>
@endpush

@push('styles')
<style>
    .foto-item { position: relative; display: inline-block; }
    .foto-mini {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: transform 0.15s, border-color 0.15s;
    }
    .foto-mini:hover {
        transform: scale(1.08);
        border-color: #3b82f6;
    }
    .foto-error {
        background: #f3f4f6;
        cursor: default;
    }
</style>
@endpush
@endif

{{-- Ubicaciones --}}
<div class="card mt-4">
    <div class="card-header"><h5><i class="bi bi-geo-alt"></i> Ubicaciones Registradas</h5></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-play-circle text-success"></i> Inicio de Jornada</h6>
                @if($ubicacionInicio)
                    <p>
                        <strong>Lat:</strong> {{ $ubicacionInicio->latitud }}<br>
                        <strong>Lng:</strong> {{ $ubicacionInicio->longitud }}<br>
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($ubicacionInicio->fecha_hora)->format('d/m/Y H:i') }}<br>
                        @if($ubicacionInicio->usuario)
                            <strong>Por:</strong> {{ $ubicacionInicio->usuario->nombre }}
                        @endif
                    </p>
                    <div id="mapaInicio" style="height: 250px; border-radius: 8px;"></div>
                @else
                    <p class="text-muted">Sin registrar.</p>
                @endif
            </div>

            <div class="col-md-6">
                <h6><i class="bi bi-stop-circle text-danger"></i> Fin de Jornada</h6>
                @if($ubicacionFin)
                    <p>
                        <strong>Lat:</strong> {{ $ubicacionFin->latitud }}<br>
                        <strong>Lng:</strong> {{ $ubicacionFin->longitud }}<br>
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($ubicacionFin->fecha_hora)->format('d/m/Y H:i') }}<br>
                        @if($ubicacionFin->usuario)
                            <strong>Por:</strong> {{ $ubicacionFin->usuario->nombre }}
                        @endif
                    </p>
                    <div id="mapaFin" style="height: 250px; border-radius: 8px;"></div>
                @else
                    <p class="text-muted">Sin registrar.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Historial --}}
@if($instalacion->ubicaciones->count() > 0)
<div class="card mt-4">
    <div class="card-header"><h5><i class="bi bi-list-ul"></i> Historial</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Tipo</th><th>Lat</th><th>Lng</th><th>Fecha</th><th>Usuario</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instalacion->ubicaciones as $ubi)
                        <tr>
                            <td><span class="badge bg-{{ $ubi->tipo == 'inicio' ? 'success' : 'danger' }}">{{ ucfirst($ubi->tipo) }}</span></td>
                            <td>{{ $ubi->latitud }}</td>
                            <td>{{ $ubi->longitud }}</td>
                            <td>{{ \Carbon\Carbon::parse($ubi->fecha_hora)->format('d/m/Y H:i') }}</td>
                            <td>{{ $ubi->usuario->nombre ?? 'N/A' }}</td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $ubi->latitud }},{{ $ubi->longitud }}"
                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-geo-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    function crearMapa(id, lat, lng, popup) {
        const el = document.getElementById(id);
        if (!el) return;
        const map = L.map(id).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map).bindPopup(popup).openPopup();
    }

    @if($ubicacionInicio)
        crearMapa('mapaInicio', {{ $ubicacionInicio->latitud }}, {{ $ubicacionInicio->longitud }}, 'Inicio de jornada');
    @endif

    @if($ubicacionFin)
        crearMapa('mapaFin', {{ $ubicacionFin->latitud }}, {{ $ubicacionFin->longitud }}, 'Fin de jornada');
    @endif

    // Confirmación al cambiar estatus
    document.querySelectorAll('.estatus-form').forEach(f => {
        f.addEventListener('submit', e => {
            if (!confirm('¿Cambiar el estatus de la instalación?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
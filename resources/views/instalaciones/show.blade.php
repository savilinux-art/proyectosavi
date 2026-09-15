@extends('layouts.app')

@section('page-title', 'Instalación: ' . ($instalacion->nombre_instalacion ?? 'Principal'))

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tools"></i> {{ $instalacion->nombre_instalacion ?? 'Instalación' }}</h1>
    <div>
        <a href="{{ route('instalaciones.edit', $instalacion->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Información General -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-info-circle"></i> Información General</h5></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width:40%;">Proyecto:</th>
                        <td>{{ $instalacion->proyecto->nombre_proyecto ?? $instalacion->nombre_proyecto }}</td>
                    </tr>
                    <tr>
                        <th>Nombre Instalación:</th>
                        <td>{{ $instalacion->nombre_instalacion ?? 'Principal' }}</td>
                    </tr>
                    <tr>
                        <th>Estatus:</th>
                        <td>
                            @php
                                $estatusColors = [
                                    'preparacion' => 'secondary',
                                    'en_proceso' => 'primary',
                                    'programacion' => 'info',
                                    'pruebas' => 'warning',
                                    'entrega' => 'success'
                                ];
                            @endphp
                            <span class="badge bg-{{ $estatusColors[$instalacion->estatus_instalacion] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $instalacion->estatus_instalacion ?? 'N/A')) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Fecha Inicio:</th>
                        <td>{{ \Carbon\Carbon::parse($instalacion->fecha_hora_inicio)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Fecha Fin:</th>
                        <td>{{ $instalacion->fecha_hora_fin ? \Carbon\Carbon::parse($instalacion->fecha_hora_fin)->format('d/m/Y H:i') : 'En proceso' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Instaladores Asignados -->
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

<!-- Ubicaciones Registradas -->
<div class="card mt-4">
    <div class="card-header"><h5><i class="bi bi-geo-alt"></i> Ubicaciones Registradas</h5></div>
    <div class="card-body">
        <div class="row">
            <!-- Inicio -->
            <div class="col-md-6">
                <h6><i class="bi bi-play-circle text-success"></i> Inicio de Jornada</h6>
                @php
                    $ubicacionInicio = $instalacion->ubicaciones->where('tipo', 'inicio')->first();
                @endphp
                @if($ubicacionInicio)
                    <p>
                        <strong>Latitud:</strong> {{ $ubicacionInicio->latitud }}<br>
                        <strong>Longitud:</strong> {{ $ubicacionInicio->longitud }}<br>
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($ubicacionInicio->fecha_hora)->format('d/m/Y H:i') }}<br>
                        @if($ubicacionInicio->usuario)
                            <strong>Registrado por:</strong> {{ $ubicacionInicio->usuario->nombre ?? $ubicacionInicio->usuario_id }}<br>
                        @endif
                        <a href="https://www.google.com/maps?q={{ $ubicacionInicio->latitud }},{{ $ubicacionInicio->longitud }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-map"></i> Abrir en Google Maps
                        </a>
                    </p>
                    <div id="mapaInicio" style="height: 250px; border-radius: 8px;" class="mt-2"></div>
                @else
                    <p class="text-muted">Sin registrar.</p>
                @endif
            </div>

            <!-- Fin -->
            <div class="col-md-6">
                <h6><i class="bi bi-stop-circle text-danger"></i> Fin de Jornada</h6>
                @php
                    $ubicacionFin = $instalacion->ubicaciones->where('tipo', 'fin')->first();
                @endphp
                @if($ubicacionFin)
                    <p>
                        <strong>Latitud:</strong> {{ $ubicacionFin->latitud }}<br>
                        <strong>Longitud:</strong> {{ $ubicacionFin->longitud }}<br>
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($ubicacionFin->fecha_hora)->format('d/m/Y H:i') }}<br>
                        @if($ubicacionFin->usuario)
                            <strong>Registrado por:</strong> {{ $ubicacionFin->usuario->nombre ?? $ubicacionFin->usuario_id }}<br>
                        @endif
                        <a href="https://www.google.com/maps?q={{ $ubicacionFin->latitud }},{{ $ubicacionFin->longitud }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-map"></i> Abrir en Google Maps
                        </a>
                    </p>
                    <div id="mapaFin" style="height: 250px; border-radius: 8px;" class="mt-2"></div>
                @else
                    <p class="text-muted">Sin registrar.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Historial de Ubicaciones -->
@if($instalacion->ubicaciones->count() > 0)
<div class="card mt-4">
    <div class="card-header"><h5><i class="bi bi-list-ul"></i> Historial de Ubicaciones</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>Fecha/Hora</th>
                        <th>Usuario</th>
                        <th>Ver en mapa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instalacion->ubicaciones as $ubi)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $ubi->tipo == 'inicio' ? 'success' : 'danger' }}">
                                    {{ ucfirst($ubi->tipo) }}
                                </span>
                            </td>
                            <td>{{ $ubi->latitud }}</td>
                            <td>{{ $ubi->longitud }}</td>
                            <td>{{ \Carbon\Carbon::parse($ubi->fecha_hora)->format('d/m/Y H:i') }}</td>
                            <td>{{ $ubi->usuario->nombre ?? 'N/A' }}</td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $ubi->latitud }},{{ $ubi->longitud }}" target="_blank" class="btn btn-sm btn-outline-primary">
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

@push('scripts')
<script>
    @if($ubicacionInicio ?? false)
        var mapaInicio = L.map('mapaInicio').setView([{{ $ubicacionInicio->latitud }}, {{ $ubicacionInicio->longitud }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(mapaInicio);
        L.marker([{{ $ubicacionInicio->latitud }}, {{ $ubicacionInicio->longitud }}]).addTo(mapaInicio)
            .bindPopup('Inicio de instalación').openPopup();
    @endif

    @if($ubicacionFin ?? false)
        var mapaFin = L.map('mapaFin').setView([{{ $ubicacionFin->latitud }}, {{ $ubicacionFin->longitud }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(mapaFin);
        L.marker([{{ $ubicacionFin->latitud }}, {{ $ubicacionFin->longitud }}]).addTo(mapaFin)
            .bindPopup('Fin de instalación').openPopup();
    @endif
</script>
@endpush
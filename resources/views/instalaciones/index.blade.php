@extends('layouts.app')
@section('page-title', 'Instalaciones')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tools"></i> Instalaciones</h1>
    <a href="{{ route('instalaciones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Instalación
    </a>
</div>

{{-- Filtros --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('instalaciones.index') }}" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="buscar" class="form-control"
                       placeholder="Buscar proyecto o instalación..."
                       value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="estatus" class="form-select">
                    <option value="">Todos los estatus</option>
                    @foreach($estatus as $e)
                        <option value="{{ $e->estatus }}" {{ request('estatus') == $e->estatus ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ',$e->estatus)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proyecto</th>
                        <th>Instalación</th>
                        <th>Instaladores</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estatus</th>
                        <th class="text-center">📷</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instalaciones as $instalacion)
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
                            $color = $estatusColors[$instalacion->estatus_instalacion] ?? 'secondary';
                        @endphp
                        <tr>
                            <td>#{{ $instalacion->id }}</td>
                            <td>{{ $instalacion->proyecto->nombre_proyecto ?? $instalacion->nombre_proyecto }}</td>
                            <td>{{ $instalacion->nombre_instalacion ?? 'Principal' }}</td>
                            <td>
                                @forelse($instalacion->instaladores as $inst)
                                    <span class="badge bg-primary">{{ $inst->nombre }}</span>
                                @empty
                                    <span class="text-muted small">Sin asignar</span>
                                @endforelse
                            </td>
                            <td>{{ \Carbon\Carbon::parse($instalacion->fecha_hora_inicio)->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ $instalacion->fecha_hora_fin
                                    ? \Carbon\Carbon::parse($instalacion->fecha_hora_fin)->format('d/m/Y H:i')
                                    : '—' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst(str_replace('_',' ',$instalacion->estatus_instalacion)) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark">{{ $instalacion->fotos_count ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('instalaciones.show', $instalacion->id) }}"
                                       class="btn btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('instalaciones.edit', $instalacion->id) }}"
                                       class="btn btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('instalaciones.destroy', $instalacion->id) }}"
                                          method="POST" style="display:inline;"
                                          onsubmit="return confirm('¿Eliminar esta instalación?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No hay instalaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Mostrando {{ $instalaciones->firstItem() ?? 0 }}–{{ $instalaciones->lastItem() ?? 0 }}
                de {{ $instalaciones->total() }}
            </div>
            <div>{{ $instalaciones->links() }}</div>
        </div>
    </div>
</div>

{{-- Mapa --}}
<div class="card mt-4">
    <div class="card-header"><strong><i class="bi bi-map"></i> Mapa de instalaciones e instaladores</strong></div>
    <div class="card-body p-0">
        <div id="mapa" style="height: 520px;"></div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    .instalador-marker { font-size: 22px; filter: drop-shadow(0 0 3px #000); }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('mapa').setView([20.6534, -105.2253], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const capaInstalaciones = L.layerGroup().addTo(map);
    const capaInstaladores  = L.layerGroup().addTo(map);

    const colorEstatus = {
        programacion: '#3b82f6', preparacion: '#f59e0b',
        en_proceso:   '#8b5cf6', pruebas:     '#06b6d4',
        entrega:      '#10b981', asignada:    '#eab308',
        pendiente:    '#6b7280',
    };

    async function cargarDatos() {
        try {
            const res  = await fetch('{{ route("instalaciones.mapaData") }}');
            const data = await res.json();

            capaInstalaciones.clearLayers();
            capaInstaladores.clearLayers();

            (data.instalaciones || []).forEach(i => {
                const color = colorEstatus[i.estatus_instalacion] || '#6b7280';
                L.circleMarker([i.latitud, i.longitud], {
                    radius: 9, color, fillColor: color, fillOpacity: 0.7, weight: 2,
                }).addTo(capaInstalaciones)
                  .bindPopup(`<b>${i.nombre_instalacion}</b><br>
                              ${i.nombre_proyecto}<br>
                              Estatus: <code>${i.estatus_instalacion}</code><br>
                              <a href="/instalaciones/${i.id}">Ver detalle</a>`);
            });

            (data.instaladores || []).forEach(u => {
                L.marker([u.latitud, u.longitud], {
                    icon: L.divIcon({
                        html: '👷', className: 'instalador-marker', iconSize: [24, 24],
                    })
                }).addTo(capaInstaladores)
                  .bindPopup(`<b>${u.nombre}</b><br>
                              Tipo: ${u.tipo}<br>
                              ${new Date(u.fecha_hora).toLocaleString()}`);
            });

        } catch (err) {
            console.error('Error cargando mapa:', err);
        }
    }

    cargarDatos();
    setInterval(cargarDatos, 15000);
});
</script>
@endpush
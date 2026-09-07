@extends('layouts.app')

@section('page-title', 'Detalle de Geocerca')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pin-map"></i> {{ $geocerca->nombre }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $geocerca->id }}</p>
                <p><strong>Nombre:</strong> {{ $geocerca->nombre }}</p>
                <p><strong>Latitud:</strong> {{ $geocerca->latitud }}</p>
                <p><strong>Longitud:</strong> {{ $geocerca->longitud }}</p>
                <p><strong>Radio:</strong> {{ $geocerca->radio }} metros</p>
                <p><strong>Color:</strong> <span style="display:inline-block; width:20px; height:20px; background-color:{{ $geocerca->color }}; border-radius:50%;"></span></p>
                <p><strong>Proyecto:</strong> {{ $geocerca->proyecto->nombre_proyecto ?? 'N/A' }}</p>
                <p><strong>Instalación:</strong> {{ $geocerca->instalacion->nombre_instalacion ?? 'N/A' }}</p>
                <p><strong>Estado:</strong> {{ $geocerca->activa ? 'Activa' : 'Inactiva' }}</p>
            </div>
            <div class="col-md-6">
                <div id="map" style="height: 300px;"></div>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('geocercas.edit', $geocerca->id) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('geocercas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([{{ $geocerca->latitud }}, {{ $geocerca->longitud }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.circle([{{ $geocerca->latitud }}, {{ $geocerca->longitud }}], {
        color: '{{ $geocerca->color }}',
        fillColor: '{{ $geocerca->color }}',
        fillOpacity: 0.2,
        radius: {{ $geocerca->radio }}
    }).addTo(map);
    L.marker([{{ $geocerca->latitud }}, {{ $geocerca->longitud }}]).addTo(map);
</script>
@endpush
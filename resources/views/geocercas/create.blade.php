@extends('layouts.app')

@section('page-title', 'Nueva Geocerca')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pin-map"></i> Nueva Geocerca</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('geocercas.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="color" class="form-control form-control-color" id="color" name="color" value="{{ old('color', '#FF0000') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="latitud" class="form-label">Latitud *</label>
                    <input type="text" class="form-control @error('latitud') is-invalid @enderror" id="latitud" name="latitud" value="{{ old('latitud', '20.6597') }}" required>
                    @error('latitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="longitud" class="form-label">Longitud *</label>
                    <input type="text" class="form-control @error('longitud') is-invalid @enderror" id="longitud" name="longitud" value="{{ old('longitud', '-105.2252') }}" required>
                    @error('longitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="radio" class="form-label">Radio (metros) *</label>
                    <input type="number" class="form-control @error('radio') is-invalid @enderror" id="radio" name="radio" value="{{ old('radio', 100) }}" required min="10">
                    @error('radio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="proyecto_id" class="form-label">Proyecto asociado</label>
                    <select class="form-select" id="proyecto_id" name="proyecto_id">
                        <option value="">Ninguno</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->id }}" {{ old('proyecto_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre_proyecto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="instalacion_id" class="form-label">Instalación asociada</label>
                    <select class="form-select" id="instalacion_id" name="instalacion_id">
                        <option value="">Ninguna</option>
                        @foreach($instalaciones as $i)
                            <option value="{{ $i->id }}" {{ old('instalacion_id') == $i->id ? 'selected' : '' }}>
                                {{ $i->nombre_instalacion }} ({{ $i->nombre_proyecto }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="activa" name="activa" value="1" {{ old('activa', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activa">Activa</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('geocercas.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Geocerca</button>
            </div>
        </form>
    </div>
</div>

<!-- Mapa para seleccionar ubicación -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Mapa interactivo</h5>
    </div>
    <div class="card-body">
        <div id="map" style="height: 400px;"></div>
        <p class="text-muted mt-2">Haz clic en el mapa para obtener coordenadas.</p>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar mapa
        var map = L.map('map').setView([20.6597, -105.2252], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker = null;

        // Cargar valores iniciales
        var latInput = document.getElementById('latitud');
        var lngInput = document.getElementById('longitud');
        var latInicial = parseFloat(latInput.value) || 20.6597;
        var lngInicial = parseFloat(lngInput.value) || -105.2252;

        // Si ya hay coordenadas, colocar marcador
        if (latInput.value && lngInput.value) {
            marker = L.marker([latInicial, lngInicial]).addTo(map);
            map.setView([latInicial, lngInicial], 15);
        }

        // Evento clic en el mapa
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            console.log('📍 Clic en el mapa:', lat, lng);

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Actualizar campos del formulario (con 7 decimales)
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
        });

        // =====================================================
        // AUTOCOMPLETAR NOMBRE A PARTIR DEL PROYECTO
        // =====================================================
        var proyectoSelect = document.getElementById('proyecto_id');
        var nombreInput = document.getElementById('nombre');
        var nombreAnterior = nombreInput.value; // Guardar nombre inicial

        proyectoSelect.addEventListener('change', function() {
            var proyectoId = this.value;
            if (proyectoId) {
                var proyectoNombre = this.options[this.selectedIndex].text;
                // Si el campo nombre está vacío o es igual al anterior autocompletado
                if (nombreInput.value === '' || nombreInput.value === nombreAnterior) {
                    nombreInput.value = proyectoNombre;
                    nombreAnterior = proyectoNombre;
                }
            } else {
                // Si se selecciona "Ninguno", no cambiar el nombre
                nombreAnterior = nombreInput.value;
            }
        });
    });
</script>
@endpush
@extends('layouts.app')

@section('page-title', 'Editar Geocerca: ' . $geocerca->nombre)

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Geocerca: {{ $geocerca->nombre }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('geocercas.update', $geocerca) }}" method="POST">
            @csrf @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $geocerca->nombre) }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="radio" class="form-label">Radio (metros) *</label>
                    <input type="number" class="form-control @error('radio') is-invalid @enderror" id="radio" name="radio" value="{{ old('radio', $geocerca->radio) }}" min="10" required>
                    @error('radio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="latitud" class="form-label">Latitud *</label>
                    <input type="text" class="form-control @error('latitud') is-invalid @enderror" id="latitud" name="latitud" value="{{ old('latitud', $geocerca->latitud) }}" required>
                    @error('latitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="longitud" class="form-label">Longitud *</label>
                    <input type="text" class="form-control @error('longitud') is-invalid @enderror" id="longitud" name="longitud" value="{{ old('longitud', $geocerca->longitud) }}" required>
                    @error('longitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="color" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $geocerca->color) }}" style="height: 50px;">
                    @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="proyecto_id" class="form-label">Proyecto asociado</label>
                    <select class="form-select @error('proyecto_id') is-invalid @enderror" id="proyecto_id" name="proyecto_id">
                        <option value="">Ninguno</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->id }}" {{ old('proyecto_id', $geocerca->proyecto_id) == $p->id ? 'selected' : '' }}>{{ $p->nombre_proyecto }}</option>
                        @endforeach
                    </select>
                    @error('proyecto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="instalacion_id" class="form-label">Instalación asociada</label>
                    <select class="form-select @error('instalacion_id') is-invalid @enderror" id="instalacion_id" name="instalacion_id">
                        <option value="">Ninguna</option>
                        @foreach($instalaciones as $i)
                            <option value="{{ $i->id }}" {{ old('instalacion_id', $geocerca->instalacion_id) == $i->id ? 'selected' : '' }}>{{ $i->nombre_instalacion }} ({{ $i->nombre_proyecto }})</option>
                        @endforeach
                    </select>
                    @error('instalacion_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="activa" name="activa" value="1" {{ old('activa', $geocerca->activa) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activa">Activa</label>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Actualizar</button>
                <a href="{{ route('geocercas.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>

<!-- Mapa -->
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="bi bi-map"></i> Selecciona la ubicación en el mapa</h5>
    </div>
    <div class="card-body">
        <div id="map" style="height: 400px; width: 100%;"></div>
        <p class="text-muted mt-2">Haz clic en el mapa para actualizar la ubicación de la geocerca.</p>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var lat = parseFloat($('#latitud').val()) || 20.6597;
    var lng = parseFloat($('#longitud').val()) || -105.2252;
    var map = L.map('map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([lat, lng]).addTo(map);

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        marker.setLatLng(e.latlng);
        $('#latitud').val(lat.toFixed(7));
        $('#longitud').val(lng.toFixed(7));
    });

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- si no está ya --}}
    <script>
        $(document).ready(function() {
            $('#proyecto_id').on('change', function() {
                var proyectoId = $(this).val();
                if (proyectoId) {
                    var nombreActual = $('#nombre').val();
                    var proyectoNombre = $('#proyecto_id option:selected').text();
                    // Si el campo nombre está vacío o es el mismo que el proyecto anterior, rellenar
                    if (nombreActual === '' || nombreActual === $('#proyecto_id').data('nombre-anterior')) {
                        $('#nombre').val(proyectoNombre);
                        $('#proyecto_id').data('nombre-anterior', proyectoNombre);
                    }
                } else {
                    $('#proyecto_id').data('nombre-anterior', '');
                }
            });
            // Guardar el nombre anterior al cargar
            $('#proyecto_id').data('nombre-anterior', $('#nombre').val());
        });
    </script>
@endpush
</script>
@endpush
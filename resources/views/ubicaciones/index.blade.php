@extends('layouts.app')

@section('page-title', 'Ubicaciones en Tiempo Real')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-geo-alt"></i> Ubicaciones de Instaladores</h1>
    <div>
        <button id="refreshBtn" class="btn btn-primary">
            <i class="bi bi-arrow-clockwise"></i> Actualizar
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="map" style="height: 600px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de instaladores -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-people"></i> Instaladores Activos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="instaladoresTable">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Dispositivo</th>
                                <th>Última ubicación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="instaladoresBody">
                            <tr>
                                <td colspan="5" class="text-center">Cargando instaladores...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet.markercluster para agrupar marcadores -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // Inicializar el mapa
    var map = L.map('map').setView([20.6597, -105.2252], 13); // Centro en Puerto Vallarta (ajusta a tu zona)

    // Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Cluster de marcadores
    var markers = L.markerClusterGroup();

    // Función para obtener y actualizar ubicaciones
    function actualizarUbicaciones() {
        $.ajax({
            url: "{{ route('ubicaciones.data') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Limpiar marcadores anteriores
                markers.clearLayers();
                
                // Actualizar tabla
                var tbody = $('#instaladoresBody');
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">No hay instaladores con ubicación disponible</td></tr>');
                }

                data.forEach(function(item) {
                    // Agregar marcador al mapa
                    var marker = L.marker([item.lat, item.lng])
                        .bindPopup(`
                            <strong>${item.nombre}</strong><br>
                            Usuario: ${item.usuario}<br>
                            Velocidad: ${item.velocidad || 0} km/h<br>
                            Última actualización: ${new Date(item.fecha).toLocaleString()}
                        `);
                    markers.addLayer(marker);

                    // Agregar fila a la tabla
                    var estado = item.velocidad > 0 ? 
                        '<span class="badge bg-success">En movimiento</span>' : 
                        '<span class="badge bg-info">Detenido</span>';
                    
                    tbody.append(`
                        <tr>
                            <td><strong>${item.nombre}</strong></td>
                            <td>${item.usuario}</td>
                            <td><code>${item.device_id || 'N/A'}</code></td>
                            <td>${item.lat.toFixed(6)}, ${item.lng.toFixed(6)}</td>
                            <td>${estado}</td>
                        </tr>
                    `);
                });

                // Agregar los marcadores al mapa
                map.addLayer(markers);

                // Ajustar el mapa para mostrar todos los marcadores
                if (data.length > 0) {
                    map.fitBounds(markers.getBounds());
                }
            },
            error: function(xhr) {
                console.error('Error al obtener ubicaciones:', xhr);
                $('#instaladoresBody').html('<tr><td colspan="5" class="text-center text-danger">Error al cargar ubicaciones</td></tr>');
            }
        });
    }

    // Cargar ubicaciones al inicio
    actualizarUbicaciones();

    // Actualizar cada 15 segundos
    setInterval(actualizarUbicaciones, 15000);

    // Botón de actualización manual
    $('#refreshBtn').on('click', function() {
        $(this).html('<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Actualizando...');
        actualizarUbicaciones();
        setTimeout(() => {
            $(this).html('<i class="bi bi-arrow-clockwise"></i> Actualizar');
        }, 1000);
    });
</script>
@endpush
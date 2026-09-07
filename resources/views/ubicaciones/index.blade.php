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
<!-- Leaflet.markercluster -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // Variables globales para el mapa
    var map = L.map('map').setView([20.6597, -105.2252], 13);
    var markers = L.markerClusterGroup();
    var markersMap = new Map(); // Para almacenar marcadores por usuario_id

    // Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Función para cargar ubicaciones iniciales (AJAX)
    function cargarUbicacionesIniciales() {
        $.ajax({
            url: "{{ route('ubicaciones.data') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Limpiar marcadores anteriores
                markers.clearLayers();
                markersMap.clear();
                
                // Actualizar tabla
                var tbody = $('#instaladoresBody');
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">No hay instaladores con ubicación disponible</td></tr>');
                    return;
                }

                data.forEach(function(item) {
                    // Crear marcador
                    var marker = L.marker([item.lat, item.lng])
                        .bindPopup(`
                            <strong>${item.nombre}</strong><br>
                            Usuario: ${item.usuario}<br>
                            Velocidad: ${item.velocidad || 0} km/h<br>
                            Última actualización: ${new Date(item.fecha).toLocaleString()}
                        `);
                    markers.addLayer(marker);
                    markersMap.set(item.id, marker);

                    // Agregar fila a la tabla
                    var estado = item.velocidad > 0 ? 
                        '<span class="badge bg-success">En movimiento</span>' : 
                        '<span class="badge bg-info">Detenido</span>';
                    
                    tbody.append(`
                        <tr id="fila-usuario-${item.id}">
                            <td><strong>${item.nombre}</strong></td>
                            <td>${item.usuario}</td>
                            <td><code>${item.device_id || 'N/A'}</code></td>
                            <td>${item.lat.toFixed(6)}, ${item.lng.toFixed(6)}</td>
                            <td>${estado}</td>
                        </tr>
                    `);
                });

                map.addLayer(markers);
                if (data.length > 0) {
                    map.fitBounds(markers.getBounds());
                }
            },
            error: function(xhr) {
                console.error('Error al cargar ubicaciones:', xhr);
                $('#instaladoresBody').html('<tr><td colspan="5" class="text-center text-danger">Error al cargar ubicaciones</td></tr>');
            }
        });
    }

    // Función para actualizar un marcador individual (desde WebSocket)
    function actualizarMarcador(ubicacion) {
        // Buscar si ya existe un marcador para este usuario
        var marker = markersMap.get(ubicacion.id);
        var lat = ubicacion.lat;
        var lng = ubicacion.lng;
        var nombre = ubicacion.nombre;
        var usuario = ubicacion.usuario;
        var velocidad = ubicacion.velocidad || 0;
        var fecha = new Date(ubicacion.fecha).toLocaleString();

        if (marker) {
            // Actualizar posición y popup
            marker.setLatLng([lat, lng]);
            marker.getPopup().setContent(`
                <strong>${nombre}</strong><br>
                Usuario: ${usuario}<br>
                Velocidad: ${velocidad} km/h<br>
                Última actualización: ${fecha}
            `);
        } else {
            // Crear nuevo marcador
            var nuevoMarker = L.marker([lat, lng])
                .bindPopup(`
                    <strong>${nombre}</strong><br>
                    Usuario: ${usuario}<br>
                    Velocidad: ${velocidad} km/h<br>
                    Última actualización: ${fecha}
                `);
            markers.addLayer(nuevoMarker);
            markersMap.set(ubicacion.id, nuevoMarker);
        }

        // Actualizar fila en la tabla
        var estado = velocidad > 0 ? 
            '<span class="badge bg-success">En movimiento</span>' : 
            '<span class="badge bg-info">Detenido</span>';
        
        var fila = $(`#fila-usuario-${ubicacion.id}`);
        if (fila.length) {
            fila.find('td:eq(3)').text(lat.toFixed(6) + ', ' + lng.toFixed(6));
            fila.find('td:eq(4)').html(estado);
        } else {
            // Si no existe, agregar nueva fila
            $('#instaladoresBody').append(`
                <tr id="fila-usuario-${ubicacion.id}">
                    <td><strong>${nombre}</strong></td>
                    <td>${usuario}</td>
                    <td><code>${ubicacion.device_id || 'N/A'}</code></td>
                    <td>${lat.toFixed(6)}, ${lng.toFixed(6)}</td>
                    <td>${estado}</td>
                </tr>
            `);
        }

        // Ajustar mapa si es necesario (opcional)
        // map.fitBounds(markers.getBounds());
    }

    // Cargar ubicaciones iniciales
    cargarUbicacionesIniciales();

    // ======================== WEBSOCKETS ========================
    // Escuchar eventos WebSocket (si está disponible)
    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('ubicaciones')
            .listen('ubicacion.actualizada', (e) => {
                console.log('Nueva ubicación recibida (WebSocket):', e.ubicacion);
                // Convertir el objeto a formato esperado
                var ubicacion = e.ubicacion;
                actualizarMarcador(ubicacion);
            });
    } else {
        console.warn('Laravel Echo no está disponible. Las actualizaciones en tiempo real no funcionarán.');
    }

    // ======================== BOTÓN DE ACTUALIZACIÓN MANUAL ========================
    $('#refreshBtn').on('click', function() {
        $(this).html('<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Actualizando...');
        cargarUbicacionesIniciales();
        setTimeout(() => {
            $(this).html('<i class="bi bi-arrow-clockwise"></i> Actualizar');
        }, 1000);
    });

    // =====================================================
    // 🚀 WEBSOCKET PARA GEOCERCAS
    // =====================================================
    function iniciarWebSocketGeocercas() {
        if (typeof window.Echo === 'undefined') {
            console.warn('⚠️ Laravel Echo no está disponible. Las alertas de geocercas no funcionarán.');
            return;
        }

        window.Echo.channel('geocercas')
            .listen('geocerca.alerta', (e) => {
                console.log('🔔 Alerta de geocerca:', e.alerta);
                mostrarNotificacion(e.alerta);
            });
    }

    // Función para mostrar notificaciones en la interfaz
    function mostrarNotificacion(alerta) {
        const tipo = alerta.tipo === 'entrada' ? '🟢 ENTRÓ' : '🔴 Salió de';
        const nombre = alerta.geocerca?.nombre || 'zona';
        const usuario = alerta.usuario?.nombre || 'Instalador';

        // Mostrar en consola
        console.log(`📍 ${tipo} la zona: ${nombre} - ${usuario}`);

        // Crear una notificación visual (Bootstrap toast o alerta)
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${alerta.tipo === 'entrada' ? 'success' : 'danger'} border-0 show" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${tipo}</strong> la zona <strong>${nombre}</strong><br>
                        👤 ${usuario}<br>
                        📅 ${new Date(alerta.fecha_hora).toLocaleString()}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        // Agregar la notificación al DOM
        $('#notificaciones-container').append(toastHtml);

        // Auto-eliminar después de 10 segundos
        setTimeout(() => {
            $('.toast').last().remove();
        }, 10000);
    }

    // Iniciar WebSocket de geocercas al cargar la página
    $(document).ready(function() {
        iniciarWebSocketGeocercas();
    });
</script>
@endpush
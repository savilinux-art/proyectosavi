{{-- 
    ============================================================
    VISTA: Ubicaciones en Tiempo Real
    ============================================================
    Esta vista muestra un mapa con las ubicaciones de los instaladores
    y una tabla con sus datos. Se actualiza automáticamente mediante:
      1. AJAX (cada 15 segundos, como respaldo)
      2. WebSocket (actualizaciones instantáneas cuando llega una nueva ubicación)
      3. WebSocket para geocercas (alertas de entrada/salida)
--}}

@extends('layouts.app')

@section('page-title', 'Ubicaciones en Tiempo Real')

@section('content')

{{-- Contenedor para notificaciones de geocercas (flotante) --}}
<div id="notificaciones-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>

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

{{-- ============================================================
     SCRIPTS (se cargan al final con @push)
     ============================================================ --}}
@push('scripts')

{{-- Bibliotecas de Leaflet y MarkerCluster --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // =============================================================
    // 1. CONFIGURACIÓN INICIAL DEL MAPA
    // =============================================================
    // Se crea el mapa con centro en Puerto Vallarta (coordenadas de prueba)
    var map = L.map('map').setView([20.6597, -105.2252], 13);

    // Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Agrupador de marcadores (para mejorar rendimiento cuando hay muchos)
    var markers = L.markerClusterGroup();

    // Mapa para guardar referencias de marcadores por ID de usuario (para actualizarlos rápidamente)
    var markersMap = new Map();

    // =============================================================
    // 2. FUNCIÓN: Cargar ubicaciones iniciales (vía AJAX)
    // =============================================================
    function cargarUbicacionesIniciales() {
        $.ajax({
            url: "{{ route('ubicaciones.data') }}", // Ruta que devuelve JSON con ubicaciones
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Limpiar marcadores anteriores
                markers.clearLayers();
                markersMap.clear();

                // Limpiar tabla
                var tbody = $('#instaladoresBody');
                tbody.empty();

                // Si no hay datos, mostrar mensaje
                if (data.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">No hay instaladores con ubicación disponible</td></tr>');
                    return;
                }

                // Recorrer cada ubicación
                data.forEach(function(item) {
                    // Crear marcador en el mapa
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

                // Agregar los marcadores al mapa
                map.addLayer(markers);

                // Ajustar el mapa para mostrar todos los marcadores
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

    // =============================================================
    // 3. FUNCIÓN: Actualizar un marcador individual (desde WebSocket)
    // =============================================================
    function actualizarMarcador(ubicacion) {
        // Extraer datos de la ubicación
        var id = ubicacion.id;
        var lat = ubicacion.lat;
        var lng = ubicacion.lng;
        var nombre = ubicacion.nombre;
        var usuario = ubicacion.usuario;
        var velocidad = ubicacion.velocidad || 0;
        var fecha = new Date(ubicacion.fecha).toLocaleString();

        // Buscar si ya existe un marcador para este usuario
        var marker = markersMap.get(id);

        if (marker) {
            // Si existe, actualizar posición y popup
            marker.setLatLng([lat, lng]);
            marker.getPopup().setContent(`
                <strong>${nombre}</strong><br>
                Usuario: ${usuario}<br>
                Velocidad: ${velocidad} km/h<br>
                Última actualización: ${fecha}
            `);
        } else {
            // Si no existe, crear uno nuevo
            var nuevoMarker = L.marker([lat, lng])
                .bindPopup(`
                    <strong>${nombre}</strong><br>
                    Usuario: ${usuario}<br>
                    Velocidad: ${velocidad} km/h<br>
                    Última actualización: ${fecha}
                `);
            markers.addLayer(nuevoMarker);
            markersMap.set(id, nuevoMarker);
        }

        // Actualizar la fila de la tabla
        var estado = velocidad > 0 ? 
            '<span class="badge bg-success">En movimiento</span>' : 
            '<span class="badge bg-info">Detenido</span>';

        var fila = $(`#fila-usuario-${id}`);
        if (fila.length) {
            // Actualizar fila existente
            fila.find('td:eq(3)').text(lat.toFixed(6) + ', ' + lng.toFixed(6));
            fila.find('td:eq(4)').html(estado);
        } else {
            // Agregar nueva fila si no existe
            $('#instaladoresBody').append(`
                <tr id="fila-usuario-${id}">
                    <td><strong>${nombre}</strong></td>
                    <td>${usuario}</td>
                    <td><code>${ubicacion.device_id || 'N/A'}</code></td>
                    <td>${lat.toFixed(6)}, ${lng.toFixed(6)}</td>
                    <td>${estado}</td>
                </tr>
            `);
        }
    }

    // =============================================================
    // 4. FUNCIÓN: Mostrar notificaciones de geocercas
    // =============================================================
    function mostrarNotificacion(alerta) {
        // Extraer datos de la alerta
        const tipo = alerta.tipo === 'entrada' ? '🟢 ENTRÓ' : '🔴 Salió de';
        const nombre = alerta.geocerca?.nombre || 'zona';
        const usuario = alerta.usuario?.nombre || 'Instalador';

        console.log(`📍 ${tipo} la zona: ${nombre} - ${usuario}`);

        // Crear un toast de Bootstrap
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${alerta.tipo === 'entrada' ? 'success' : 'danger'} border-0 show" role="alert" aria-live="assertive" aria-atomic="true" style="position: relative; margin-bottom: 10px; min-width: 300px;">
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

        // Agregar la notificación al contenedor
        $('#notificaciones-container').append(toastHtml);

        // Auto-eliminar después de 10 segundos
        setTimeout(() => {
            $('.toast').last().remove();
        }, 10000);
    }

    // =============================================================
    // 5. WEBSOCKET: Escuchar eventos en tiempo real
    // =============================================================

    // 5.1. Escuchar actualizaciones de ubicaciones (canal "ubicaciones")
    function iniciarWebSocketUbicaciones() {
        if (typeof window.Echo === 'undefined') {
            console.warn('⚠️ Laravel Echo no está disponible. Las actualizaciones en tiempo real no funcionarán.');
            return;
        }

        window.Echo.channel('ubicaciones')
            .listen('ubicacion.actualizada', (e) => {
                console.log('📍 Nueva ubicación recibida (WebSocket):', e.ubicacion);
                actualizarMarcador(e.ubicacion);
            });
    }

    // 5.2. Escuchar alertas de geocercas (canal "geocercas")
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

    // =============================================================
    // 6. INICIALIZACIÓN
    // =============================================================

    // Cargar ubicaciones iniciales
    cargarUbicacionesIniciales();

    // Iniciar WebSockets (ubicaciones y geocercas) después de que la página esté lista
    $(document).ready(function() {
        iniciarWebSocketUbicaciones();
        iniciarWebSocketGeocercas();
    });

    // Actualización manual con el botón "Actualizar"
    $('#refreshBtn').on('click', function() {
        $(this).html('<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Actualizando...');
        cargarUbicacionesIniciales();
        setTimeout(() => {
            $(this).html('<i class="bi bi-arrow-clockwise"></i> Actualizar');
        }, 1000);
    });

    // (Opcional) Actualización automática cada 15 segundos como respaldo
    // setInterval(cargarUbicacionesIniciales, 15000);
</script>
@endpush
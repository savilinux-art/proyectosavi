@extends('layouts.app')

@section('page-title', 'Mapa de Instaladores')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.3.0/dist/leaflet-routing-machine.css" />
    <style>
        #map {
            height: 70vh;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .info-panel {
            max-height: 70vh;
            overflow-y: auto;
        }
        .device-marker {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .device-marker i {
            font-size: 28px;
            color: #dc3545;
        }
        .device-marker.online i {
            color: #28a745;
        }
        .device-marker.offline i {
            color: #6c757d;
        }
        .device-popup .card-body {
            padding: 10px;
        }
        .device-popup .device-name {
            font-weight: bold;
            font-size: 1.1rem;
        }
        .device-popup .device-info {
            font-size: 0.9rem;
            color: #666;
        }
        .last-update {
            font-size: 0.8rem;
            color: #999;
        }
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 8px;
        }
        .loading-overlay.hidden {
            display: none;
        }
        .status-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
        }
    </style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-geo-alt"></i> Ubicación de Instaladores</h1>
    <div>
        <button class="btn btn-primary" id="refreshMap">
            <i class="bi bi-arrow-clockwise"></i> Actualizar
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Mapa -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body position-relative">
                <div id="map"></div>
                <div class="loading-overlay" id="loadingOverlay">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted small">
                <i class="bi bi-info-circle"></i> Las ubicaciones se actualizan cada 30 segundos.
            </div>
        </div>
    </div>

    <!-- Panel lateral de dispositivos -->
    <div class="col-md-4">
        <div class="card info-panel">
            <div class="card-header">
                <h5 class="mb-0">📱 Dispositivos</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="deviceList">
                    <li class="list-group-item text-center text-muted">
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Cargando dispositivos...
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between small">
                    <span><span class="badge bg-success me-1">&nbsp;</span> En línea</span>
                    <span><span class="badge bg-danger me-1">&nbsp;</span> Desconectado</span>
                    <span><span class="badge bg-secondary me-1">&nbsp;</span> Sin datos</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.3.0/dist/leaflet-routing-machine.js"></script>

    <script>
        let map;
        let markers = {};
        let updateInterval;

        function getStatusColor(status) {
            if (status === 'online') return 'success';
            if (status === 'offline') return 'danger';
            return 'secondary';
        }

        function getStatusText(status) {
            if (status === 'online') return 'En línea';
            if (status === 'offline') return 'Desconectado';
            return 'Sin datos';
        }

        function createMarkerIcon(status) {
            const color = status === 'online' ? '#28a745' : status === 'offline' ? '#dc3545' : '#6c757d';
            return L.divIcon({
                className: 'device-marker',
                html: `<i class="bi bi-person-fill" style="color: ${color}; font-size: 32px;"></i>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32],
            });
        }

        function initMap() {
            // Centrar en la ubicación aproximada de México
            map = L.map('map').setView([20.5, -99.5], 5);

            // Capa de mapa (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Cargar dispositivos
            loadDevices();

            // Actualizar cada 30 segundos
            updateInterval = setInterval(loadDevices, 30000);
        }

        function loadDevices() {
            $('#loadingOverlay').removeClass('hidden');

            $.ajax({
                url: '{{ route("mapa.positions") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    updateDevices(data);
                    updateDeviceList(data);
                    $('#loadingOverlay').addClass('hidden');
                },
                error: function(xhr) {
                    console.error('Error al cargar ubicaciones:', xhr);
                    $('#loadingOverlay').addClass('hidden');
                }
            });
        }

        function updateDevices(devices) {
            // Limpiar marcadores existentes
            Object.values(markers).forEach(marker => map.removeLayer(marker));
            markers = {};

            // Crear nuevos marcadores
            devices.forEach(device => {
                if (!device.latitude || !device.longitude) return;

                const popupContent = `
                    <div class="device-popup">
                        <div class="card-body">
                            <div class="device-name">${device.name}</div>
                            <div class="device-info">
                                <span class="badge bg-${getStatusColor(device.status)} status-badge">
                                    ${getStatusText(device.status)}
                                </span>
                            </div>
                            <div class="device-info mt-1">
                                <i class="bi bi-speedometer2"></i> ${device.speed ? device.speed.toFixed(1) : '0'} km/h
                            </div>
                            <div class="device-info">
                                <i class="bi bi-geo"></i> ${device.latitude.toFixed(6)}, ${device.longitude.toFixed(6)}
                            </div>
                            <div class="last-update">
                                <i class="bi bi-clock"></i> Última actualización: ${device.last_update ? new Date(device.last_update).toLocaleString() : 'N/A'}
                            </div>
                            <hr class="my-1">
                            <button class="btn btn-sm btn-outline-primary" onclick="centerDevice(${device.latitude}, ${device.longitude}, '${device.name}')">
                                <i class="bi bi-crosshair"></i> Centrar
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="viewHistory(${device.id}, '${device.name}')">
                                <i class="bi bi-clock-history"></i> Historial
                            </button>
                        </div>
                    </div>
                `;

                const marker = L.marker([device.latitude, device.longitude], {
                    icon: createMarkerIcon(device.status)
                })
                .bindPopup(popupContent)
                .addTo(map);

                markers[device.id] = marker;
            });

            // Si hay dispositivos, ajustar el mapa para mostrarlos todos
            if (devices.length > 0) {
                const validDevices = devices.filter(d => d.latitude && d.longitude);
                if (validDevices.length > 0) {
                    const group = L.featureGroup(Object.values(markers));
                    map.fitBounds(group.getBounds(), { padding: [50, 50] });
                }
            }
        }

        function updateDeviceList(devices) {
            const list = $('#deviceList');
            list.empty();

            if (devices.length === 0) {
                list.html('<li class="list-group-item text-center text-muted">No hay dispositivos disponibles</li>');
                return;
            }

            devices.forEach(device => {
                const statusClass = getStatusColor(device.status);
                const statusText = getStatusText(device.status);
                const lastUpdate = device.last_update ? new Date(device.last_update).toLocaleString() : 'Sin datos';

                const html = `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${device.name}</strong>
                            <br>
                            <small class="text-muted">ID: ${device.unique_id}</small>
                            <div class="small text-muted">
                                <i class="bi bi-clock"></i> ${lastUpdate}
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-${statusClass}">${statusText}</span>
                            ${device.speed ? `<br><small class="text-muted">${device.speed.toFixed(1)} km/h</small>` : ''}
                            <br>
                            <button class="btn btn-sm btn-outline-primary mt-1" onclick="centerDevice(${device.latitude || 0}, ${device.longitude || 0}, '${device.name}')" ${!device.latitude ? 'disabled' : ''}>
                                <i class="bi bi-crosshair"></i>
                            </button>
                        </div>
                    </li>
                `;
                list.append(html);
            });
        }

        function centerDevice(lat, lng, name) {
            if (!lat || !lng) return;
            map.setView([lat, lng], 15);
            // Buscar y abrir el popup del marcador correspondiente
            Object.values(markers).forEach(marker => {
                const pos = marker.getLatLng();
                if (Math.abs(pos.lat - lat) < 0.0001 && Math.abs(pos.lng - lng) < 0.0001) {
                    marker.openPopup();
                }
            });
        }

        function viewHistory(deviceId, name) {
            // Implementar en el futuro: mostrar historial de rutas
            alert(`Historial de ${name} - Pendiente de implementar`);
        }

        // Cargar el mapa al iniciar
        $(document).ready(function() {
            initMap();

            // Botón de refrescar
            $('#refreshMap').click(function() {
                loadDevices();
                $(this).html('<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm me-1"></i> Actualizando...');
                setTimeout(() => {
                    $(this).html('<i class="bi bi-arrow-clockwise"></i> Actualizar');
                }, 2000);
            });
        });

        // Limpiar intervalo al salir de la página
        $(window).on('beforeunload', function() {
            if (updateInterval) clearInterval(updateInterval);
        });
    </script>
@endsection
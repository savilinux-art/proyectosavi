@extends('layouts.app')

@section('page-title', 'Rastreo GPS')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <h1><i class="bi bi-geo-alt-fill"></i> Rastreo GPS en Vivo</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>
    </div>
</div>

<div class="row">
    <!-- Panel lateral de dispositivos -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Dispositivos</h5>
            </div>
            <div class="card-body p-0" style="max-height: 70vh; overflow-y: auto;">
                <div id="device-list" class="list-group list-group-flush"></div>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-body p-0">
                <div id="map" style="height: 70vh; width: 100%; border-radius: 5px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .device-item { cursor: pointer; }
    .device-item:hover { background-color: #f8f9fa; }
    .device-online { border-left: 4px solid #198754; }
    .device-offline { border-left: 4px solid #dc3545; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const socketUrl = @json($socketUrl);
    const initialDevices = @json($devices);

    // Inicializar mapa
    const map = L.map('map').setView([19.4326, -99.1332], 6); // Centrado en México
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const markers = {};
    const deviceList = document.getElementById('device-list');

    function renderDeviceList(devices) {
        deviceList.innerHTML = '';
        devices.forEach(device => {
            const statusClass = device.status === 'online' ? 'device-online' : 'device-offline';
            const pos = device.position;
            const lat = pos ? pos.latitude.toFixed(4) : 'N/A';
            const lng = pos ? pos.longitude.toFixed(4) : 'N/A';
            const speed = pos ? pos.speed : 0;

            const item = document.createElement('a');
            item.href = '#';
            item.className = `list-group-item list-group-item-action device-item ${statusClass}`;
            item.innerHTML = `
                <div class="d-flex w-100 justify-content-between">
                    <strong>${device.name}</strong>
                    <span class="badge bg-${device.status === 'online' ? 'success' : 'secondary'}">${device.status || 'unknown'}</span>
                </div>
                <small>Lat: ${lat} | Lng: ${lng}</small><br>
                <small>Vel: ${speed} km/h</small>
            `;
            item.addEventListener('click', (e) => {
                e.preventDefault();
                focusDevice(device.id);
            });
            deviceList.appendChild(item);
        });
    }

    function updateMarker(device) {
        if (!device.position) return;
        const { latitude, longitude } = device.position;

        if (markers[device.id]) {
            markers[device.id].setLatLng([latitude, longitude]);
        } else {
            markers[device.id] = L.marker([latitude, longitude])
                .addTo(map)
                .bindPopup(`<b>${device.name}</b><br>Vel: ${device.position.speed} km/h`);
        }
    }

    function focusDevice(deviceId) {
        if (markers[deviceId]) {
            map.setView(markers[deviceId].getLatLng(), 15);
            markers[deviceId].openPopup();
        }
    }

    // Render inicial
    renderDeviceList(initialDevices);
    initialDevices.forEach(updateMarker);

    // WebSocket en vivo
    if (socketUrl) {
        const socket = new WebSocket(socketUrl);
        socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.devices) {
                renderDeviceList(data.devices);
                data.devices.forEach(updateMarker);
            } else if (data.device) {
                updateMarker(data.device);
            }
        };
        socket.onclose = () => setTimeout(() => location.reload(), 5000);
    }
</script>
@endpush
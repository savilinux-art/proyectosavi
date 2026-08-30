@extends('layouts.app')
@section('page-title', 'Instalaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tools"></i> Instalaciones</h1>
    <a href="{{ route('instalaciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva Instalación</a>
</div>
<div class="card"><div class="card-body">
    <table class="table table-striped" id="instalacionesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Instaladores</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estatus</th>
                <th>Ubicación Inicio</th>
                <th>Ubicación Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instalaciones as $instalacion)
            <tr>
                <td>{{ $instalacion->id }}</td>
                <td>{{ $instalacion->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                <td>
                    @foreach($instalacion->instaladores as $inst)
                        <span class="badge bg-primary">{{ $inst->nombre }}</span>
                    @endforeach
                    @if($instalacion->instaladores->isEmpty())
                        <span class="text-muted">Sin asignar</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($instalacion->fecha_hora_inicio)->format('d/m/Y H:i') }}</td>
                <td>{{ $instalacion->fecha_hora_fin ? \Carbon\Carbon::parse($instalacion->fecha_hora_fin)->format('d/m/Y H:i') : 'Pendiente' }}</td>
                <td><span class="badge bg-{{ $instalacion->estatus_instalacion == 'entrega' ? 'success' : ($instalacion->estatus_instalacion == 'pruebas' ? 'warning' : ($instalacion->estatus_instalacion == 'programacion' ? 'info' : 'primary')) }}">{{ ucfirst($instalacion->estatus_instalacion ?? 'N/A') }}</span></td>
                
                <!-- Ubicación Inicio -->
                <td>
                    @php
                        $ubicacionInicio = $instalacion->ubicaciones->where('tipo', 'inicio')->first();
                    @endphp
                    @if($ubicacionInicio)
                        <a href="https://www.google.com/maps?q={{ $ubicacionInicio->latitud }},{{ $ubicacionInicio->longitud }}" target="_blank">
                            Ver mapa
                        </a>
                        <br>
                        <small>{{ \Carbon\Carbon::parse($ubicacionInicio->fecha_hora)->format('d/m/Y H:i') }}</small>
                    @else
                        <span class="text-muted">Sin registrar</span>
                    @endif
                </td>
                
                <!-- Ubicación Fin -->
                <td>
                    @php
                        $ubicacionFin = $instalacion->ubicaciones->where('tipo', 'fin')->first();
                    @endphp
                    @if($ubicacionFin)
                        <a href="https://www.google.com/maps?q={{ $ubicacionFin->latitud }},{{ $ubicacionFin->longitud }}" target="_blank">
                            Ver mapa
                        </a>
                        <br>
                        <small>{{ \Carbon\Carbon::parse($ubicacionFin->fecha_hora)->format('d/m/Y H:i') }}</small>
                    @else
                        <span class="text-muted">Sin registrar</span>
                    @endif
                </td>
                
                <!-- Acciones -->
                <td>
                    <div class="btn-group">
                        <a href="{{ route('instalaciones.show', $instalacion->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('instalaciones.edit', $instalacion->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('instalaciones.destroy', $instalacion->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta instalación?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('#instalacionesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'desc']]
        });
    });
</script>
@endpush
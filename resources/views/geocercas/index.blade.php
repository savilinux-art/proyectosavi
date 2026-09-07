@extends('layouts.app')

@section('page-title', 'Geocercas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-pin-map"></i> Geocercas</h1>
    <a href="{{ route('geocercas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Geocerca
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="geocercasTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Radio (m)</th>
                        <th>Color</th>
                        <th>Proyecto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($geocercas as $g)
                    <tr>
                        <td>{{ $g->id }}</td>
                        <td><strong>{{ $g->nombre }}</strong></td>
                        <td>{{ $g->latitud }}, {{ $g->longitud }}</td>
                        <td>{{ $g->radio }}</td>
                        <td>
                            <span style="display:inline-block; width:20px; height:20px; background-color:{{ $g->color }}; border-radius:50%;"></span>
                        </td>
                        <td>{{ $g->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                        <td>
                            @if($g->activa)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('geocercas.show', $g->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('geocercas.edit', $g->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('geocercas.destroy', $g->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta geocerca?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#geocercasTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'desc']]
        });
    });
</script>
@endpush
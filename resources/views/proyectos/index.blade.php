@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-folder"></i> Proyectos</h1>
    <a href="{{ route('proyectos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Proyecto
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="proyectosTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proyecto</th>
                        <th>Correo</th>
                        <th>Ubicación</th>
                        <th>Modificado por</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                    <tr>
                        <td>{{ $proyecto->id }}</td>
                        <td>
                            <strong>{{ $proyecto->nombre_proyecto }}</strong>
                            <br>
                            <small class="text-muted">{{ $proyecto->venta->titulo_venta ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $proyecto->correo_electronico }}</td>
                        <td>{{ $proyecto->ubicacion ?? 'N/A' }}</td>
                        <td>{{ $proyecto->modificadoPor->nombre ?? 'N/A' }}</td>
                        <td>{{ $proyecto->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('proyectos.destroy', $proyecto->id) }}" method="POST" 
                                      onsubmit="return confirm('¿Eliminar este proyecto?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
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
        $('#proyectosTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'desc']]
        });
    });
</script>
@endpush
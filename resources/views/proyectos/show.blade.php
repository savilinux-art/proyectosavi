@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-folder"></i> Detalle del Proyecto</h1>
    <div>
        <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Información del Proyecto</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID</th>
                        <td>{{ $proyecto->id }}</td>
                    </tr>
                    <tr>
                        <th>Nombre del Proyecto</th>
                        <td>{{ $proyecto->nombre_proyecto }}</td>
                    </tr>
                    <tr>
                        <th>Correo Electrónico</th>
                        <td>{{ $proyecto->correo_electronico }}</td>
                    </tr>
                    <tr>
                        <th>Ubicación</th>
                        <td>{{ $proyecto->ubicacion ?? 'No especificada' }}</td>
                    </tr>
                    <tr>
                        <th>Credenciales</th>
                        <td>{{ $proyecto->credenciales ?? 'No especificadas' }}</td>
                    </tr>
                    <tr>
                        <th>Modificado por</th>
                        <td>{{ $proyecto->modificadoPor->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Creación</th>
                        <td>{{ $proyecto->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Última Actualización</th>
                        <td>{{ $proyecto->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Documentos del Proyecto</h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @if($proyecto->propuesta_economica)
                        <div class="list-group-item">
                            <i class="bi bi-file-pdf text-danger"></i>
                            Propuesta Económica
                            <a href="#" class="btn btn-sm btn-danger float-end">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    @endif
                    
                    @if($proyecto->archivo_as_built)
                        <div class="list-group-item">
                            <i class="bi bi-file-earmark text-primary"></i>
                            Archivo As-Built
                            <a href="#" class="btn btn-sm btn-primary float-end">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    @endif
                    
                    @if($proyecto->salida_inventario)
                        <div class="list-group-item">
                            <i class="bi bi-file-earmark-excel text-success"></i>
                            Salida de Inventario
                            <a href="#" class="btn btn-sm btn-success float-end">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    @endif
                    
                    @if($proyecto->devolucion_inventario)
                        <div class="list-group-item">
                            <i class="bi bi-file-earmark-arrow-down text-warning"></i>
                            Devolución de Inventario
                            <a href="#" class="btn btn-sm btn-warning float-end">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    @endif
                    
                    @if(!$proyecto->propuesta_economica && !$proyecto->archivo_as_built && !$proyecto->salida_inventario && !$proyecto->devolucion_inventario)
                        <div class="list-group-item text-muted text-center">
                            <i class="bi bi-file-earmark"></i>
                            <p>No hay documentos disponibles</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-folder-plus"></i> Nuevo Proyecto</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('proyectos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre_proyecto" class="form-label">Nombre del Proyecto *</label>
                    <select class="form-select @error('nombre_proyecto') is-invalid @enderror" 
                            id="nombre_proyecto" name="nombre_proyecto" required>
                        <option value="">Seleccionar proyecto</option>
                        @foreach($ventas as $venta)
                            <option value="{{ $venta->nombre_proyecto }}" {{ old('nombre_proyecto') == $venta->nombre_proyecto ? 'selected' : '' }}>
                                {{ $venta->nombre_proyecto }} - {{ $venta->titulo_venta }}
                            </option>
                        @endforeach
                    </select>
                    @error('nombre_proyecto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Solo proyectos con venta ganada</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="correo_electronico" class="form-label">Correo Electrónico *</label>
                    <input type="email" class="form-control @error('correo_electronico') is-invalid @enderror" 
                           id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico') }}" required>
                    @error('correo_electronico')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ubicacion" class="form-label">Ubicación</label>
                    <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" 
                           id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" 
                           placeholder="Dirección del proyecto">
                    @error('ubicacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="credenciales" class="form-label">Credenciales</label>
                    <input type="text" class="form-control @error('credenciales') is-invalid @enderror" 
                           id="credenciales" name="credenciales" value="{{ old('credenciales') }}" 
                           placeholder="Usuarios y contraseñas de acceso">
                    @error('credenciales')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="propuesta_economica" class="form-label">Propuesta Económica</label>
                    <input type="file" class="form-control @error('propuesta_economica') is-invalid @enderror" 
                           id="propuesta_economica" name="propuesta_economica" accept=".pdf,.doc,.docx">
                    @error('propuesta_economica')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Formatos: PDF, DOC, DOCX. Máximo 5MB</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="archivo_as_built" class="form-label">Archivo As-Built</label>
                    <input type="file" class="form-control @error('archivo_as_built') is-invalid @enderror" 
                           id="archivo_as_built" name="archivo_as_built" accept=".pdf,.dwg">
                    @error('archivo_as_built')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Formatos: PDF, DWG. Máximo 5MB</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="salida_inventario" class="form-label">Salida de Inventario</label>
                    <input type="file" class="form-control @error('salida_inventario') is-invalid @enderror" 
                           id="salida_inventario" name="salida_inventario" accept=".pdf">
                    @error('salida_inventario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Formato: PDF. Máximo 5MB</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="devolucion_inventario" class="form-label">Devolución de Inventario</label>
                    <input type="file" class="form-control @error('devolucion_inventario') is-invalid @enderror" 
                           id="devolucion_inventario" name="devolucion_inventario" accept=".pdf">
                    @error('devolucion_inventario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Formato: PDF. Máximo 5MB</small>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('proyectos.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Proyecto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('page-title', 'Nueva Instalación')
@section('content')
<div class="card">
    <div class="card-header"><h4><i class="bi bi-tools"></i> Nueva Instalación</h4></div>
    <div class="card-body">
        <form action="{{ route('instalaciones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3"><label for="nombre_proyecto" class="form-label">Proyecto *</label><select class="form-select @error('nombre_proyecto') is-invalid @enderror" id="nombre_proyecto" name="nombre_proyecto" required><option value="">Seleccionar</option>@foreach($proyectos as $p)<option value="{{ $p->nombre_proyecto }}" {{ old('nombre_proyecto')==$p->nombre_proyecto?'selected':'' }}>{{ $p->nombre_proyecto }}</option>@endforeach</select>@error('nombre_proyecto')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="estatus_instalacion" class="form-label">Estatus *</label><select class="form-select @error('estatus_instalacion') is-invalid @enderror" id="estatus_instalacion" name="estatus_instalacion" required>@foreach($estatus as $e)<option value="{{ $e }}" {{ old('estatus_instalacion')==$e?'selected':'' }}>{{ ucfirst($e) }}</option>@endforeach</select>@error('estatus_instalacion')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="fecha_hora_inicio" class="form-label">Fecha Inicio *</label><input type="datetime-local" class="form-control @error('fecha_hora_inicio') is-invalid @enderror" id="fecha_hora_inicio" name="fecha_hora_inicio" value="{{ old('fecha_hora_inicio', now()->format('Y-m-d\TH:i')) }}" required>@error('fecha_hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="fecha_hora_fin" class="form-label">Fecha Fin</label><input type="datetime-local" class="form-control @error('fecha_hora_fin') is-invalid @enderror" id="fecha_hora_fin" name="fecha_hora_fin" value="{{ old('fecha_hora_fin') }}">@error('fecha_hora_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="ubicacion_actual" class="form-label">Ubicación</label><input type="text" class="form-control @error('ubicacion_actual') is-invalid @enderror" id="ubicacion_actual" name="ubicacion_actual" value="{{ old('ubicacion_actual') }}">@error('ubicacion_actual')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="instaladores" class="form-label">Instaladores *</label><select class="form-select @error('instaladores') is-invalid @enderror" name="instaladores[]" id="instaladores" multiple required>@foreach($instaladores as $inst)<option value="{{ $inst->usuario }}" {{ in_array($inst->usuario, old('instaladores', [])) ? 'selected' : '' }}>{{ $inst->nombre }}</option>@endforeach</select>@error('instaladores')<div class="invalid-feedback">{{ $message }}</div>@enderror<small class="text-muted">Presiona Ctrl/Cmd para seleccionar varios</small></div>
            </div>
            <div class="mb-3"><label class="form-label">Checklist</label><div class="card"><div class="card-body">@php $items=['material_listo'=>'Material listo','herramientas_disponibles'=>'Herramientas disponibles','seguridad_equipo'=>'Equipo de seguridad','permisos_obtenidos'=>'Permisos obtenidos','acceso_sitio'=>'Acceso al sitio','documentacion_revisada'=>'Documentación revisada'];@endphp @foreach($items as $key=>$label)<div class="form-check"><input class="form-check-input" type="checkbox" name="check_list[]" value="{{ $key }}" id="{{ $key }}"><label class="form-check-label" for="{{ $key }}">{{ $label }}</label></div>@endforeach</div></div></div>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="evidencia_inicio" class="form-label">Evidencia Inicio</label><input type="file" class="form-control @error('evidencia_inicio') is-invalid @enderror" id="evidencia_inicio" name="evidencia_inicio" accept=".jpeg,.png,.jpg,.pdf">@error('evidencia_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="incidencias" class="form-label">Incidencias</label><input type="file" class="form-control @error('incidencias') is-invalid @enderror" id="incidencias" name="incidencias" accept=".jpeg,.png,.jpg,.pdf">@error('incidencias')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="evidencia_fin" class="form-label">Evidencia Fin</label><input type="file" class="form-control @error('evidencia_fin') is-invalid @enderror" id="evidencia_fin" name="evidencia_fin" accept=".jpeg,.png,.jpg,.pdf">@error('evidencia_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="d-flex justify-content-end"><a href="{{ route('instalaciones.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button></div>
        </form>
    </div>
</div>
@endsection
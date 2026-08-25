@extends('layouts.app')
@section('page-title', 'Nueva Asignación')
@section('content')
<div class="card">
    <div class="card-header"><h4><i class="bi bi-person-plus"></i> Nueva Asignación</h4></div>
    <div class="card-body">
        <form action="{{ route('asignaciones.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3"><label for="instalacion_id" class="form-label">Instalación *</label><select class="form-select @error('instalacion_id') is-invalid @enderror" name="instalacion_id" id="instalacion_id" required><option value="">Seleccionar</option>@foreach($instalacionesDisponibles as $i)<option value="{{ $i->id }}" {{ old('instalacion_id')==$i->id?'selected':'' }}>{{ $i->proyecto->nombre_proyecto ?? 'N/A' }} - {{ $i->estatus_instalacion ?? 'N/A' }}</option>@endforeach</select>@error('instalacion_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="fecha_hora_inicio" class="form-label">Fecha Inicio *</label><input type="datetime-local" class="form-control @error('fecha_hora_inicio') is-invalid @enderror" name="fecha_hora_inicio" id="fecha_hora_inicio" value="{{ old('fecha_hora_inicio', now()->addDay()->format('Y-m-d\TH:i')) }}" required>@error('fecha_hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mb-3"><label for="instaladores" class="form-label">Instaladores *</label><select class="form-select @error('instaladores') is-invalid @enderror" name="instaladores[]" id="instaladores" multiple required>@foreach($instaladores as $inst)<option value="{{ $inst->usuario }}" {{ in_array($inst->usuario, old('instaladores', [])) ? 'selected' : '' }}>{{ $inst->nombre }} ({{ $inst->instalaciones_asignadas_count }} asignaciones)</option>@endforeach</select>@error('instaladores')<div class="invalid-feedback">{{ $message }}</div>@enderror<small class="text-muted">Ctrl/Cmd para seleccionar varios</small></div>
            <div class="mb-3"><label for="observaciones" class="form-label">Observaciones</label><input type="text" class="form-control @error('observaciones') is-invalid @enderror" name="observaciones" id="observaciones" value="{{ old('observaciones') }}">@error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="d-flex justify-content-end"><a href="{{ route('asignaciones.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Asignar</button></div>
        </form>
    </div>
</div>
@endsection
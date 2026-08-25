@extends('layouts.app')
@section('page-title', 'Editar Asignación')
@section('content')
<div class="card">
    <div class="card-header"><h4><i class="bi bi-pencil"></i> Editar Asignación</h4></div>
    <div class="card-body">
        <form action="{{ route('asignaciones.update', $instalacion->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Proyecto</label><p class="form-control-plaintext">{{ $instalacion->proyecto->nombre_proyecto ?? 'N/A' }}</p></div>
                <div class="col-md-6 mb-3"><label for="estatus_instalacion" class="form-label">Estatus *</label><select class="form-select @error('estatus_instalacion') is-invalid @enderror" name="estatus_instalacion" id="estatus_instalacion" required>@foreach($estatus as $e)<option value="{{ $e }}" {{ old('estatus_instalacion', $instalacion->estatus_instalacion)==$e?'selected':'' }}>{{ ucfirst($e) }}</option>@endforeach</select>@error('estatus_instalacion')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="fecha_hora_inicio" class="form-label">Fecha Inicio *</label><input type="datetime-local" class="form-control @error('fecha_hora_inicio') is-invalid @enderror" name="fecha_hora_inicio" id="fecha_hora_inicio" value="{{ old('fecha_hora_inicio', date('Y-m-d\TH:i', strtotime($instalacion->fecha_hora_inicio))) }}" required>@error('fecha_hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="fecha_hora_fin" class="form-label">Fecha Fin</label><input type="datetime-local" class="form-control @error('fecha_hora_fin') is-invalid @enderror" name="fecha_hora_fin" id="fecha_hora_fin" value="{{ old('fecha_hora_fin', $instalacion->fecha_hora_fin ? date('Y-m-d\TH:i', strtotime($instalacion->fecha_hora_fin)) : '') }}">@error('fecha_hora_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mb-3"><label for="instaladores" class="form-label">Instaladores *</label><select class="form-select @error('instaladores') is-invalid @enderror" name="instaladores[]" id="instaladores" multiple required>@foreach($instaladores as $inst)<option value="{{ $inst->usuario }}" {{ in_array($inst->usuario, old('instaladores', $instaladoresSeleccionados ?? [])) ? 'selected' : '' }}>{{ $inst->nombre }}</option>@endforeach</select>@error('instaladores')<div class="invalid-feedback">{{ $message }}</div>@enderror<small class="text-muted">Ctrl/Cmd para seleccionar varios</small></div>
            <div class="d-flex justify-content-end"><a href="{{ route('asignaciones.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Actualizar</button></div>
        </form>
    </div>
</div>
@endsection
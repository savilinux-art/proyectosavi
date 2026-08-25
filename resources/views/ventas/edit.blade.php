@extends('layouts.app')
@section('page-title', 'Editar Venta')
@section('content')
<div class="card">
    <div class="card-header"><h4><i class="bi bi-pencil"></i> Editar Venta</h4></div>
    <div class="card-body">
        <form action="{{ route('ventas.update', $venta->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3"><label for="titulo_venta" class="form-label">Título *</label><input type="text" class="form-control @error('titulo_venta') is-invalid @enderror" id="titulo_venta" name="titulo_venta" value="{{ old('titulo_venta', $venta->titulo_venta) }}" required>@error('titulo_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="nombre_proyecto" class="form-label">Proyecto *</label><input type="text" class="form-control @error('nombre_proyecto') is-invalid @enderror" id="nombre_proyecto" name="nombre_proyecto" value="{{ old('nombre_proyecto', $venta->nombre_proyecto) }}" required>@error('nombre_proyecto')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="moneda" class="form-label">Moneda *</label><select class="form-select @error('moneda') is-invalid @enderror" id="moneda" name="moneda" required><option value="MXN" {{ old('moneda', $venta->moneda)=='MXN'?'selected':'' }}>MXN</option><option value="USD" {{ old('moneda', $venta->moneda)=='USD'?'selected':'' }}>USD</option><option value="EUR" {{ old('moneda', $venta->moneda)=='EUR'?'selected':'' }}>EUR</option></select>@error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="monto_venta" class="form-label">Monto *</label><input type="number" step="0.01" class="form-control @error('monto_venta') is-invalid @enderror" id="monto_venta" name="monto_venta" value="{{ old('monto_venta', $venta->monto_venta) }}" required>@error('monto_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="vendedor" class="form-label">Vendedor *</label><select class="form-select @error('vendedor') is-invalid @enderror" id="vendedor" name="vendedor" required>@foreach($vendedores as $v)<option value="{{ $v->usuario }}" {{ old('vendedor', $venta->vendedor)==$v->usuario?'selected':'' }}>{{ $v->nombre }}</option>@endforeach</select>@error('vendedor')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mb-3"><label for="requerimiento_venta" class="form-label">Requerimiento *</label><textarea class="form-control @error('requerimiento_venta') is-invalid @enderror" id="requerimiento_venta" name="requerimiento_venta" rows="3" required>{{ old('requerimiento_venta', $venta->requerimiento_venta) }}</textarea>@error('requerimiento_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="fecha_hora_levantamiento" class="form-label">Fecha y Hora *</label><input type="datetime-local" class="form-control @error('fecha_hora_levantamiento') is-invalid @enderror" id="fecha_hora_levantamiento" name="fecha_hora_levantamiento" value="{{ old('fecha_hora_levantamiento', date('Y-m-d\TH:i', strtotime($venta->fecha_hora_levantamiento))) }}" required>@error('fecha_hora_levantamiento')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="estatus" class="form-label">Estatus *</label><select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus" required>@foreach(['prospeccion','levantamiento','cotizacion','cierre_venta'] as $e)<option value="{{ $e }}" {{ old('estatus', $venta->estatus)==$e?'selected':'' }}>{{ ucfirst($e) }}</option>@endforeach</select>@error('estatus')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="venta_ganada" class="form-label">Venta Ganada *</label><select class="form-select @error('venta_ganada') is-invalid @enderror" id="venta_ganada" name="venta_ganada" required><option value="1" {{ old('venta_ganada', $venta->venta_ganada) ? 'selected' : '' }}>Sí</option><option value="0" {{ old('venta_ganada', $venta->venta_ganada) ? '' : 'selected' }}>No</option></select>@error('venta_ganada')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="razon_perdida_venta" class="form-label">Razón de Pérdida</label><input type="text" class="form-control @error('razon_perdida_venta') is-invalid @enderror" id="razon_perdida_venta" name="razon_perdida_venta" value="{{ old('razon_perdida_venta', $venta->razon_perdida_venta) }}">@error('razon_perdida_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="cotizacion" class="form-label">Cotización</label>@if($venta->cotizacion)<div class="alert alert-info"><i class="bi bi-file-pdf"></i> Documento actual cargado</div>@endif<input type="file" class="form-control @error('cotizacion') is-invalid @enderror" id="cotizacion" name="cotizacion" accept=".pdf,.doc,.docx">@error('cotizacion')<div class="invalid-feedback">{{ $message }}</div>@enderror<small class="text-muted">Dejar vacío para mantener</small></div>
                <div class="col-md-6 mb-3"><label for="levantamiento" class="form-label">Levantamiento</label>@if($venta->levantamiento)<div class="alert alert-info"><i class="bi bi-file-earmark"></i> Documento actual cargado</div>@endif<input type="file" class="form-control @error('levantamiento') is-invalid @enderror" id="levantamiento" name="levantamiento" accept=".pdf,.doc,.docx">@error('levantamiento')<div class="invalid-feedback">{{ $message }}</div>@enderror<small class="text-muted">Dejar vacío para mantener</small></div>
            </div>
            <div class="d-flex justify-content-end"><a href="{{ route('ventas.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Actualizar</button></div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('page-title', 'Generar Reporte')
@section('content')
<div class="card">
    <div class="card-header"><h4><i class="bi bi-plus-circle"></i> Generar Nuevo Reporte</h4></div>
    <div class="card-body">
        <form action="{{ route('reportes.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3"><label for="tipo" class="form-label">Tipo *</label><select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required><option value="">Seleccionar</option><option value="ventas" {{ old('tipo')=='ventas'?'selected':'' }}>Ventas</option><option value="inventario" {{ old('tipo')=='inventario'?'selected':'' }}>Inventario</option><option value="instalaciones" {{ old('tipo')=='instalaciones'?'selected':'' }}>Instalaciones</option><option value="clientes" {{ old('tipo')=='clientes'?'selected':'' }}>Clientes</option><option value="proyectos" {{ old('tipo')=='proyectos'?'selected':'' }}>Proyectos</option></select>@error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="formato" class="form-label">Formato *</label><select class="form-select @error('formato') is-invalid @enderror" id="formato" name="formato" required><option value="">Seleccionar</option><option value="pdf" {{ old('formato')=='pdf'?'selected':'' }}>PDF</option><option value="excel" {{ old('formato')=='excel'?'selected':'' }}>Excel</option><option value="csv" {{ old('formato')=='csv'?'selected':'' }}>CSV</option></select>@error('formato')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="fecha_inicio" class="form-label">Fecha Inicio</label><input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-01')) }}">@error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="fecha_fin" class="form-label">Fecha Fin</label><input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', date('Y-m-d')) }}">@error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mb-3"><div class="form-check"><input class="form-check-input" type="checkbox" id="incluir_graficos" name="incluir_graficos" value="1"><label class="form-check-label" for="incluir_graficos">Incluir gráficos</label></div></div>
            <div class="d-flex justify-content-end"><a href="{{ route('reportes.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-text"></i> Generar</button></div>
        </form>
    </div>
</div>
@endsection
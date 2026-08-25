@extends('layouts.app')
@section('page-title', 'Nuevo Producto')
@section('content')
<div class="card">
    <div class="card-header"><h4><i class="bi bi-plus-circle"></i> Agregar Producto</h4></div>
    <div class="card-body">
        <form action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3"><label for="modelo" class="form-label">Modelo *</label><input type="text" class="form-control @error('modelo') is-invalid @enderror" id="modelo" name="modelo" value="{{ old('modelo') }}" required>@error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="marca" class="form-label">Marca *</label><input type="text" class="form-control @error('marca') is-invalid @enderror" id="marca" name="marca" value="{{ old('marca') }}" required>@error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mb-3"><label for="descripcion" class="form-label">Descripción *</label><textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>@error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="categoria" class="form-label">Categoría *</label><select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required><option value="">Seleccionar</option>@foreach($categorias as $cat)<option value="{{ $cat->nombre_categoria }}" {{ old('categoria') == $cat->nombre_categoria ? 'selected' : '' }}>{{ $cat->nombre_categoria }}</option>@endforeach</select>@error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="existencia" class="form-label">Existencia *</label><input type="number" class="form-control @error('existencia') is-invalid @enderror" id="existencia" name="existencia" value="{{ old('existencia', 0) }}" min="0" required>@error('existencia')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="almacen" class="form-label">Almacén *</label><input type="text" class="form-control @error('almacen') is-invalid @enderror" id="almacen" name="almacen" value="{{ old('almacen') }}" required>@error('almacen')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="apea" class="form-label">APEA *</label><input type="text" class="form-control @error('apea') is-invalid @enderror" id="apea" name="apea" value="{{ old('apea') }}" required>@error('apea')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="imagen" class="form-label">Imagen</label><input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen" accept="image/*">@error('imagen')<div class="invalid-feedback">{{ $message }}</div>@enderror<small class="text-muted">JPG, PNG, GIF. Máximo 2MB</small></div>
            </div>
            <div class="mb-3"><label for="comentarios" class="form-label">Comentarios</label><input type="text" class="form-control @error('comentarios') is-invalid @enderror" id="comentarios" name="comentarios" value="{{ old('comentarios') }}">@error('comentarios')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="d-flex justify-content-end"><a href="{{ route('inventario.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button></div>
        </form>
    </div>
</div>
@endsection
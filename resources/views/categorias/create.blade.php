@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-plus-circle"></i> Nueva Categoría</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="nombre_categoria" class="form-label">Nombre de la Categoría *</label>
                <input type="text" class="form-control @error('nombre_categoria') is-invalid @enderror" 
                       id="nombre_categoria" name="nombre_categoria" value="{{ old('nombre_categoria') }}" required>
                @error('nombre_categoria')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Cliente</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rfc" class="form-label">RFC *</label>
                    <input type="text" class="form-control @error('rfc') is-invalid @enderror" 
                           id="rfc" name="rfc" value="{{ old('rfc', $cliente->rfc) }}" 
                           placeholder="Ej: XAXX010101000" maxlength="13" required>
                    @error('rfc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="razon_social" class="form-label">Razón Social *</label>
                    <input type="text" class="form-control @error('razon_social') is-invalid @enderror" 
                           id="razon_social" name="razon_social" value="{{ old('razon_social', $cliente->razon_social) }}" required>
                    @error('razon_social')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre_proyecto" class="form-label">Proyecto</label>
                    <select class="form-select @error('nombre_proyecto') is-invalid @enderror" 
                            id="nombre_proyecto" name="nombre_proyecto">
                        <option value="">Sin proyecto asignado</option>
                        @foreach($ventas as $venta)
                            <option value="{{ $venta->nombre_proyecto }}" 
                                    {{ old('nombre_proyecto', $cliente->nombre_proyecto) == $venta->nombre_proyecto ? 'selected' : '' }}>
                                {{ $venta->nombre_proyecto }}
                            </option>
                        @endforeach
                    </select>
                    @error('nombre_proyecto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="regimen_fiscal" class="form-label">Régimen Fiscal *</label>
                    <select class="form-select @error('regimen_fiscal') is-invalid @enderror" 
                            id="regimen_fiscal" name="regimen_fiscal" required>
                        <option value="">Seleccionar régimen</option>
                        <option value="Persona Física" {{ old('regimen_fiscal', $cliente->regimen_fiscal) == 'Persona Física' ? 'selected' : '' }}>Persona Física</option>
                        <option value="Persona Moral" {{ old('regimen_fiscal', $cliente->regimen_fiscal) == 'Persona Moral' ? 'selected' : '' }}>Persona Moral</option>
                        <option value="RESICO" {{ old('regimen_fiscal', $cliente->regimen_fiscal) == 'RESICO' ? 'selected' : '' }}>RESICO</option>
                    </select>
                    @error('regimen_fiscal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="codigo_postal" class="form-label">Código Postal *</label>
                    <input type="number" class="form-control @error('codigo_postal') is-invalid @enderror" 
                           id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $cliente->codigo_postal) }}" 
                           placeholder="12345" required>
                    @error('codigo_postal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-8 mb-3">
                    <label for="correo_electronico" class="form-label">Correo Electrónico *</label>
                    <input type="email" class="form-control @error('correo_electronico') is-invalid @enderror" 
                           id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico', $cliente->correo_electronico) }}" required>
                    @error('correo_electronico')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="constancia_situacion_fiscal" class="form-label">Constancia de Situación Fiscal</label>
                @if($cliente->constancia_situacion_fiscal)
                    <div class="alert alert-info">
                        <i class="bi bi-file-pdf"></i> Documento actual cargado
                    </div>
                @endif
                <input type="file" class="form-control @error('constancia_situacion_fiscal') is-invalid @enderror" 
                       id="constancia_situacion_fiscal" name="constancia_situacion_fiscal" accept=".pdf">
                @error('constancia_situacion_fiscal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Dejar vacío para mantener el documento actual</small>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
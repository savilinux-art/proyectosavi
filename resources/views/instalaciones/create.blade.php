@extends('layouts.app')
@section('page-title', 'Nueva Instalación')
@section('content')

<div class="card">
    <div class="card-header"><h4><i class="bi bi-tools"></i> Nueva Instalación</h4></div>
    <div class="card-body">
        <form action="{{ route('instalaciones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Fila 1: Proyecto + Nombre --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre_proyecto" class="form-label">Proyecto *</label>
                    <select class="form-select @error('nombre_proyecto') is-invalid @enderror"
                            id="nombre_proyecto" name="nombre_proyecto" required>
                        <option value="">Seleccionar</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->nombre_proyecto }}"
                                {{ old('nombre_proyecto') == $p->nombre_proyecto ? 'selected' : '' }}>
                                {{ $p->nombre_proyecto }}
                            </option>
                        @endforeach
                    </select>
                    @error('nombre_proyecto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nombre_instalacion" class="form-label">Nombre de la Instalación *</label>
                    <input type="text"
                           class="form-control @error('nombre_instalacion') is-invalid @enderror"
                           id="nombre_instalacion" name="nombre_instalacion"
                           value="{{ old('nombre_instalacion') }}"
                           placeholder="Ej: Redes, Iluminación, CCTV..." required>
                    @error('nombre_instalacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Fila 2: Estatus + Fecha inicio + Fecha fin --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="estatus_instalacion" class="form-label">Estatus *</label>
                    <select class="form-select @error('estatus_instalacion') is-invalid @enderror"
                            id="estatus_instalacion" name="estatus_instalacion" required>
                        @foreach($estatus as $e)
                            <option value="{{ $e->estatus }}"
                                {{ old('estatus_instalacion', 'pendiente') == $e->estatus ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$e->estatus)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('estatus_instalacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="fecha_hora_inicio" class="form-label">Fecha Inicio *</label>
                    <input type="datetime-local"
                           class="form-control @error('fecha_hora_inicio') is-invalid @enderror"
                           id="fecha_hora_inicio" name="fecha_hora_inicio"
                           value="{{ old('fecha_hora_inicio', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha_hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="fecha_hora_fin" class="form-label">Fecha Fin</label>
                    <input type="datetime-local"
                           class="form-control @error('fecha_hora_fin') is-invalid @enderror"
                           id="fecha_hora_fin" name="fecha_hora_fin"
                           value="{{ old('fecha_hora_fin') }}">
                    @error('fecha_hora_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Fila 3: Ubicación --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="latitud" class="form-label">Latitud</label>
                    <input type="number" step="0.0000001"
                           class="form-control @error('latitud') is-invalid @enderror"
                           id="latitud" name="latitud"
                           value="{{ old('latitud') }}" placeholder="20.6534000">
                    @error('latitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="longitud" class="form-label">Longitud</label>
                    <input type="number" step="0.0000001"
                           class="form-control @error('longitud') is-invalid @enderror"
                           id="longitud" name="longitud"
                           value="{{ old('longitud') }}" placeholder="-105.2253000">
                    @error('longitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text"
                           class="form-control @error('direccion') is-invalid @enderror"
                           id="direccion" name="direccion"
                           value="{{ old('direccion') }}"
                           placeholder="Calle, número, colonia...">
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Fila 4: Instaladores --}}
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="instaladores" class="form-label">Instaladores</label>
                    <select class="form-select @error('instaladores') is-invalid @enderror"
                            name="instaladores[]" id="instaladores" multiple size="5">
                        @foreach($instaladores as $inst)
                            <option value="{{ $inst->id }}"
                                {{ in_array($inst->id, old('instaladores', [])) ? 'selected' : '' }}>
                                {{ $inst->nombre }} ({{ $inst->usuario }})
                            </option>
                        @endforeach
                    </select>
                    @error('instaladores')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Ctrl / Cmd para seleccionar varios</small>
                </div>
            </div>

            {{-- Fila 5: Fotos --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="tipo_fotos" class="form-label">Tipo de evidencia</label>
                    <select name="tipo_fotos" id="tipo_fotos" class="form-select">
                        <option value="inicio">Inicio</option>
                        <option value="proceso" selected>Proceso</option>
                        <option value="fin">Fin</option>
                        <option value="incidencia">Incidencia</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="fotos" class="form-label">Fotos (puedes subir varias)</label>
                    <input type="file" name="fotos[]" id="fotos"
                           class="form-control @error('fotos') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/webp" multiple>
                    @error('fotos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('fotos.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="text-muted">Máximo 4 MB por archivo.</small>
                </div>
            </div>

            {{-- Checklist --}}
            <div class="mb-3">
                <label class="form-label">Checklist</label>
                <div class="card">
                    <div class="card-body">
                        @php
                            $items = [
                                'material_listo'         => 'Material listo',
                                'herramientas_disponibles' => 'Herramientas disponibles',
                                'seguridad_equipo'       => 'Equipo de seguridad',
                                'permisos_obtenidos'     => 'Permisos obtenidos',
                                'acceso_sitio'           => 'Acceso al sitio',
                                'documentacion_revisada' => 'Documentación revisada',
                            ];
                            $oldCheck = old('check_list', []);
                        @endphp
                        @foreach($items as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="check_list[]" value="{{ $key }}"
                                       id="{{ $key }}"
                                       {{ in_array($key, $oldCheck) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary me-2">
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
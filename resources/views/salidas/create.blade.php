@extends('layouts.app')
@section('page-title', 'Nueva Salida')
@section('content')
<div class="card">
    <div class="card-header"><h4><i class="bi bi-box-arrow-right"></i> Registrar Salida</h4></div>
    <div class="card-body">
        <form action="{{ route('salidas.store') }}" method="POST" id="salidaForm">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre_proyecto" class="form-label">Proyecto *</label>
                    <select class="form-select @error('nombre_proyecto') is-invalid @enderror" name="nombre_proyecto" id="nombre_proyecto" required>
                        <option value="">Seleccionar</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->nombre_proyecto }}" {{ old('nombre_proyecto')==$p->nombre_proyecto?'selected':'' }}>{{ $p->nombre_proyecto }}</option>
                        @endforeach
                    </select>
                    @error('nombre_proyecto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="entregado_a" class="form-label">Entregado a *</label>
                    <select class="form-select @error('entregado_a') is-invalid @enderror" name="entregado_a" id="entregado_a" required>
                        <option value="">Seleccionar</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->usuario }}" {{ old('entregado_a')==$u->usuario?'selected':'' }}>{{ $u->nombre }} ({{ $u->rol }})</option>
                        @endforeach
                    </select>
                    @error('entregado_a')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Buscador de productos -->
            <div class="mb-3">
                <label class="form-label">Buscar productos *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="buscadorProductos" placeholder="Buscar por modelo, descripción o marca...">
                    <button class="btn btn-primary" type="button" id="btnBuscarProductos">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="btnLimpiarBusqueda">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                
                <!-- Contenedor de resultados (siempre visible) -->
                <div id="resultadosBusqueda" class="list-group mt-2" style="max-height:250px; overflow-y:auto; min-height:60px;">
                    <div class="list-group-item text-muted" id="mensajePredeterminado">
                        <i class="bi bi-search"></i> Escribe al menos 2 caracteres para buscar productos
                    </div>
                </div>
                <small class="text-muted">Escribe y espera, o haz clic en Buscar. Los productos ya seleccionados no aparecerán.</small>
            </div>

            <!-- Productos seleccionados -->
            <div class="mb-3">
                <label class="form-label">Productos seleccionados</label>
                <div class="card"><div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tablaProductos">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Modelo</th>
                                    <th>Descripción</th>
                                    <th>Marca</th>
                                    <th>Stock</th>
                                    <th>Cantidad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listaProductos"></tbody>
                        </table>
                    </div>
                    <div id="sinProductos" class="text-center py-3 text-muted">
                        <i class="bi bi-inbox" style="font-size:24px;"></i>
                        <p class="mb-0">No hay productos seleccionados</p>
                    </div>
                </div></div>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control @error('observaciones') is-invalid @enderror" name="observaciones" id="observaciones" rows="2">{{ old('observaciones') }}</textarea>
                @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('salidas.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary" id="btnGuardar" disabled>
                    <i class="bi bi-save"></i> Registrar Salida
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let productosSeleccionados = [];
let timeoutBuscador = null;

// Función que realiza la búsqueda
function realizarBusqueda() {
    const q = $('#buscadorProductos').val().trim();
    const container = $('#resultadosBusqueda');
    
    // Si la búsqueda tiene menos de 2 caracteres, mostrar mensaje inicial
    if (q.length < 2) {
        container.empty().append(`
            <div class="list-group-item text-muted">
                <i class="bi bi-search"></i> Escribe al menos 2 caracteres para buscar productos
            </div>
        `);
        return;
    }

    // Mostrar mensaje de "Buscando..."
    container.empty().append(`
        <div class="list-group-item text-muted">
            <i class="bi bi-hourglass-split"></i> Buscando productos...
        </div>
    `);

    // Realizar petición AJAX
    $.ajax({
        url: "{{ route('salidas.buscarProductos') }}",
        method: 'GET',
        data: { q: q },
        dataType: 'json',
        success: function(data) {
            container.empty();
            
            // Verificar si data es un array (o tiene results)
            const productos = Array.isArray(data) ? data : (data.results || []);
            
            if (productos.length === 0) {
                container.append(`
                    <div class="list-group-item text-muted">
                        <i class="bi bi-info-circle"></i> No se encontraron productos con stock disponible
                    </div>
                `);
                return;
            }

            // Mostrar productos
            productos.forEach(function(p) {
                // No mostrar productos ya seleccionados
                if (productosSeleccionados.some(x => x.id === p.id)) return;
                
                container.append(`
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${p.modelo || 'Sin modelo'}</strong><br>
                            <small class="text-muted">${p.descripcion || 'Sin descripción'} - ${p.marca || 'Sin marca'}</small><br>
                            <span class="badge bg-info">Stock: ${p.existencia}</span>
                        </div>
                        <button class="btn btn-sm btn-primary agregar-producto"
                                data-id="${p.id}"
                                data-modelo="${p.modelo || 'N/A'}"
                                data-descripcion="${p.descripcion || 'N/A'}"
                                data-marca="${p.marca || 'N/A'}"
                                data-existencia="${p.existencia}">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </button>
                    </div>
                `);
            });
        },
        error: function(xhr) {
            console.error('Error en la búsqueda:', xhr);
            container.empty().append(`
                <div class="list-group-item text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Error al buscar productos. Intenta de nuevo.
                </div>
            `);
        }
    });
}

// Búsqueda automática al escribir (con debounce)
$('#buscadorProductos').on('input', function() {
    clearTimeout(timeoutBuscador);
    const q = $(this).val().trim();
    if (q.length < 2) {
        const container = $('#resultadosBusqueda');
        container.empty().append(`
            <div class="list-group-item text-muted">
                <i class="bi bi-search"></i> Escribe al menos 2 caracteres para buscar productos
            </div>
        `);
        return;
    }
    timeoutBuscador = setTimeout(realizarBusqueda, 300);
});

// Búsqueda manual con el botón
$('#btnBuscarProductos').on('click', function() {
    clearTimeout(timeoutBuscador);
    realizarBusqueda();
});

// Búsqueda con Enter
$('#buscadorProductos').on('keypress', function(e) {
    if (e.which === 13) {
        e.preventDefault();
        clearTimeout(timeoutBuscador);
        realizarBusqueda();
    }
});

// Agregar producto a la lista
$(document).on('click', '.agregar-producto', function() {
    const id = $(this).data('id');
    if (productosSeleccionados.some(p => p.id === id)) {
        alert('Este producto ya está en la lista.');
        return;
    }
    productosSeleccionados.push({
        id: id,
        modelo: $(this).data('modelo'),
        descripcion: $(this).data('descripcion'),
        marca: $(this).data('marca'),
        existencia: $(this).data('existencia'),
        cantidad: 1
    });
    renderizarLista();
    // Limpiar resultados y campo de búsqueda
    $('#resultadosBusqueda').empty().append(`
        <div class="list-group-item text-muted">
            <i class="bi bi-search"></i> Escribe al menos 2 caracteres para buscar productos
        </div>
    `);
    $('#buscadorProductos').val('');
});

// Renderizar lista de productos seleccionados
function renderizarLista() {
    const tbody = $('#listaProductos');
    tbody.empty();
    
    if (productosSeleccionados.length === 0) {
        $('#sinProductos').show();
        $('#btnGuardar').prop('disabled', true);
        return;
    }
    
    $('#sinProductos').hide();
    $('#btnGuardar').prop('disabled', false);
    
    productosSeleccionados.forEach((p, idx) => {
        tbody.append(`
            <tr>
                <td>${idx+1}</td>
                <td><strong>${p.modelo}</strong></td>
                <td>${p.descripcion}</td>
                <td>${p.marca}</td>
                <td><span class="badge bg-info">${p.existencia}</span></td>
                <td>
                    <input type="number" class="form-control form-control-sm cantidad-producto"
                           style="width:80px;" data-index="${idx}"
                           value="${p.cantidad}" min="1" max="${p.existencia}">
                </td>
                <td>
                    <button class="btn btn-sm btn-danger eliminar-producto" data-index="${idx}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });
    actualizarInputsOcultos();
}

// Actualizar inputs ocultos para el formulario
function actualizarInputsOcultos() {
    $('#productosHidden').remove();
    const container = $('<div id="productosHidden"></div>');
    productosSeleccionados.forEach((p, idx) => {
        container.append(`<input type="hidden" name="productos[${idx}][inventario_id]" value="${p.id}">`);
        container.append(`<input type="hidden" name="productos[${idx}][cantidad]" value="${p.cantidad}">`);
    });
    $('#salidaForm').append(container);
}

// Cambiar cantidad
$(document).on('change', '.cantidad-producto', function() {
    const idx = $(this).data('index');
    const cantidad = parseInt($(this).val()) || 0;
    if (cantidad < 1) {
        alert('La cantidad debe ser al menos 1');
        $(this).val(1);
        productosSeleccionados[idx].cantidad = 1;
    } else if (cantidad > productosSeleccionados[idx].existencia) {
        alert(`Stock disponible: ${productosSeleccionados[idx].existencia}`);
        $(this).val(productosSeleccionados[idx].existencia);
        productosSeleccionados[idx].cantidad = productosSeleccionados[idx].existencia;
    } else {
        productosSeleccionados[idx].cantidad = cantidad;
    }
    actualizarInputsOcultos();
});

// Eliminar producto
$(document).on('click', '.eliminar-producto', function() {
    const idx = $(this).data('index');
    productosSeleccionados.splice(idx, 1);
    renderizarLista();
});

// Limpiar búsqueda
$('#btnLimpiarBusqueda').on('click', function() {
    $('#buscadorProductos').val('');
    const container = $('#resultadosBusqueda');
    container.empty().append(`
        <div class="list-group-item text-muted">
            <i class="bi bi-search"></i> Escribe al menos 2 caracteres para buscar productos
        </div>
    `);
});

// Cerrar resultados al hacer clic fuera (excepto en elementos interactivos)
$(document).on('click', function(e) {
    const target = $(e.target);
    if (!target.closest('#buscadorProductos, #resultadosBusqueda, .agregar-producto, #btnBuscarProductos, #btnLimpiarBusqueda').length) {
        // No ocultamos, solo mantenemos el estado actual
    }
});

// Validar antes de enviar
$('#salidaForm').on('submit', function(e) {
    if (productosSeleccionados.length === 0) {
        e.preventDefault();
        alert('Debes agregar al menos un producto.');
        return false;
    }
    return true;
});

// Inicializar: mostrar mensaje predeterminado
$(document).ready(function() {
    $('#resultadosBusqueda').empty().append(`
        <div class="list-group-item text-muted">
            <i class="bi bi-search"></i> Escribe al menos 2 caracteres para buscar productos
        </div>
    `);
});
</script>
@endpush
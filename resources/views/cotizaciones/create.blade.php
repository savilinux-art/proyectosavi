@extends('layouts.app')

@section('page-title', 'Nueva Cotización')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header"><h4><i class="bi bi-file-earmark-plus"></i> Nueva Cotización</h4></div>
    <div class="card-body">
        <form action="{{ route('cotizaciones.store') }}" method="POST" id="cotizacionForm">
            @csrf

            <div class="mb-3">
    <label class="form-label">Proyecto *</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="proyecto_modo" id="modoExistente" value="existente"
               {{ old('proyecto_modo', 'existente') === 'existente' ? 'checked' : '' }}>
        <label class="form-check-label" for="modoExistente">Usar proyecto existente</label>
    </div>
    <div class="form-check mb-2">
        <input class="form-check-input" type="radio" name="proyecto_modo" id="modoNuevo" value="nuevo"
               {{ old('proyecto_modo') === 'nuevo' ? 'checked' : '' }}>
        <label class="form-check-label" for="modoNuevo">Crear proyecto nuevo</label>
    </div>

    <div id="bloqueExistente" style="{{ old('proyecto_modo') === 'nuevo' ? 'display:none;' : '' }}">
        <select class="form-select @error('proyecto_id') is-invalid @enderror" name="proyecto_id" id="proyecto_id">
            <option value="">Seleccionar proyecto...</option>
            @foreach($proyectos as $p)
                <option value="{{ $p->id }}" {{ old('proyecto_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nombre_proyecto }}
                </option>
            @endforeach
        </select>
        @error('proyecto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div id="bloqueNuevo" style="{{ old('proyecto_modo') === 'nuevo' ? '' : 'display:none;' }}">
        <input type="text" class="form-control @error('proyecto_nuevo') is-invalid @enderror"
               name="proyecto_nuevo" id="proyecto_nuevo" placeholder="Nombre del nuevo proyecto"
               value="{{ old('proyecto_nuevo') }}">
        @error('proyecto_nuevo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha_emision" class="form-label">Fecha de emisión *</label>
                    <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror" name="fecha_emision" id="fecha_emision" value="{{ old('fecha_emision', date('Y-m-d')) }}" required>
                    @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_validez" class="form-label">Fecha de validez</label>
                    <input type="date" class="form-control @error('fecha_validez') is-invalid @enderror" name="fecha_validez" id="fecha_validez" value="{{ old('fecha_validez', date('Y-m-d', strtotime('+30 days'))) }}">
                    @error('fecha_validez')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="moneda" class="form-label">Moneda</label>
                    <select class="form-select @error('moneda') is-invalid @enderror" name="moneda" id="moneda">
                        <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="MXN" {{ old('moneda') == 'MXN' ? 'selected' : '' }}>MXN ($)</option>
                        <option value="EUR" {{ old('moneda') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    </select>
                    @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Productos/Servicios *</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="buscadorProductos" placeholder="Buscar producto en inventario...">
                    <button class="btn btn-outline-secondary" type="button" id="btnLimpiarBusqueda"><i class="bi bi-x-circle"></i></button>
                    <button class="btn btn-success" type="button" id="btnAgregarManual">
                        <i class="bi bi-plus-circle"></i> Agregar manual
                    </button>
                </div>
                <div id="resultadosBusqueda" class="list-group mt-2" style="max-height:200px; overflow-y:auto; display:none;"></div>
                <small class="text-muted">Puedes escribir el nombre de un producto existente o agregar uno nuevo manualmente.</small>

                <div class="table-responsive mt-3">
                    <table class="table table-striped" id="tablaProductos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Importe</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="listaProductos">
                            <!-- Se agregan dinámicamente -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td id="subtotal">$0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>IVA (16%):</strong></td>
                                <td id="iva">$0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td id="total"><strong>$0.00</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mb-3">
                <label for="condiciones" class="form-label">Condiciones / Notas</label>
                <textarea class="form-control @error('condiciones') is-invalid @enderror" name="condiciones" id="condiciones" rows="4">{{ old('condiciones', "** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.") }}</textarea>
                @error('condiciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div id="productosHidden"></div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cotización</button>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
        </form>
    </div>
</div>

<!-- Modal para agregar producto manual -->
<div class="modal fade" id="modalProductoManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Agregar producto manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="manual_descripcion" class="form-label">Descripción *</label>
                    <input type="text" class="form-control" id="manual_descripcion" placeholder="Ej: Cable HDMI 2.0 5m">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="manual_cantidad" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control" id="manual_cantidad" value="1" min="1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manual_precio" class="form-label">Precio unitario *</label>
                        <input type="number" class="form-control" id="manual_precio" value="0" min="0" step="0.01">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarProductoManual"><i class="bi bi-check2"></i> Agregar producto</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let productosSeleccionados = [];
    let timeoutBuscador = null;

        // ========== TOGGLE PROYECTO EXISTENTE / NUEVO ==========
    function toggleProyectoModo() {
        const modo = $('input[name="proyecto_modo"]:checked').val() || 'existente';
        const esNuevo = modo === 'nuevo';
        $('#bloqueExistente').toggle(!esNuevo);
        $('#bloqueNuevo').toggle(esNuevo);
        $('#proyecto_id').prop('required', !esNuevo);
        $('#proyecto_nuevo').prop('required', esNuevo);
    }

    $('input[name="proyecto_modo"]').on('change', toggleProyectoModo);
    toggleProyectoModo();   // estado inicial

    // ========== BUSCADOR DE PRODUCTOS ==========
    function buscarProductos() {
        const q = $('#buscadorProductos').val().trim();
        if (q.length < 2) {
            $('#resultadosBusqueda').hide().empty();
            return;
        }
        clearTimeout(timeoutBuscador);
        timeoutBuscador = setTimeout(function() {
            $.ajax({
                url: "{{ route('cotizaciones.buscarProductos') }}",
                method: 'GET',
                data: { q: q },
                dataType: 'json',
                success: function(data) {
                    const container = $('#resultadosBusqueda');
                    container.empty().show();
                    if (data.length === 0) {
                        container.append('<div class="list-group-item text-muted">No se encontraron productos</div>');
                        container.append(`
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div><strong>Agregar manualmente</strong><br><small class="text-muted">"${q}"</small></div>
                                <button class="btn btn-sm btn-success agregar-manual" data-descripcion="${q}"><i class="bi bi-plus-circle"></i> Agregar</button>
                            </div>
                        `);
                        return;
                    }
                    data.forEach(function(p) {
                        if (productosSeleccionados.some(x => x.descripcion === p.descripcion)) return;
                        container.append(`
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div><strong>${p.modelo || 'Sin modelo'}</strong><br><small class="text-muted">${p.descripcion}</small></div>
                                <button class="btn btn-sm btn-primary agregar-producto" 
                                    data-id="${p.id}" 
                                    data-descripcion="${p.descripcion}" 
                                    data-precio="${p.precio || 0}">
                                    <i class="bi bi-plus-circle"></i> Agregar
                                </button>
                            </div>
                        `);
                    });
                },
                error: function() {
                    $('#resultadosBusqueda').empty().show().append('<div class="list-group-item text-danger">Error al buscar productos</div>');
                }
            });
        }, 300);
    }

    $('#buscadorProductos').on('input', buscarProductos);

    // ========== AGREGAR DESDE BUSCADOR ==========
    $(document).on('click', '.agregar-producto', function() {
        const id = $(this).data('id');
        const descripcion = $(this).data('descripcion');
        const precio = parseFloat($(this).data('precio')) || 0;
        agregarProducto(id, descripcion, 1, precio);
        $('#resultadosBusqueda').hide().empty();
        $('#buscadorProductos').val('');
    });

    $(document).on('click', '.agregar-manual', function() {
        const descripcion = $(this).data('descripcion');
        agregarProducto(null, descripcion, 1, 0);
        $('#resultadosBusqueda').hide().empty();
        $('#buscadorProductos').val('');
    });

    $('#btnLimpiarBusqueda').on('click', function() {
        $('#buscadorProductos').val('');
        $('#resultadosBusqueda').hide().empty();
    });

    // ========== MODAL PARA AGREGAR MANUAL ==========
    $('#btnAgregarManual').on('click', function() {
        $('#manual_descripcion').val('');
        $('#manual_cantidad').val(1);
        $('#manual_precio').val(0);
        $('#modalProductoManual').modal('show');
    });

    $('#btnGuardarProductoManual').on('click', function() {
        const descripcion = $('#manual_descripcion').val().trim();
        if (!descripcion) {
            alert('La descripción es obligatoria.');
            $('#manual_descripcion').focus();
            return;
        }
        const cantidad = parseInt($('#manual_cantidad').val()) || 1;
        if (cantidad < 1) {
            alert('La cantidad debe ser al menos 1.');
            return;
        }
        const precio = parseFloat($('#manual_precio').val()) || 0;
        if (precio < 0) {
            alert('El precio no puede ser negativo.');
            return;
        }
        agregarProducto(null, descripcion, cantidad, precio);
        $('#modalProductoManual').modal('hide');
    });

    // ========== FUNCIONES PRINCIPALES ==========
    function agregarProducto(id, descripcion, cantidad, precio) {
        if (productosSeleccionados.some(x => x.descripcion === descripcion)) {
            alert('Este producto ya está en la lista');
            return;
        }
        const importe = cantidad * precio;
        productosSeleccionados.push({ id, descripcion, cantidad, precio, importe });
        renderizarLista();
    }

    function renderizarLista() {
        const tbody = $('#listaProductos');
        tbody.empty();
        let subtotal = 0;
        productosSeleccionados.forEach((p, idx) => {
            subtotal += p.importe;
            tbody.append(`
                <tr>
                    <td>${idx+1}</td>
                    <td>${p.descripcion}</td>
                    <td><input type="number" class="form-control form-control-sm cantidad-item" data-index="${idx}" value="${p.cantidad}" min="1" style="width:80px;"></td>
                    <td><input type="number" class="form-control form-control-sm precio-item" data-index="${idx}" value="${p.precio}" min="0" step="0.01" style="width:120px;"></td>
                    <td class="importe-item">${p.importe.toFixed(2)}</td>
                    <td><button class="btn btn-sm btn-danger eliminar-item" data-index="${idx}"><i class="bi bi-trash"></i></button></td>
                </tr>
            `);
        });
        calcularTotales();
        actualizarInputsOcultos();
    }

    function calcularTotales() {
        let subtotal = 0;
        productosSeleccionados.forEach(p => subtotal += p.importe);
        const iva = subtotal * 0.16;
        const total = subtotal + iva;
        $('#subtotal').text('$' + subtotal.toFixed(2));
        $('#iva').text('$' + iva.toFixed(2));
        $('#total').text('$' + total.toFixed(2));
    }

    function actualizarInputsOcultos() {
        $('#productosHidden').empty();
        productosSeleccionados.forEach((p, idx) => {
            $('#productosHidden').append(`
                <input type="hidden" name="productos[${idx}][inventario_id]" value="${p.id || ''}">
                <input type="hidden" name="productos[${idx}][descripcion]" value="${p.descripcion}">
                <input type="hidden" name="productos[${idx}][cantidad]" value="${p.cantidad}">
                <input type="hidden" name="productos[${idx}][precio_unitario]" value="${p.precio}">
            `);
        });
    }

    // ========== EVENTOS DE EDICIÓN EN LA TABLA ==========
    $(document).on('change', '.cantidad-item', function() {
        const idx = $(this).data('index');
        const cantidad = parseInt($(this).val()) || 0;
        if (cantidad < 1) { $(this).val(1); return; }
        productosSeleccionados[idx].cantidad = cantidad;
        productosSeleccionados[idx].importe = cantidad * productosSeleccionados[idx].precio;
        $(this).closest('tr').find('.importe-item').text(productosSeleccionados[idx].importe.toFixed(2));
        calcularTotales();
        actualizarInputsOcultos();
    });

    $(document).on('change', '.precio-item', function() {
        const idx = $(this).data('index');
        const precio = parseFloat($(this).val()) || 0;
        productosSeleccionados[idx].precio = precio;
        productosSeleccionados[idx].importe = productosSeleccionados[idx].cantidad * precio;
        $(this).closest('tr').find('.importe-item').text(productosSeleccionados[idx].importe.toFixed(2));
        calcularTotales();
        actualizarInputsOcultos();
    });

    $(document).on('click', '.eliminar-item', function() {
        const idx = $(this).data('index');
        productosSeleccionados.splice(idx, 1);
        renderizarLista();
    });

    // ========== VALIDACIÓN AL ENVIAR ==========
    $('#cotizacionForm').on('submit', function(e) {
        if (productosSeleccionados.length === 0) {
            e.preventDefault();
            alert('Debes agregar al menos un producto/servicio.');
            return false;
        }
        return true;
    });
</script>
@endpush
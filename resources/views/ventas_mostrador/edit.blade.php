@extends('layouts.app')

@section('page-title', 'Editar Venta #' . $ventaMostrador->id)

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header"><h4><i class="bi bi-cart-plus"></i> Editar Venta de Mostrador</h4></div>
    <div class="card-body">
        <form action="{{ route('ventas_mostrador.update', $ventaMostrador) }}" method="POST" id="ventaForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="proyecto_id" class="form-label">Proyecto *</label>
                <select class="form-select @error('proyecto_id') is-invalid @enderror"
                        name="proyecto_id" id="proyecto_id" required>
                    <option value="">Seleccionar proyecto...</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id }}" {{ old('proyecto_id', $ventaMostrador->proyecto_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre_proyecto }}
                        </option>
                    @endforeach
                </select>
                @error('proyecto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="moneda" class="form-label">Moneda *</label>
                <select name="moneda" id="moneda" class="form-select" required>
                    <option value="MXN" {{ old('moneda', $ventaMostrador->moneda ?? 'MXN') === 'MXN' ? 'selected' : '' }}>MXN — Pesos mexicanos</option>
                    <option value="USD" {{ old('moneda', $ventaMostrador->moneda) === 'USD' ? 'selected' : '' }}>USD — Dólares americanos</option>
                </select>
            </div>

            <hr>
             <div class="mb-3">
                <label class="form-label">Buscar producto (búsqueda rápida)</label>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="buscadorInventario"
                           placeholder="Mínimo 2 caracteres (modelo, descripción, marca, categoría)..."
                           autocomplete="off">
                    <button type="button" class="btn btn-outline-secondary" id="btnLimpiarBusquedaInventario">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div id="resultadosBusquedaInventario" class="list-group mb-3"
                     style="max-height:300px; overflow-y:auto; display:none;"></div>
            </div>

            

            <div class="table-responsive mt-3">
                <table class="table table-striped" id="tablaItems">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Modelo</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Descuento</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="listaItems"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                            <td id="subtotalVenta"><strong>MXN 0.00</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>IVA (16%):</strong></td>
                            <td id="ivaVenta"><strong>MXN 0.00</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Total:</strong></td>
                            <td id="totalVenta"><strong>MXN 0.00</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Condiciones</label>
                <textarea class="form-control" name="observaciones" id="observaciones" rows="3">{{ old('observaciones', $ventaMostrador->observaciones) }}</textarea>
            </div>

            @if(!empty($ventaMostrador->estadosPermitidos()))
            <div class="mb-3">
                <label for="estado" class="form-label">Cambiar estado</label>
                <select class="form-select" name="estado" id="estado">
                    <option value="{{ $ventaMostrador->estado }}">{{ ucfirst($ventaMostrador->estado) }} (actual)</option>
                    @foreach($ventaMostrador->estadosPermitidos() as $est)
                        <option value="{{ $est }}">{{ ucfirst($est) }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Solo se permite avanzar de pendiente → completada/cancelada.</small>
            </div>
            @endif

            <div id="itemsHidden"></div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
            <a href="{{ route('ventas_mostrador.show', $ventaMostrador) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @php
        $itemsArray = $ventaMostrador->detalles->map(fn($d) => [
            'inventario_id'   => $d->inventario_id,
            'modelo'          => $d->inventario?->modelo ?? '',
            'descripcion'     => $d->inventario?->descripcion ?? '',
            'cantidad'        => $d->cantidad,
            'precio_unitario' => (float) $d->precio_unitario,
            'descuento'       => (float) $d->descuento,
        ])->values()->all();
    @endphp
    let items = {!! json_encode($itemsArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

    $('#btnAgregar').on('click', function() {
        const $opt = $('#selectorInventario option:selected');
        const id = $opt.val();
        if (!id) { alert('Selecciona un producto.'); return; }

        if (items.some(x => x.inventario_id == id)) {
            alert('Ese producto ya está en la lista.');
            return;
        }

        items.push({
            inventario_id: id,
            modelo: $opt.data('modelo'),
            descripcion: $opt.data('descripcion'),
            cantidad: 1,
            precio_unitario: parseFloat($opt.data('precio')) || 0,
            descuento: 0,
        });
        $('#selectorInventario').val('');
        renderItems();
    });

    function renderItems() {
        const $tb = $('#listaItems').empty();
        let subtotal = 0;
        const moneda = $('select[name="moneda"]').val() || 'MXN';

        items.forEach((it, idx) => {
            const lineSubtotal = it.cantidad * (it.precio_unitario - it.descuento);
            subtotal += lineSubtotal;
            $tb.append(`
                <tr>
                    <td>${idx+1}</td>
                    <td>${it.modelo}</td>
                    <td>${it.descripcion}</td>
                    <td><input type="number" class="form-control form-control-sm edit-cant" data-idx="${idx}" value="${it.cantidad}" min="1" style="width:80px;"></td>
                    <td><input type="number" class="form-control form-control-sm edit-precio" data-idx="${idx}" value="${it.precio_unitario}" min="0" step="0.01" style="width:110px;"></td>
                    <td><input type="number" class="form-control form-control-sm edit-desc" data-idx="${idx}" value="${it.descuento}" min="0" step="0.01" style="width:100px;"></td>
                    <td class="subtotal-cell">${moneda} ${lineSubtotal.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-sm btn-danger eliminar" data-idx="${idx}"><i class="bi bi-trash"></i></button></td>
                </tr>
            `);
        });

        const iva   = subtotal * 0.16;
        const total = subtotal + iva;

        $('#subtotalVenta').html('<strong>' + moneda + ' ' + subtotal.toFixed(2) + '</strong>');
        $('#ivaVenta').html('<strong>' + moneda + ' ' + iva.toFixed(2) + '</strong>');
        $('#totalVenta').html('<strong>' + moneda + ' ' + total.toFixed(2) + '</strong>');
        syncHidden();
    }

    function syncHidden() {
        $('#itemsHidden').empty();
        items.forEach((it, idx) => {
            $('#itemsHidden').append(`
                <input type="hidden" name="items[${idx}][inventario_id]" value="${it.inventario_id}">
                <input type="hidden" name="items[${idx}][cantidad]" value="${it.cantidad}">
                <input type="hidden" name="items[${idx}][precio_unitario]" value="${it.precio_unitario}">
                <input type="hidden" name="items[${idx}][descuento]" value="${it.descuento}">
            `);
        });
    }

    $(document).on('change', 'select[name="moneda"]', function () {
        renderItems();
    });

    $(document).on('change', '.edit-cant', function() {
        const idx = $(this).data('idx');
        const v = parseInt($(this).val()) || 1;
        items[idx].cantidad = Math.max(1, v);
        renderItems();
    });
    $(document).on('change', '.edit-precio', function() {
        const idx = $(this).data('idx');
        items[idx].precio_unitario = parseFloat($(this).val()) || 0;
        renderItems();
    });
    $(document).on('change', '.edit-desc', function() {
        const idx = $(this).data('idx');
        items[idx].descuento = parseFloat($(this).val()) || 0;
        renderItems();
    });
    $(document).on('click', '.eliminar', function() {
        const idx = $(this).data('idx');
        items.splice(idx, 1);
        renderItems();
    });

    renderItems();

    $('#ventaForm').on('submit', function(e) {
        if (items.length === 0) {
            e.preventDefault();
            alert('Debes agregar al menos un producto.');
            return false;
        }
        return true;
    });

  // ========== BUSCADOR DE INVENTARIO (Etapa 2) ==========
    let timeoutBuscadorInv = null;

    $('#buscadorInventario').on('input', function () {
        const q = $(this).val().trim();
        const $cont = $('#resultadosBusquedaInventario');
        if (q.length < 2) { $cont.hide().empty(); return; }

        clearTimeout(timeoutBuscadorInv);
        timeoutBuscadorInv = setTimeout(function () {
            $.ajax({
                url: "{{ route('ventas_mostrador.buscarInventario') }}",
                method: 'GET',
                data: { q: q },
                dataType: 'json',
                success: function (data) {
                    $cont.empty().show();
                    if (!data.length) {
                        $cont.append('<div class="list-group-item text-muted">Sin resultados</div>');
                        return;
                    }
                    data.forEach(function (p) {
                        if (items.some(x => x.inventario_id == p.id)) return;

                        const $btn = $('<button type="button">')
                            .addClass('list-group-item list-group-item-action d-flex justify-content-between align-items-center agregar-desde-busqueda-inv')
                            .data('id', p.id)
                            .data('modelo', p.modelo || '')
                            .data('descripcion', p.descripcion || '')
                            .data('precio', p.precio || 0);

                        const $left = $('<div class="text-start">')
                            .append($('<strong>').text(p.modelo || 'Sin modelo'))
                            .append('<br>')
                            .append($('<small class="text-muted">').text(p.descripcion || ''));

                        $btn.append($left)
                            .append($('<span class="badge bg-primary">').html('<i class="bi bi-plus-circle"></i> Agregar'));

                        $cont.append($btn);
                    });
                },
                error: function () {
                    $cont.empty().show()
                         .append('<div class="list-group-item text-danger">Error al buscar</div>');
                }
            });
        }, 300);
    });

    $('#btnLimpiarBusquedaInventario').on('click', function () {
        $('#buscadorInventario').val('');
        $('#resultadosBusquedaInventario').hide().empty();
    });

    $(document).on('click', '.agregar-desde-busqueda-inv', function () {
        const id = $(this).data('id');
        if (items.some(x => x.inventario_id == id)) {
            alert('Ese producto ya está en la lista.');
            return;
        }
        items.push({
            inventario_id:   id,
            modelo:          $(this).data('modelo'),
            descripcion:     $(this).data('descripcion'),
            cantidad:        1,
            precio_unitario: parseFloat($(this).data('precio')) || 0,
            descuento:       0,
        });
        renderItems();
        $('#buscadorInventario').val('');
        $('#resultadosBusquedaInventario').hide().empty();
    });

</script>
@endpush
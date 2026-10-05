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

            <hr>

            <div class="mb-3">
                <label class="form-label">Agregar producto del inventario *</label>
                <div class="row g-2">
                    <div class="col-md-9">
                        <select class="form-select" id="selectorInventario">
                            <option value="">Seleccionar producto...</option>
                            @foreach($inventario as $inv)
                                <option value="{{ $inv->id }}"
                                        data-modelo="{{ $inv->modelo }}"
                                        data-descripcion="{{ $inv->descripcion }}"
                                        data-precio="{{ $inv->precio }}">
                                    {{ $inv->modelo }} — {{ $inv->descripcion }} (Exist: {{ $inv->existencia }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100" id="btnAgregar">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </button>
                    </div>
                </div>
                <small class="text-muted">Solo se pueden agregar productos del inventario.</small>
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
                            <td colspan="6" class="text-end"><strong>Total:</strong></td>
                            <td id="totalVenta"><strong>$0.00</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
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
        'inventario_id'    => $d->inventario_id,
        'modelo'           => $d->inventario?->modelo ?? '',
        'descripcion'      => $d->inventario?->descripcion ?? '',
        'cantidad'         => $d->cantidad,
        'precio_unitario'  => (float) $d->precio_unitario,
        'descuento'        => (float) $d->descuento,
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
        let total = 0;
        items.forEach((it, idx) => {
            const subtotal = it.cantidad * (it.precio_unitario - it.descuento);
            total += subtotal;
            $tb.append(`
                <tr>
                    <td>${idx+1}</td>
                    <td>${it.modelo}</td>
                    <td>${it.descripcion}</td>
                    <td><input type="number" class="form-control form-control-sm edit-cant" data-idx="${idx}" value="${it.cantidad}" min="1" style="width:80px;"></td>
                    <td><input type="number" class="form-control form-control-sm edit-precio" data-idx="${idx}" value="${it.precio_unitario}" min="0" step="0.01" style="width:110px;"></td>
                    <td><input type="number" class="form-control form-control-sm edit-desc" data-idx="${idx}" value="${it.descuento}" min="0" step="0.01" style="width:100px;"></td>
                    <td class="subtotal-cell">$${subtotal.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-sm btn-danger eliminar" data-idx="${idx}"><i class="bi bi-trash"></i></button></td>
                </tr>
            `);
        });
        $('#totalVenta').html('<strong>$' + total.toFixed(2) + '</strong>');
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

    // Render inicial con los ítems precargados
    renderItems();

    $('#ventaForm').on('submit', function(e) {
        if (items.length === 0) {
            e.preventDefault();
            alert('Debes agregar al menos un producto.');
            return false;
        }
        return true;
    });
</script>
@endpush
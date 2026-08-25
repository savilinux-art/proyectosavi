@extends('layouts.app')

@section('page-title', 'Inventario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box"></i> Inventario</h1>
    <div>
        <a href="{{ route('inventario.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </a>
        <a href="{{ route('inventario.export') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Exportar
        </a>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="categoryFilter" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach(\App\Models\Categoria::all() as $categoria)
                        <option value="{{ $categoria->nombre_categoria }}">{{ $categoria->nombre_categoria }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="stockFilter" class="form-select">
                    <option value="">Todos los stocks</option>
                    <option value="low">Stock bajo (< 10)</option>
                    <option value="out">Sin stock (0)</option>
                    <option value="normal">Stock normal (≥ 10)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Resumen rápido -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total Productos</h6>
                <h3>{{ $inventario->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Existencia Total</h6>
                <h3>{{ $inventario->sum('existencia') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Stock Bajo (< 10)</h6>
                <h3>{{ $inventario->where('existencia', '<', 10)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h6 class="card-title">Sin Stock</h6>
                <h3>{{ $inventario->where('existencia', 0)->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="inventarioTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Modelo</th>
                        <th>Descripción</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Existencia</th>
                        <th>Almacén</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventario as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            @if($item->imagen)
                                <img src="data:image/jpeg;base64,{{ base64_encode($item->imagen) }}" 
                                     width="50" height="50" class="img-thumbnail" alt="Imagen" 
                                     style="object-fit: cover;">
                            @else
                                <i class="bi bi-image" style="font-size: 24px; color: #ccc;"></i>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item->modelo }}</strong>
                            <br>
                            <small class="text-muted">ID: {{ $item->id }}</small>
                        </td>
                        <td>{{ Str::limit($item->descripcion, 40) }}</td>
                        <td>{{ $item->marca }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $item->categoriaRelacion->nombre_categoria ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @if($item->existencia == 0)
                                <span class="badge bg-danger">0</span>
                            @elseif($item->existencia < 10)
                                <span class="badge bg-warning">{{ $item->existencia }}</span>
                            @else
                                <span class="badge bg-success">{{ $item->existencia }}</span>
                            @endif
                        </td>
                        <td>{{ $item->almacen }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('inventario.show', $item->id) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('inventario.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('inventario.destroy', $item->id) }}" method="POST" 
                                      onsubmit="return confirm('¿Eliminar este producto?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#inventarioTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });

        // Filtros personalizados
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#categoryFilter').on('change', function() {
            table.column(5).search(this.value).draw();
        });

        $('#stockFilter').on('change', function() {
            var value = this.value;
            if (value === 'low') {
                table.column(6).search('< 10', true).draw();
            } else if (value === 'out') {
                table.column(6).search('0', true).draw();
            } else if (value === 'normal') {
                table.column(6).search('>= 10', true).draw();
            } else {
                table.column(6).search('').draw();
            }
        });

        window.resetFilters = function() {
            $('#searchInput').val('');
            $('#categoryFilter').val('');
            $('#stockFilter').val('');
            table.search('').columns().search('').draw();
        };
    });
</script>
@endpush
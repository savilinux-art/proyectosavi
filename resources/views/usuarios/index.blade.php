@extends('layouts.app')

@section('page-title', 'Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> Usuarios del Sistema</h1>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
    </a>
</div>

<!-- Resumen -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total Usuarios</h6>
                <h3>{{ $usuarios->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Administradores</h6>
                <h3>{{ $usuarios->where('rol', 'Administrador')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title">Ventas</h6>
                <h3>{{ $usuarios->where('rol', 'Ventas')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Instaladores</h6>
                <h3>{{ $usuarios->where('rol', 'Instalador')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="usuariosTable">
               <thead>
                <tr>
                     <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Telegram Chat ID</th>   <!-- Nueva columna -->
                     <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>
                            <strong>{{ $usuario->usuario }}</strong>
                            @if($usuario->usuario == 'admin')
                                <span class="badge bg-danger">Principal</span>
                            @endif
                        </td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>
                            @php
                                $rolColors = [
                                    'Administrador' => 'danger',
                                    'Ventas' => 'success',
                                    'Instalador' => 'primary',
                                    'Inventarios' => 'warning',
                                    'Contabilidad' => 'info',
                                    'Sistemas' => 'secondary'
                                ];
                                $color = $rolColors[$usuario->rol] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">
                                {{ $usuario->rol }}
                            </span>
                        </td>
                        <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('usuarios.show', $usuario->usuario) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('usuarios.edit', $usuario->usuario) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($usuario->usuario != 'admin')
                                    <form action="{{ route('usuarios.destroy', $usuario->usuario) }}" method="POST" 
                                          onsubmit="return confirm('¿Eliminar este usuario?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
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
        $('#usuariosTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'asc']]
        });
    });
</script>
@endpush

 @foreach($usuarios as $usuario)
    <tr>
        <td>{{ $usuario->id }}</td>
        <td>{{ $usuario->usuario }}</td>
        <td>{{ $usuario->nombre }}</td>
        <td>{{ $usuario->correo }}</td>
        <td>{{ $usuario->rol }}</td>
        <td>{{ $usuario->telegram_chat_id ?? 'No registrado' }}</td>
        <td>
            <!-- Botones de acción -->
        </td>
    </tr>
    @endforeach

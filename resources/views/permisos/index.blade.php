@extends('layouts.app')
@section('page-title', 'Permisos')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-list-check"></i> Permisos</h1>
    <div><a href="{{ route('permisos.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Permiso</a><a href="{{ route('roles.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver Roles</a></div>
</div>
<div class="card"><div class="card-body">
    @foreach($permisos as $modulo => $permisosModulo)
    <div class="card mb-3"><div class="card-header bg-light"><h6><i class="bi bi-folder"></i> {{ ucfirst($modulo) }} <span class="badge bg-secondary">{{ $permisosModulo->count() }}</span></h6></div><div class="card-body"><table class="table table-striped"><thead><tr><th>Nombre</th><th>Slug</th><th>Descripción</th><th>Acciones</th></tr></thead><tbody>@foreach($permisosModulo as $p)<tr><td><strong>{{ $p->nombre }}</strong></td><td><code>{{ $p->slug }}</code></td><td>{{ $p->descripcion }}</td><td><div class="btn-group"><a href="{{ route('permisos.edit', $p->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a><form action="{{ route('permisos.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este permiso?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form></div></td></tr>@endforeach</tbody></table></div></div>
    @endforeach
</div></div>
@endsection
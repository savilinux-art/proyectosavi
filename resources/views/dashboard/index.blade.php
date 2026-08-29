@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard <small class="text-muted fs-6">Bienvenido, {{ session('user_nombre') }}</small></h1>
    </div>
</div>

<!-- ==================== NOTIFICACIONES ==================== -->
@if(isset($notificaciones) && count($notificaciones) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark"><h5><i class="bi bi-bell-fill"></i> Notificaciones</h5></div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($notificaciones as $notif)
                        <li class="list-group-item d-flex align-items-center">{{ $notif }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ==================== ACCESO RÁPIDO ==================== -->
@php
    $userRol = session('user_rol');
    $user = \App\Models\Usuario::find(session('user_usuario'));
    $hasPermiso = function($slug) use ($user) {
        return $user && $user->hasPermiso($slug);
    };
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white"><h5><i class="bi bi-rocket-takeoff"></i> Acceso Rápido</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    @if($userRol == 'Administrador')
                        <div class="col-md-2"><a href="{{ route('inventario.create') }}" class="text-decoration-none"><div class="card h-100 border-primary hover-shadow"><div class="card-body text-center"><i class="bi bi-box" style="font-size:36px;color:#0d6efd;"></i><h6 class="mt-2">Nuevo Producto</h6></div></div></a></div>
                        <div class="col-md-2"><a href="{{ route('ventas.create') }}" class="text-decoration-none"><div class="card h-100 border-success hover-shadow"><div class="card-body text-center"><i class="bi bi-cart-plus" style="font-size:36px;color:#198754;"></i><h6 class="mt-2">Nueva Venta</h6></div></div></a></div>
                        <div class="col-md-2"><a href="{{ route('clientes.create') }}" class="text-decoration-none"><div class="card h-100 border-info hover-shadow"><div class="card-body text-center"><i class="bi bi-person-plus" style="font-size:36px;color:#0dcaf0;"></i><h6 class="mt-2">Nuevo Cliente</h6></div></div></a></div>
                        <div class="col-md-2"><a href="{{ route('proyectos.create') }}" class="text-decoration-none"><div class="card h-100 border-warning hover-shadow"><div class="card-body text-center"><i class="bi bi-folder-plus" style="font-size:36px;color:#ffc107;"></i><h6 class="mt-2">Nuevo Proyecto</h6></div></div></a></div>
                        <div class="col-md-2"><a href="{{ route('instalaciones.create') }}" class="text-decoration-none"><div class="card h-100 border-danger hover-shadow"><div class="card-body text-center"><i class="bi bi-tools" style="font-size:36px;color:#dc3545;"></i><h6 class="mt-2">Nueva Instalación</h6></div></div></a></div>
                        <div class="col-md-2"><a href="{{ route('asignaciones.index') }}" class="text-decoration-none"><div class="card h-100 border-dark hover-shadow"><div class="card-body text-center"><i class="bi bi-person-plus" style="font-size:36px;color:#212529;"></i><h6 class="mt-2">Asignaciones</h6></div></div></a></div>
                    @elseif($userRol == 'Inventarios')
                        @if($hasPermiso('crear-producto'))
                        <div class="col-md-3"><a href="{{ route('inventario.create') }}" class="text-decoration-none"><div class="card h-100 border-primary hover-shadow"><div class="card-body text-center"><i class="bi bi-box" style="font-size:36px;color:#0d6efd;"></i><h6 class="mt-2">Nuevo Producto</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('crear-salida'))
                        <div class="col-md-3"><a href="{{ route('salidas.create') }}" class="text-decoration-none"><div class="card h-100 border-success hover-shadow"><div class="card-body text-center"><i class="bi bi-box-arrow-right" style="font-size:36px;color:#198754;"></i><h6 class="mt-2">Nueva Salida</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('crear-devolucion'))
                        <div class="col-md-3"><a href="{{ route('devoluciones.create') }}" class="text-decoration-none"><div class="card h-100 border-info hover-shadow"><div class="card-body text-center"><i class="bi bi-arrow-return-left" style="font-size:36px;color:#0dcaf0;"></i><h6 class="mt-2">Nueva Devolución</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('ver-proyectos'))
                        <div class="col-md-3"><a href="{{ route('proyectos.index') }}" class="text-decoration-none"><div class="card h-100 border-warning hover-shadow"><div class="card-body text-center"><i class="bi bi-folder" style="font-size:36px;color:#ffc107;"></i><h6 class="mt-2">Ver Proyectos</h6></div></div></a></div>
                        @endif
                    @elseif($userRol == 'Instalador')
                        <div class="col-md-3"><a href="{{ route('instalaciones.index') }}" class="text-decoration-none"><div class="card h-100 border-primary hover-shadow"><div class="card-body text-center"><i class="bi bi-tools" style="font-size:36px;color:#0d6efd;"></i><h6 class="mt-2">Mis Instalaciones</h6></div></div></a></div>
                        <div class="col-md-3"><a href="{{ route('proyectos.index') }}" class="text-decoration-none"><div class="card h-100 border-success hover-shadow"><div class="card-body text-center"><i class="bi bi-folder" style="font-size:36px;color:#198754;"></i><h6 class="mt-2">Proyectos</h6></div></div></a></div>
                        @if($hasPermiso('crear-instalacion'))
                        <div class="col-md-3"><a href="{{ route('instalaciones.create') }}" class="text-decoration-none"><div class="card h-100 border-warning hover-shadow"><div class="card-body text-center"><i class="bi bi-plus-circle" style="font-size:36px;color:#ffc107;"></i><h6 class="mt-2">Nueva Instalación</h6></div></div></a></div>
                        @endif
                    @elseif($userRol == 'Contabilidad')
                        @if($hasPermiso('crear-cliente'))
                        <div class="col-md-3"><a href="{{ route('clientes.create') }}" class="text-decoration-none"><div class="card h-100 border-primary hover-shadow"><div class="card-body text-center"><i class="bi bi-person-plus" style="font-size:36px;color:#0d6efd;"></i><h6 class="mt-2">Nuevo Cliente</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('ver-clientes'))
                        <div class="col-md-3"><a href="{{ route('clientes.index') }}" class="text-decoration-none"><div class="card h-100 border-success hover-shadow"><div class="card-body text-center"><i class="bi bi-people" style="font-size:36px;color:#198754;"></i><h6 class="mt-2">Ver Clientes</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('ver-proyectos'))
                        <div class="col-md-3"><a href="{{ route('proyectos.index') }}" class="text-decoration-none"><div class="card h-100 border-info hover-shadow"><div class="card-body text-center"><i class="bi bi-folder" style="font-size:36px;color:#0dcaf0;"></i><h6 class="mt-2">Ver Proyectos</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('ver-instalaciones'))
                        <div class="col-md-3"><a href="{{ route('instalaciones.index') }}" class="text-decoration-none"><div class="card h-100 border-warning hover-shadow"><div class="card-body text-center"><i class="bi bi-tools" style="font-size:36px;color:#ffc107;"></i><h6 class="mt-2">Ver Instalaciones</h6></div></div></a></div>
                        @endif
                    @elseif($userRol == 'Sistemas')
                        <div class="col-md-3"><a href="{{ route('proyectos.index') }}" class="text-decoration-none"><div class="card h-100 border-primary hover-shadow"><div class="card-body text-center"><i class="bi bi-folder" style="font-size:36px;color:#0d6efd;"></i><h6 class="mt-2">Ver Proyectos</h6></div></div></a></div>
                        <div class="col-md-3"><a href="{{ route('instalaciones.index') }}" class="text-decoration-none"><div class="card h-100 border-success hover-shadow"><div class="card-body text-center"><i class="bi bi-tools" style="font-size:36px;color:#198754;"></i><h6 class="mt-2">Ver Instalaciones</h6></div></div></a></div>
                        @if($hasPermiso('crear-proyecto'))
                        <div class="col-md-3"><a href="{{ route('proyectos.create') }}" class="text-decoration-none"><div class="card h-100 border-warning hover-shadow"><div class="card-body text-center"><i class="bi bi-folder-plus" style="font-size:36px;color:#ffc107;"></i><h6 class="mt-2">Nuevo Proyecto</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('crear-instalacion'))
                        <div class="col-md-3"><a href="{{ route('instalaciones.create') }}" class="text-decoration-none"><div class="card h-100 border-danger hover-shadow"><div class="card-body text-center"><i class="bi bi-plus-circle" style="font-size:36px;color:#dc3545;"></i><h6 class="mt-2">Nueva Instalación</h6></div></div></a></div>
                        @endif
                    @elseif($userRol == 'Ventas')
                        @if($hasPermiso('crear-venta'))
                        <div class="col-md-3"><a href="{{ route('ventas.create') }}" class="text-decoration-none"><div class="card h-100 border-primary hover-shadow"><div class="card-body text-center"><i class="bi bi-cart-plus" style="font-size:36px;color:#0d6efd;"></i><h6 class="mt-2">Nueva Venta</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('ver-ventas'))
                        <div class="col-md-3"><a href="{{ route('ventas.index') }}" class="text-decoration-none"><div class="card h-100 border-success hover-shadow"><div class="card-body text-center"><i class="bi bi-cart" style="font-size:36px;color:#198754;"></i><h6 class="mt-2">Ver Ventas</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('ver-instalaciones'))
                        <div class="col-md-3"><a href="{{ route('instalaciones.index') }}" class="text-decoration-none"><div class="card h-100 border-info hover-shadow"><div class="card-body text-center"><i class="bi bi-tools" style="font-size:36px;color:#0dcaf0;"></i><h6 class="mt-2">Instalaciones</h6></div></div></a></div>
                        @endif
                        @if($hasPermiso('ver-clientes'))
                        <div class="col-md-3"><a href="{{ route('clientes.index') }}" class="text-decoration-none"><div class="card h-100 border-warning hover-shadow"><div class="card-body text-center"><i class="bi bi-people" style="font-size:36px;color:#ffc107;"></i><h6 class="mt-2">Clientes</h6></div></div></a></div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== ESTADÍSTICAS Y CONTENIDO POR ROL ==================== -->

<!-- ====== INVENTARIOS ====== -->
@if($userRol == 'Inventarios')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-boxes"></i> Inventario Total</h6>
                <h2>{{ $total_inventario ?? 0 }}</h2>
                <a href="{{ route('inventario.index') }}" class="text-white text-decoration-none"><small>Ver inventario <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-cart"></i> Ventas Registradas</h6>
                <h2>{{ $total_ventas ?? 0 }}</h2>
                <a href="{{ route('ventas.index') }}" class="text-white text-decoration-none"><small>Ver ventas <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-folder"></i> Proyectos</h6>
                <h2>{{ $total_proyectos ?? 0 }}</h2>
                <a href="{{ route('proyectos.index') }}" class="text-white text-decoration-none"><small>Ver proyectos <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
</div>

@if(isset($inventario_bajo) && $inventario_bajo->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white"><h5><i class="bi bi-exclamation-triangle"></i> Productos con Stock Bajo</h5></div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead><tr><th>Modelo</th><th>Descripción</th><th>Existencia</th><th>Marca</th></tr></thead>
                    <tbody>
                        @foreach($inventario_bajo as $item)
                        <tr><td>{{ $item->modelo }}</td><td>{{ $item->descripcion }}</td><td class="text-danger"><strong>{{ $item->existencia }}</strong></td><td>{{ $item->marca }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endif

<!-- ====== INSTALADORES ====== -->
@if($userRol == 'Instalador')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white"><h5><i class="bi bi-tools"></i> Mis Instalaciones</h5></div>
            <div class="card-body">
                @if(isset($instalaciones) && $instalaciones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Proyecto</th><th>Estatus</th><th>Acciones</th></tr></thead>
                            <tbody>
                                @foreach($instalaciones as $inst)
                                <tr>
                                    <td>{{ $inst->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                                    <td><span class="badge bg-{{ $inst->estatus_instalacion == 'entrega' ? 'success' : 'warning' }}">{{ ucfirst($inst->estatus_instalacion) }}</span></td>
                                    <td><a href="{{ route('instalaciones.edit', $inst->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Editar</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No tienes instalaciones asignadas.</p>
                @endif
                <a href="{{ route('instalaciones.index') }}" class="btn btn-outline-primary btn-sm">Ver todas</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white"><h5><i class="bi bi-folder"></i> Mis Proyectos</h5></div>
            <div class="card-body">
                @if(isset($proyectos) && $proyectos->count() > 0)
                    <ul class="list-group">
                        @foreach($proyectos as $proy)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $proy->nombre_proyecto }}
                                <a href="{{ route('proyectos.show', $proy->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No tienes proyectos asociados.</p>
                @endif
                <a href="{{ route('proyectos.index') }}" class="btn btn-outline-success btn-sm mt-2">Ver todos</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ====== CONTABILIDAD ====== -->
@if($userRol == 'Contabilidad')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-people"></i> Clientes</h6>
                <h2>{{ $total_clientes ?? 0 }}</h2>
                <a href="{{ route('clientes.index') }}" class="text-white text-decoration-none"><small>Ver clientes <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-folder"></i> Proyectos</h6>
                <h2>{{ $total_proyectos ?? 0 }}</h2>
                <a href="{{ route('proyectos.index') }}" class="text-white text-decoration-none"><small>Ver proyectos <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-tools"></i> Instalaciones</h6>
                <h2>{{ $total_instalaciones ?? 0 }}</h2>
                <a href="{{ route('instalaciones.index') }}" class="text-white text-decoration-none"><small>Ver instalaciones <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ====== SISTEMAS ====== -->
@if($userRol == 'Sistemas')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white"><h5><i class="bi bi-folder"></i> Proyectos</h5></div>
            <div class="card-body">
                @if(isset($proyectos) && $proyectos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Proyecto</th><th>Venta asociada</th><th>Acciones</th></tr></thead>
                            <tbody>
                                @foreach($proyectos as $proy)
                                <tr>
                                    <td>{{ $proy->nombre_proyecto }}</td>
                                    <td>{{ $proy->venta->titulo_venta ?? 'N/A' }}</td>
                                    <td><a href="{{ route('proyectos.edit', $proy->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay proyectos registrados.</p>
                @endif
                <a href="{{ route('proyectos.index') }}" class="btn btn-outline-primary btn-sm">Ver todos</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white"><h5><i class="bi bi-tools"></i> Instalaciones</h5></div>
            <div class="card-body">
                @if(isset($instalaciones) && $instalaciones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Proyecto</th><th>Instaladores</th><th>Estatus</th><th>Acciones</th></tr></thead>
                            <tbody>
                                @foreach($instalaciones as $inst)
                                <tr>
                                    <td>{{ $inst->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                                    <td>@foreach($inst->instaladores as $ins)<span class="badge bg-primary">{{ $ins->nombre }}</span> @endforeach</td>
                                    <td><span class="badge bg-{{ $inst->estatus_instalacion == 'entrega' ? 'success' : 'warning' }}">{{ ucfirst($inst->estatus_instalacion) }}</span></td>
                                    <td><a href="{{ route('instalaciones.edit', $inst->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay instalaciones registradas.</p>
                @endif
                <a href="{{ route('instalaciones.index') }}" class="btn btn-outline-success btn-sm">Ver todas</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ====== ADMINISTRADOR Y VENTAS ====== -->
@if(in_array($userRol, ['Administrador', 'Ventas']))
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-cart"></i> Total Ventas</h6>
                <h2>{{ $total_ventas ?? 0 }}</h2>
                <a href="{{ route('ventas.index') }}" class="text-white text-decoration-none"><small>Ver todas <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-boxes"></i> Total Inventario</h6>
                <h2>{{ $total_inventario ?? 0 }}</h2>
                <a href="{{ route('inventario.index') }}" class="text-white text-decoration-none"><small>Ver todos <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-tools"></i> Instalaciones Pendientes</h6>
                <h2>{{ $instalaciones_pendientes ?? 0 }}</h2>
                <a href="{{ route('instalaciones.index') }}" class="text-white text-decoration-none"><small>Ver todas <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info stat-card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-people"></i> Clientes</h6>
                <h2>{{ $total_clientes ?? 0 }}</h2>
                <a href="{{ route('clientes.index') }}" class="text-white text-decoration-none"><small>Ver todos <i class="bi bi-arrow-right"></i></small></a>
            </div>
        </div>
    </div>
</div>

@if(isset($ventas_por_estatus) && $ventas_por_estatus->count() > 0)
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-bar-chart"></i> Ventas por Estatus</h5></div>
            <div class="card-body"><canvas id="ventasChart" height="300"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-list"></i> Detalle</h5></div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($ventas_por_estatus as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($item->estatus) }}
                            <span class="badge bg-primary rounded-pill">{{ $item->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif

<!-- ====== INSTALACIONES ACTIVAS (COMÚN PARA TODOS) ====== -->
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="bi bi-tools"></i> Instalaciones Activas</h5>
    </div>
    <div class="card-body">
        @if($instalacionesActivas->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Proyecto</th>
                            <th>Estatus</th>
                            <th>Instaladores</th>
                            <th>Fecha Inicio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instalacionesActivas as $instalacion)
                        <tr>
                            <td>{{ $instalacion->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                            <td><span class="badge bg-primary">{{ ucfirst($instalacion->estatus_instalacion) }}</span></td>
                            <td>
                                @foreach($instalacion->instaladores as $inst)
                                    <span class="badge bg-secondary me-1">{{ $inst->nombre }}</span>
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($instalacion->fecha_hora_inicio)->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No hay instalaciones activas.</p>
        @endif
    </div>
</div>

<!-- ====== UBICACIONES RECIENTES (COMÚN PARA TODOS) ====== -->
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="bi bi-geo-alt"></i> Ubicaciones Recientes (últimas 24h)</h5>
    </div>
    <div class="card-body">
        @if($ubicacionesRecientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Instalador</th>
                            <th>Instalación</th>
                            <th>Tipo</th>
                            <th>Fecha/Hora</th>
                            <th>Ubicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ubicacionesRecientes as $ubicacion)
                        <tr>
                            <td>{{ $ubicacion->usuario->nombre ?? 'N/A' }}</td>
                            <td>{{ $ubicacion->instalacion->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $ubicacion->tipo == 'inicio' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($ubicacion->tipo) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($ubicacion->fecha_hora)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $ubicacion->latitud }},{{ $ubicacion->longitud }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-geo-alt"></i> Ver mapa
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No hay ubicaciones recientes.</p>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(isset($ventas_por_estatus) && $ventas_por_estatus->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ventasChart');
        if (ctx) {
            const labels = {!! json_encode($ventas_por_estatus->pluck('estatus')->map(function($item){ return ucfirst($item); })) !!};
            const data = {!! json_encode($ventas_por_estatus->pluck('total')) !!};
            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventas',
                        data: data,
                        backgroundColor: ['rgba(54,162,235,0.8)', 'rgba(255,206,86,0.8)', 'rgba(255,159,64,0.8)', 'rgba(75,192,192,0.8)'],
                        borderColor: ['#36a2eb', '#ffce56', '#ff9f40', '#4bc0c0'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }
    });
</script>
@endif
@endpush
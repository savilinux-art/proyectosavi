<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'ProyectoSAVI v0.1.1')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    @stack('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: #16213e;
            --sidebar-active: #0f3460;
            --sidebar-text: #a8a8b3;
            --sidebar-text-hover: #ffffff;
            --sidebar-border: #2d2d44;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-width);
            background: var(--sidebar-bg); color: var(--sidebar-text);
            transition: all 0.3s ease; z-index: 1050;
            overflow-y: auto; overflow-x: hidden;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-track { background: var(--sidebar-bg); }
        .sidebar::-webkit-scrollbar-thumb { background: var(--sidebar-active); border-radius: 10px; }

        .sidebar-brand {
            padding: 20px 25px; border-bottom: 1px solid var(--sidebar-border);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar-brand h3 { color: white; font-weight: 700; margin: 0; font-size: 20px; }
        .sidebar-brand small { color: rgba(255,255,255,0.7); font-size: 12px; }

        .sidebar-user {
            padding: 20px 25px; border-bottom: 1px solid var(--sidebar-border);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-user .avatar {
            width: 45px; height: 45px; border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: bold; font-size: 18px; flex-shrink: 0;
        }
        .sidebar-user .user-info .name { color: white; font-weight: 600; font-size: 14px; }
        .sidebar-user .user-info .role { color: var(--sidebar-text); font-size: 12px; }
        .sidebar-user .user-info .badge-role { font-size: 10px; padding: 2px 8px; border-radius: 20px; }

        .sidebar-menu { padding: 15px 0; }
        .sidebar-menu .menu-label {
            padding: 10px 25px; font-size: 11px; text-transform: uppercase;
            color: var(--sidebar-text); opacity: 0.5; letter-spacing: 1px; font-weight: 600;
        }
        .sidebar-menu .nav-link {
            display: flex; align-items: center; padding: 12px 25px;
            color: var(--sidebar-text); text-decoration: none; transition: all 0.3s ease;
            border-left: 3px solid transparent; gap: 12px; font-size: 14px;
        }
        .sidebar-menu .nav-link:hover { background: var(--sidebar-hover); color: var(--sidebar-text-hover); border-left-color: #667eea; }
        .sidebar-menu .nav-link.active { background: var(--sidebar-active); color: white; border-left-color: #667eea; }
        .sidebar-menu .nav-link i { font-size: 18px; width: 24px; text-align: center; flex-shrink: 0; }
        .sidebar-menu .nav-link .badge { margin-left: auto; font-size: 11px; padding: 3px 8px; border-radius: 20px; }

        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: all 0.3s ease; }

        .top-navbar {
            background: white; padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 1040;
        }
        .top-navbar .page-title { font-size: 20px; font-weight: 600; color: #2d3748; margin: 0; }
        .top-navbar .page-title i { color: #667eea; margin-right: 10px; }

        .content-wrapper { padding: 30px; }

        .sidebar-toggle { display: none; background: none; border: none; color: #4a5568; font-size: 24px; padding: 5px 10px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1045; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .top-navbar .page-title { font-size: 16px; }
        }
        @media (max-width: 576px) {
            .top-navbar { padding: 10px 15px; }
            .content-wrapper { padding: 15px; }
        }
        .stat-card { transition: all 0.3s ease; border: none; border-radius: 15px; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .hover-shadow { transition: all 0.3s ease; }
        .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .card.hover-shadow { cursor: pointer; }
        .fade-in { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h3><i class="bi bi-boxes"></i> ProyectoSAVI</h3>
            <small>v0.1.1 - Sistema de Gestión</small>
        </div>

        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(session('user_nombre', 'U'), 0, 2)) }}</div>
            <div class="user-info">
                <div class="name">{{ session('user_nombre', 'Usuario') }}</div>
                <div class="role">
                    <span class="badge badge-role bg-{{ session('user_rol') == 'Administrador' ? 'danger' : (session('user_rol') == 'Ventas' ? 'success' : (session('user_rol') == 'Instalador' ? 'primary' : 'info')) }}">
                        {{ session('user_rol', 'Usuario') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">

            <!-- ==================== DASHBOARD ==================== -->
            <div class="menu-label">Navegación</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- ==================== INVENTARIO ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Ventas', 'Inventarios']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('ver-inventario')))
            <div class="menu-label mt-3">Inventario</div>
            <a href="{{ route('inventario.index') }}" class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}">
                <i class="bi bi-box"></i> Inventario
                <span class="badge bg-primary ms-auto">{{ \App\Models\Inventario::count() }}</span>
            </a>
            @if(in_array(session('user_rol'), ['Administrador', 'Inventarios']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('crear-producto')))
            <a href="{{ route('inventario.create') }}" class="nav-link {{ request()->routeIs('inventario.create') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
            @endif
            <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-tags"></i> Categorías
            </a>
            @endif

            <!-- ==================== SALIDAS ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Inventarios']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('ver-salidas')))
            <a href="{{ route('salidas.index') }}" class="nav-link {{ request()->routeIs('salidas.*') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-box-arrow-right"></i> Salidas
                <span class="badge bg-warning ms-auto">{{ \App\Models\SalidaInventario::count() }}</span>
            </a>
            @if(in_array(session('user_rol'), ['Administrador', 'Inventarios']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('crear-salida')))
            <a href="{{ route('salidas.create') }}" class="nav-link {{ request()->routeIs('salidas.create') ? 'active' : '' }}" style="padding-left: 70px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nueva Salida
            </a>
            @endif
            @endif

            <!-- ==================== DEVOLUCIONES ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Inventarios']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('ver-devoluciones')))
            <a href="{{ route('devoluciones.index') }}" class="nav-link {{ request()->routeIs('devoluciones.*') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-arrow-return-left"></i> Devoluciones
                <span class="badge bg-success ms-auto">{{ \App\Models\DevolucionInventario::count() }}</span>
            </a>
            @if(in_array(session('user_rol'), ['Administrador', 'Inventarios']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('crear-devolucion')))
            <a href="{{ route('devoluciones.create') }}" class="nav-link {{ request()->routeIs('devoluciones.create') ? 'active' : '' }}" style="padding-left: 70px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nueva Devolución
            </a>
            @endif
            @endif

            <!-- ==================== VENTAS ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Ventas']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('ver-ventas')))
            <div class="menu-label mt-3">Ventas</div>
            <a href="{{ route('ventas.index') }}" class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                <i class="bi bi-cart"></i> Ventas
                <span class="badge bg-success ms-auto">{{ \App\Models\Venta::count() }}</span>
            </a>
            @if(in_array(session('user_rol'), ['Administrador', 'Ventas']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('crear-venta')))
            <a href="{{ route('ventas.create') }}" class="nav-link {{ request()->routeIs('ventas.create') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nueva Venta
            </a>
            @endif
            @endif

            <!-- ==================== INSTALACIONES ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Instalador', 'Ventas']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('ver-instalaciones')))
            <div class="menu-label mt-3">Instalaciones</div>
            <a href="{{ route('instalaciones.index') }}" class="nav-link {{ request()->routeIs('instalaciones.*') ? 'active' : '' }}">
                <i class="bi bi-tools"></i> Instalaciones
                <span class="badge bg-warning ms-auto">{{ \App\Models\Instalacion::where('estatus_instalacion', '!=', 'entrega')->count() }}</span>
            </a>
            @if(in_array(session('user_rol'), ['Administrador', 'Ventas']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('crear-instalacion')))
            <a href="{{ route('instalaciones.create') }}" class="nav-link {{ request()->routeIs('instalaciones.create') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nueva Instalación
            </a>
            @endif
            @endif

            <!-- ==================== CLIENTES ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Contabilidad', 'Ventas']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('ver-clientes')))
            <div class="menu-label mt-3">Clientes</div>
            <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Clientes
                <span class="badge bg-info ms-auto">{{ \App\Models\Cliente::count() }}</span>
            </a>
            @if(in_array(session('user_rol'), ['Administrador', 'Contabilidad']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('crear-cliente')))
            <a href="{{ route('clientes.create') }}" class="nav-link {{ request()->routeIs('clientes.create') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nuevo Cliente
            </a>
            @endif
            @endif

            <!-- ==================== PROYECTOS ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Contabilidad', 'Sistemas', 'Ventas', 'Instalador', 'Inventarios']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('ver-proyectos')))
            <div class="menu-label mt-3">Proyectos</div>
            <a href="{{ route('proyectos.index') }}" class="nav-link {{ request()->routeIs('proyectos.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i> Proyectos
                <span class="badge bg-secondary ms-auto">{{ \App\Models\Proyecto::count() }}</span>
            </a>
            @if(in_array(session('user_rol'), ['Administrador', 'Contabilidad', 'Sistemas']) || (session('user_usuario') && \App\Models\Usuario::find(session('user_usuario'))?->hasPermiso('crear-proyecto')))
            <a href="{{ route('proyectos.create') }}" class="nav-link {{ request()->routeIs('proyectos.create') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nuevo Proyecto
            </a>
            @endif
            @endif

            <!-- ==================== ASIGNACIONES ==================== -->
            @if(session('user_rol') == 'Administrador')
            <div class="menu-label mt-3">Administración</div>
            <a href="{{ route('asignaciones.index') }}" class="nav-link {{ request()->routeIs('asignaciones.*') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i> Asignaciones
                <span class="badge bg-danger ms-auto">{{ \App\Models\Instalacion::whereDoesntHave('instaladores')->count() }}</span>
            </a>
            <a href="{{ route('asignaciones.create') }}" class="nav-link {{ request()->routeIs('asignaciones.create') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nueva Asignación
            </a>
            @endif

            <!-- ==================== USUARIOS ==================== -->
            @if(session('user_rol') == 'Administrador')
            <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Usuarios
                <span class="badge bg-primary ms-auto">{{ \App\Models\Usuario::count() }}</span>
            </a>
            <a href="{{ route('usuarios.create') }}" class="nav-link {{ request()->routeIs('usuarios.create') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-plus-circle"></i> Nuevo Usuario
            </a>
            @endif

            <!-- ==================== ROLES Y PERMISOS ==================== -->
            @if(session('user_rol') == 'Administrador')
            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Roles y Permisos
                <span class="badge bg-info ms-auto">{{ \App\Models\Rol::count() }}</span>
            </a>
            <a href="{{ route('permisos.index') }}" class="nav-link {{ request()->routeIs('permisos.*') ? 'active' : '' }}" style="padding-left: 55px; font-size: 13px;">
                <i class="bi bi-list-check"></i> Gestionar Permisos
            </a>
            @endif

            <!-- ==================== REPORTES ==================== -->
            @if(in_array(session('user_rol'), ['Administrador', 'Ventas', 'Contabilidad']))
            <div class="menu-label mt-3">Reportes</div>
            <a href="{{ route('reportes.index') }}" class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Reportes
            </a>
            @endif

            <!-- ==================== NOTIFICACIONES ==================== -->
            <div class="menu-label mt-3">Sistema</div>
            <a href="{{ route('notificaciones.index') }}" class="nav-link {{ request()->routeIs('notificaciones.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notificaciones
                <span class="badge bg-danger ms-auto" id="sidebarNotificacionesCount">{{ \App\Models\Notificacion::where('usuario_id', session('user_usuario'))->where('leida', false)->count() }}</span>
            </a>

            <!-- ==================== CERRAR SESIÓN ==================== -->
            <a href="{{ route('logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">@csrf</form>

        </div>
    </nav>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content">
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <h4 class="page-title">
                    <i class="bi bi-{{ request()->routeIs('dashboard') ? 'speedometer2' : (request()->routeIs('inventario.*') ? 'box' : (request()->routeIs('ventas.*') ? 'cart' : (request()->routeIs('instalaciones.*') ? 'tools' : (request()->routeIs('clientes.*') ? 'people' : (request()->routeIs('proyectos.*') ? 'folder' : (request()->routeIs('usuarios.*') ? 'people' : (request()->routeIs('roles.*') ? 'shield-lock' : (request()->routeIs('salidas.*') ? 'box-arrow-right' : (request()->routeIs('devoluciones.*') ? 'arrow-return-left' : (request()->routeIs('asignaciones.*') ? 'person-plus' : (request()->routeIs('notificaciones.*') ? 'bell' : 'gear'))))))))))) }}">
                    @yield('page-title', 'Dashboard')
                </h4>
            </div>
            <div class="navbar-actions">
                <a href="{{ route('notificaciones.index') }}" class="btn btn-light position-relative me-2">
                    <i class="bi bi-bell"></i>
                    @php $count = \App\Models\Notificacion::where('usuario_id', session('user_usuario'))->where('leida', false)->count(); @endphp
                    @if($count > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $count }}</span>
                    @endif
                </a>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ session('user_nombre') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text"><strong>{{ session('user_nombre') }}</strong><br><small class="text-muted">{{ session('user_rol') }}</small></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        });
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
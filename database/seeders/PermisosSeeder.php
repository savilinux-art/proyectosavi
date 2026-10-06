<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;
use App\Models\Rol;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        if (!\Schema::hasTable('permisos')) {
            $this->command->error('La tabla permisos no existe. Ejecuta las migraciones primero.');
            return;
        }

        $permisos = [
            // ═══════════════════════════════════════════════════════
            // DASHBOARD
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Dashboard', 'slug' => 'ver-dashboard', 'descripcion' => 'Acceder al dashboard principal', 'modulo' => 'dashboard'],

            // ═══════════════════════════════════════════════════════
            // INVENTARIO
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Inventario',      'slug' => 'ver-inventario',      'descripcion' => 'Ver listado de inventario',      'modulo' => 'inventario'],
            ['nombre' => 'Crear Producto',      'slug' => 'crear-producto',      'descripcion' => 'Agregar nuevos productos',      'modulo' => 'inventario'],
            ['nombre' => 'Editar Producto',     'slug' => 'editar-producto',     'descripcion' => 'Editar productos existentes',   'modulo' => 'inventario'],
            ['nombre' => 'Eliminar Producto',   'slug' => 'eliminar-producto',   'descripcion' => 'Eliminar productos',           'modulo' => 'inventario'],
            ['nombre' => 'Exportar Inventario', 'slug' => 'exportar-inventario', 'descripcion' => 'Exportar inventario a archivo', 'modulo' => 'inventario'],

            // ═══════════════════════════════════════════════════════
            // SALIDAS / DEVOLUCIONES
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Salidas de Inventario',      'slug' => 'ver-salidas',      'descripcion' => 'Ver listado de salidas de inventario',      'modulo' => 'inventario'],
            ['nombre' => 'Crear Salida de Inventario',     'slug' => 'crear-salida',     'descripcion' => 'Registrar nuevas salidas de inventario',    'modulo' => 'inventario'],
            ['nombre' => 'Ver Devoluciones de Inventario', 'slug' => 'ver-devoluciones', 'descripcion' => 'Ver listado de devoluciones de inventario','modulo' => 'inventario'],
            ['nombre' => 'Crear Devolución de Inventario', 'slug' => 'crear-devolucion', 'descripcion' => 'Registrar nuevas devoluciones',             'modulo' => 'inventario'],

            // ═══════════════════════════════════════════════════════
            // VENTAS
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Ventas',       'slug' => 'ver-ventas',       'descripcion' => 'Ver listado de ventas',       'modulo' => 'ventas'],
            ['nombre' => 'Crear Venta',      'slug' => 'crear-venta',      'descripcion' => 'Registrar nuevas ventas',     'modulo' => 'ventas'],
            ['nombre' => 'Editar Venta',     'slug' => 'editar-venta',     'descripcion' => 'Editar ventas existentes',    'modulo' => 'ventas'],
            ['nombre' => 'Eliminar Venta',   'slug' => 'eliminar-venta',   'descripcion' => 'Eliminar ventas',             'modulo' => 'ventas'],
            ['nombre' => 'Exportar Ventas',  'slug' => 'exportar-ventas',  'descripcion' => 'Exportar ventas a archivo',    'modulo' => 'ventas'],
            ['nombre' => 'Ventas Mostrador', 'slug' => 'ventas-mostrador', 'descripcion' => 'Acceder al módulo de ventas de mostrador', 'modulo' => 'ventas'],

            // ═══════════════════════════════════════════════════════
            // INSTALACIONES
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Instalaciones',    'slug' => 'ver-instalaciones',    'descripcion' => 'Ver listado de instalaciones',    'modulo' => 'instalaciones'],
            ['nombre' => 'Crear Instalación',    'slug' => 'crear-instalacion',    'descripcion' => 'Registrar nuevas instalaciones',  'modulo' => 'instalaciones'],
            ['nombre' => 'Editar Instalación',   'slug' => 'editar-instalacion',   'descripcion' => 'Editar instalaciones existentes', 'modulo' => 'instalaciones'],
            ['nombre' => 'Eliminar Instalación', 'slug' => 'eliminar-instalacion', 'descripcion' => 'Eliminar instalaciones',         'modulo' => 'instalaciones'],

            // ═══════════════════════════════════════════════════════
            // CLIENTES
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Clientes',     'slug' => 'ver-clientes',     'descripcion' => 'Ver listado de clientes',     'modulo' => 'clientes'],
            ['nombre' => 'Crear Cliente',    'slug' => 'crear-cliente',    'descripcion' => 'Registrar nuevos clientes',   'modulo' => 'clientes'],
            ['nombre' => 'Editar Cliente',   'slug' => 'editar-cliente',   'descripcion' => 'Editar clientes existentes',  'modulo' => 'clientes'],
            ['nombre' => 'Eliminar Cliente', 'slug' => 'eliminar-cliente', 'descripcion' => 'Eliminar clientes',           'modulo' => 'clientes'],

            // ═══════════════════════════════════════════════════════
            // PROYECTOS
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Proyectos',     'slug' => 'ver-proyectos',     'descripcion' => 'Ver listado de proyectos',     'modulo' => 'proyectos'],
            ['nombre' => 'Crear Proyecto',    'slug' => 'crear-proyecto',    'descripcion' => 'Registrar nuevos proyectos',   'modulo' => 'proyectos'],
            ['nombre' => 'Editar Proyecto',   'slug' => 'editar-proyecto',   'descripcion' => 'Editar proyectos existentes',  'modulo' => 'proyectos'],
            ['nombre' => 'Eliminar Proyecto', 'slug' => 'eliminar-proyecto', 'descripcion' => 'Eliminar proyectos',          'modulo' => 'proyectos'],

            // ═══════════════════════════════════════════════════════
            // ASIGNACIONES
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Asignaciones',    'slug' => 'ver-asignaciones',    'descripcion' => 'Ver listado de asignaciones',    'modulo' => 'asignaciones'],
            ['nombre' => 'Crear Asignación',    'slug' => 'crear-asignacion',    'descripcion' => 'Crear nuevas asignaciones',      'modulo' => 'asignaciones'],
            ['nombre' => 'Editar Asignación',   'slug' => 'editar-asignacion',   'descripcion' => 'Editar asignaciones existentes', 'modulo' => 'asignaciones'],
            ['nombre' => 'Eliminar Asignación', 'slug' => 'eliminar-asignacion', 'descripcion' => 'Eliminar asignaciones',         'modulo' => 'asignaciones'],

            // ═══════════════════════════════════════════════════════
            // REPORTES
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Reportes',     'slug' => 'ver-reportes',     'descripcion' => 'Ver módulo de reportes',  'modulo' => 'reportes'],
            ['nombre' => 'Generar Reportes', 'slug' => 'generar-reportes', 'descripcion' => 'Generar nuevos reportes', 'modulo' => 'reportes'],

            // ═══════════════════════════════════════════════════════
            // ROLES Y PERMISOS
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Roles',        'slug' => 'ver-roles',        'descripcion' => 'Ver listado de roles',       'modulo' => 'roles'],
            ['nombre' => 'Crear Rol',        'slug' => 'crear-rol',        'descripcion' => 'Crear nuevos roles',         'modulo' => 'roles'],
            ['nombre' => 'Editar Rol',       'slug' => 'editar-rol',       'descripcion' => 'Editar roles existentes',    'modulo' => 'roles'],
            ['nombre' => 'Eliminar Rol',     'slug' => 'eliminar-rol',     'descripcion' => 'Eliminar roles',             'modulo' => 'roles'],
            ['nombre' => 'Ver Permisos',     'slug' => 'ver-permisos',     'descripcion' => 'Ver listado de permisos',    'modulo' => 'roles'],
            ['nombre' => 'Crear Permiso',    'slug' => 'crear-permiso',    'descripcion' => 'Crear nuevos permisos',      'modulo' => 'roles'],
            ['nombre' => 'Editar Permiso',   'slug' => 'editar-permiso',   'descripcion' => 'Editar permisos existentes', 'modulo' => 'roles'],
            ['nombre' => 'Eliminar Permiso', 'slug' => 'eliminar-permiso', 'descripcion' => 'Eliminar permisos',          'modulo' => 'roles'],

            // ═══════════════════════════════════════════════════════
            // NUEVOS — 6 OCT 2026
            // ═══════════════════════════════════════════════════════
            ['nombre' => 'Ver Ubicaciones', 'slug' => 'ver-ubicaciones', 'descripcion' => 'Ver mapa y ubicaciones GPS', 'modulo' => 'ubicaciones'],

            ['nombre' => 'Ver Geocercas',     'slug' => 'ver-geocercas',     'descripcion' => 'Ver geocercas y alertas',       'modulo' => 'geocercas'],
            ['nombre' => 'Crear Geocerca',    'slug' => 'crear-geocerca',    'descripcion' => 'Crear nuevas geocercas',       'modulo' => 'geocercas'],
            ['nombre' => 'Editar Geocerca',   'slug' => 'editar-geocerca',   'descripcion' => 'Editar geocercas existentes',  'modulo' => 'geocercas'],
            ['nombre' => 'Eliminar Geocerca', 'slug' => 'eliminar-geocerca', 'descripcion' => 'Eliminar geocercas',          'modulo' => 'geocercas'],

            ['nombre' => 'Ver Recordatorios',     'slug' => 'ver-recordatorios',     'descripcion' => 'Ver listado de recordatorios',    'modulo' => 'recordatorios'],
            ['nombre' => 'Crear Recordatorio',    'slug' => 'crear-recordatorio',    'descripcion' => 'Crear nuevos recordatorios',      'modulo' => 'recordatorios'],
            ['nombre' => 'Editar Recordatorio',   'slug' => 'editar-recordatorio',   'descripcion' => 'Editar recordatorios existentes', 'modulo' => 'recordatorios'],
            ['nombre' => 'Eliminar Recordatorio', 'slug' => 'eliminar-recordatorio', 'descripcion' => 'Eliminar recordatorios',         'modulo' => 'recordatorios'],

            ['nombre' => 'Ver Certificados',   'slug' => 'ver-certificados',   'descripcion' => 'Ver certificados',               'modulo' => 'certificados'],
            ['nombre' => 'Crear Certificado',  'slug' => 'crear-certificado',  'descripcion' => 'Registrar nuevos certificados',  'modulo' => 'certificados'],
            ['nombre' => 'Editar Certificado', 'slug' => 'editar-certificado', 'descripcion' => 'Editar certificados existentes', 'modulo' => 'certificados'],

            ['nombre' => 'Ver Usuarios',     'slug' => 'ver-usuarios',     'descripcion' => 'Ver listado de usuarios',    'modulo' => 'usuarios'],
            ['nombre' => 'Crear Usuario',    'slug' => 'crear-usuario',    'descripcion' => 'Crear nuevos usuarios',      'modulo' => 'usuarios'],
            ['nombre' => 'Editar Usuario',   'slug' => 'editar-usuario',   'descripcion' => 'Editar usuarios existentes', 'modulo' => 'usuarios'],
            ['nombre' => 'Eliminar Usuario', 'slug' => 'eliminar-usuario', 'descripcion' => 'Eliminar usuarios',          'modulo' => 'usuarios'],

            ['nombre' => 'Ver Notificaciones', 'slug' => 'ver-notificaciones', 'descripcion' => 'Ver notificaciones', 'modulo' => 'notificaciones'],
        ];

        $creados = 0;
        $actualizados = 0;

        foreach ($permisos as $data) {
            $existing = Permiso::where('slug', $data['slug'])->first();

            if (!$existing) {
                Permiso::create($data);
                $creados++;
            } else {
                $existing->update([
                    'nombre'      => $data['nombre'],
                    'descripcion' => $data['descripcion'],
                    'modulo'      => $data['modulo'],
                ]);
                $actualizados++;
            }
        }

        $admin = Rol::where('rol', 'Administrador')->first();
        if ($admin) {
            $admin->permisos()->sync(Permiso::pluck('id')->all());
            $this->command->info('OK Permisos sincronizados con Administrador');
        }

        $this->command->info("Permisos procesados: " . count($permisos) . " (nuevos: {$creados}, actualizados: {$actualizados})");
    }
}

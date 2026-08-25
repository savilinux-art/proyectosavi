<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;
use App\Models\Rol;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar que la tabla permisos existe
        if (!\Schema::hasTable('permisos')) {
            $this->command->error('La tabla permisos no existe. Ejecuta las migraciones primero.');
            return;
        }

        // Definir permisos
        $permisos = [
            // Dashboard
            ['nombre' => 'Ver Dashboard', 'slug' => 'ver-dashboard', 'descripcion' => 'Acceder al dashboard principal', 'modulo' => 'dashboard'],
            
            // Inventario
            ['nombre' => 'Ver Inventario', 'slug' => 'ver-inventario', 'descripcion' => 'Ver listado de inventario', 'modulo' => 'inventario'],
            ['nombre' => 'Crear Producto', 'slug' => 'crear-producto', 'descripcion' => 'Agregar nuevos productos', 'modulo' => 'inventario'],
            ['nombre' => 'Editar Producto', 'slug' => 'editar-producto', 'descripcion' => 'Editar productos existentes', 'modulo' => 'inventario'],
            ['nombre' => 'Eliminar Producto', 'slug' => 'eliminar-producto', 'descripcion' => 'Eliminar productos', 'modulo' => 'inventario'],
            ['nombre' => 'Exportar Inventario', 'slug' => 'exportar-inventario', 'descripcion' => 'Exportar inventario a archivo', 'modulo' => 'inventario'],
            
            // Ventas
            ['nombre' => 'Ver Ventas', 'slug' => 'ver-ventas', 'descripcion' => 'Ver listado de ventas', 'modulo' => 'ventas'],
            ['nombre' => 'Crear Venta', 'slug' => 'crear-venta', 'descripcion' => 'Registrar nuevas ventas', 'modulo' => 'ventas'],
            ['nombre' => 'Editar Venta', 'slug' => 'editar-venta', 'descripcion' => 'Editar ventas existentes', 'modulo' => 'ventas'],
            ['nombre' => 'Eliminar Venta', 'slug' => 'eliminar-venta', 'descripcion' => 'Eliminar ventas', 'modulo' => 'ventas'],
            ['nombre' => 'Exportar Ventas', 'slug' => 'exportar-ventas', 'descripcion' => 'Exportar ventas a archivo', 'modulo' => 'ventas'],
            
            // Instalaciones
            ['nombre' => 'Ver Instalaciones', 'slug' => 'ver-instalaciones', 'descripcion' => 'Ver listado de instalaciones', 'modulo' => 'instalaciones'],
            ['nombre' => 'Crear Instalación', 'slug' => 'crear-instalacion', 'descripcion' => 'Registrar nuevas instalaciones', 'modulo' => 'instalaciones'],
            ['nombre' => 'Editar Instalación', 'slug' => 'editar-instalacion', 'descripcion' => 'Editar instalaciones existentes', 'modulo' => 'instalaciones'],
            ['nombre' => 'Eliminar Instalación', 'slug' => 'eliminar-instalacion', 'descripcion' => 'Eliminar instalaciones', 'modulo' => 'instalaciones'],
            
            // Clientes
            ['nombre' => 'Ver Clientes', 'slug' => 'ver-clientes', 'descripcion' => 'Ver listado de clientes', 'modulo' => 'clientes'],
            ['nombre' => 'Crear Cliente', 'slug' => 'crear-cliente', 'descripcion' => 'Registrar nuevos clientes', 'modulo' => 'clientes'],
            ['nombre' => 'Editar Cliente', 'slug' => 'editar-cliente', 'descripcion' => 'Editar clientes existentes', 'modulo' => 'clientes'],
            ['nombre' => 'Eliminar Cliente', 'slug' => 'eliminar-cliente', 'descripcion' => 'Eliminar clientes', 'modulo' => 'clientes'],
            
            // Proyectos
            ['nombre' => 'Ver Proyectos', 'slug' => 'ver-proyectos', 'descripcion' => 'Ver listado de proyectos', 'modulo' => 'proyectos'],
            ['nombre' => 'Crear Proyecto', 'slug' => 'crear-proyecto', 'descripcion' => 'Registrar nuevos proyectos', 'modulo' => 'proyectos'],
            ['nombre' => 'Editar Proyecto', 'slug' => 'editar-proyecto', 'descripcion' => 'Editar proyectos existentes', 'modulo' => 'proyectos'],
            ['nombre' => 'Eliminar Proyecto', 'slug' => 'eliminar-proyecto', 'descripcion' => 'Eliminar proyectos', 'modulo' => 'proyectos'],
            
            // Asignaciones
            ['nombre' => 'Ver Asignaciones', 'slug' => 'ver-asignaciones', 'descripcion' => 'Ver listado de asignaciones', 'modulo' => 'asignaciones'],
            ['nombre' => 'Crear Asignación', 'slug' => 'crear-asignacion', 'descripcion' => 'Crear nuevas asignaciones', 'modulo' => 'asignaciones'],
            ['nombre' => 'Editar Asignación', 'slug' => 'editar-asignacion', 'descripcion' => 'Editar asignaciones existentes', 'modulo' => 'asignaciones'],
            ['nombre' => 'Eliminar Asignación', 'slug' => 'eliminar-asignacion', 'descripcion' => 'Eliminar asignaciones', 'modulo' => 'asignaciones'],
            
            // Reportes
            ['nombre' => 'Ver Reportes', 'slug' => 'ver-reportes', 'descripcion' => 'Ver módulo de reportes', 'modulo' => 'reportes'],
            ['nombre' => 'Generar Reportes', 'slug' => 'generar-reportes', 'descripcion' => 'Generar nuevos reportes', 'modulo' => 'reportes'],

            // Dentro del array $permisos, agregar:
            ['nombre' => 'Ver Salidas de Inventario',
            'slug' => 'ver-salidas',
            'descripcion' => 'Permite ver el listado de salidas de inventario',
            'modulo' => 'inventario' ],
            ['nombre' => 'Crear Salida de Inventario',
             'slug' => 'crear-salida',
             'descripcion' => 'Permite registrar nuevas salidas de inventario',
             'modulo' => 'inventario'],
[
    'nombre' => 'Ver Devoluciones de Inventario',
    'slug' => 'ver-devoluciones',
    'descripcion' => 'Permite ver el listado de devoluciones de inventario',
    'modulo' => 'inventario'],
[
    'nombre' => 'Crear Devolución de Inventario',
    'slug' => 'crear-devolucion',
    'descripcion' => 'Permite registrar nuevas devoluciones de inventario',
    'modulo' => 'inventario'],
            
            // Roles y Permisos
            ['nombre' => 'Ver Roles', 'slug' => 'ver-roles', 'descripcion' => 'Ver listado de roles', 'modulo' => 'roles'],
            ['nombre' => 'Crear Rol', 'slug' => 'crear-rol', 'descripcion' => 'Crear nuevos roles', 'modulo' => 'roles'],
            ['nombre' => 'Editar Rol', 'slug' => 'editar-rol', 'descripcion' => 'Editar roles existentes', 'modulo' => 'roles'],
            ['nombre' => 'Eliminar Rol', 'slug' => 'eliminar-rol', 'descripcion' => 'Eliminar roles', 'modulo' => 'roles'],
            ['nombre' => 'Ver Permisos', 'slug' => 'ver-permisos', 'descripcion' => 'Ver listado de permisos', 'modulo' => 'roles'],
            ['nombre' => 'Crear Permiso', 'slug' => 'crear-permiso', 'descripcion' => 'Crear nuevos permisos', 'modulo' => 'roles'],
            ['nombre' => 'Editar Permiso', 'slug' => 'editar-permiso', 'descripcion' => 'Editar permisos existentes', 'modulo' => 'roles'],
            ['nombre' => 'Eliminar Permiso', 'slug' => 'eliminar-permiso', 'descripcion' => 'Eliminar permisos', 'modulo' => 'roles'],
        ];

        // Insertar o actualizar permisos usando firstOrCreate
        $count = 0;
        foreach ($permisos as $permiso) {
            $existing = Permiso::where('slug', $permiso['slug'])->first();
            if (!$existing) {
                Permiso::create($permiso);
                $count++;
            } else {
                // Actualizar si es necesario
                $existing->update($permiso);
            }
        }

        // Asignar todos los permisos al Administrador
        $admin = Rol::where('rol', 'Administrador')->first();
        if ($admin) {
            // Obtener todos los IDs de permisos
            $permisoIds = Permiso::all()->pluck('id');
            
            // Sincronizar permisos (esto elimina los que no están y agrega los nuevos)
            $admin->permisos()->sync($permisoIds);
            $this->command->info('Permisos sincronizados con el Administrador');
        }
        
        $this->command->info('Permisos procesados: ' . count($permisos) . ' (nuevos: ' . $count . ')');
    }
}
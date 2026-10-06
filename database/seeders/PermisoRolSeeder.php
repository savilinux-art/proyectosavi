<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permiso;
use App\Models\Rol;

class PermisoRolSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════════
        // Matriz de AGREGADOS (nunca quita permisos existentes).
        // null = todos los permisos (para Administrador).
        // ═══════════════════════════════════════════════════════════
        $matriz = [
            'Administrador' => null,

            'Contabilidad' => [
                'ver-reportes',
                'ver-certificados',
                'ver-recordatorios',
                'ver-notificaciones',
            ],

            'Instalador' => [
                'crear-instalacion',
                'ver-ubicaciones',
                'ver-geocercas',
                'ver-salidas',
                'ver-recordatorios',
                'ver-certificados',
                'ver-notificaciones',
            ],

            'Inventarios' => [
                'ver-reportes',
                'ver-recordatorios',
                'ver-notificaciones',
            ],

            'Ventas' => [
                'crear-cliente',
                'editar-cliente',
                'ver-certificados',
                'ver-recordatorios',
                'ver-notificaciones',
            ],

            'Sistemas' => [
                'ver-ubicaciones',
                'ver-geocercas',
                'crear-geocerca',
                'editar-geocerca',
                'eliminar-geocerca',
                'ver-recordatorios',
                'crear-recordatorio',
                'editar-recordatorio',
                'eliminar-recordatorio',
                'ver-certificados',
                'crear-certificado',
                'editar-certificado',
                'ver-notificaciones',
                'ver-reportes',
            ],
        ];

        // Índice de permisos por slug (una sola query)
        $permisosPorSlug = Permiso::pluck('id', 'slug')->all();

        $totalAgregados = 0;

        foreach ($matriz as $rolNombre => $slugs) {
            $rol = Rol::where('rol', $rolNombre)->first();
            if (!$rol) {
                $this->command->warn("Rol no encontrado: {$rolNombre}");
                continue;
            }

            // Administrador: todos los permisos
            if ($slugs === null) {
                $ids = array_values($permisosPorSlug);
            } else {
                $faltantes = array_diff($slugs, array_keys($permisosPorSlug));
                if (!empty($faltantes)) {
                    $this->command->warn("Slugs no encontrados para {$rolNombre}: " . implode(', ', $faltantes));
                }
                $ids = array_values(array_intersect_key($permisosPorSlug, array_flip($slugs)));
            }

            // Permisos que YA tiene el rol
            $existentes = DB::table('permiso_rol')
                ->where('rol', $rolNombre)
                ->pluck('permiso_id')
                ->all();

            // Diferencia: los que hay que agregar
            $aInsertar = array_diff($ids, $existentes);

            if (empty($aInsertar)) {
                $this->command->info("OK {$rolNombre}: sin cambios (" . count($existentes) . " permisos)");
                continue;
            }

            // Insert masivo
            $rows = array_map(fn($id) => [
                'rol'        => $rolNombre,
                'permiso_id' => $id,
                'permitido'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], $aInsertar);

            DB::table('permiso_rol')->insert($rows);

            $totalAgregados += count($aInsertar);
            $nuevoTotal = count($existentes) + count($aInsertar);

            $this->command->info("OK {$rolNombre}: +" . count($aInsertar) . " permisos (total: {$nuevoTotal})");
        }

        $this->command->info("Total agregados: {$totalAgregados}");
    }
}

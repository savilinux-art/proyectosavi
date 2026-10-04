<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeders disponibles en el proyecto.
     *
     * Descomenta solo los que quieras ejecutar con `php artisan db:seed`.
     * El orden importa: primero lo que no depende de nada (roles, permisos,
     * catálogos), al final lo que depende de otros (usuarios, certificados).
     */
    public function run(): void
    {
        $this->call([
            // --- Catálogos base (sin dependencias) ---
            // RolesSeeder::class,
            // PermisosSeeder::class,
            // EstatusSeeder::class,
            // CategoriasSeeder::class,

            // --- Datos que dependen de roles/permisos ---
            // UsuariosSeeder::class,

            // --- Datos de negocio ---
            // CertificadoSeeder::class,
        ]);
    }
}
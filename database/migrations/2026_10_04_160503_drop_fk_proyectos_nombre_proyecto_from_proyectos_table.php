<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropForeign('proyectos_nombre_proyecto_foreign');
        });
    }

    public function down(): void
    {
        // No-op intencional (Q-76).
        // El nombre del proyecto no debe depender de la existencia
        // de una venta. Ver reporte sesión 4 oct 2026.
    }
};

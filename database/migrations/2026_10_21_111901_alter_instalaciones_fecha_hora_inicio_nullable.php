<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * D-4: "fecha_hora_inicio := capturado_at (si era NULL)" requiere
     * que la columna sea nullable. Estaba NOT NULL sin default.
     *
     * Nota: fecha_hora_inicio tiene índice MUL. Laravel 11+ usa ALTER
     * TABLE nativo; el índice se preserva. Verificación post-migrate
     * en el test de la columna (abajo).
     */
    public function up(): void
    {
        Schema::table('instalaciones', function (Blueprint $table) {
            $table->dateTime('fecha_hora_inicio')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('instalaciones', function (Blueprint $table) {
            $table->dateTime('fecha_hora_inicio')->nullable(false)->change();
        });
    }
};

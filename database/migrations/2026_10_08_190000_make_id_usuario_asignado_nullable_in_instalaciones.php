<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Q-101b (revisado 8-oct-2026):
     *   La columna `instalaciones.id_usuario_asignado` es NULLABLE en dev
     *   por un ALTER manual que nunca se versionó. En testing seguía siendo
     *   NOT NULL, lo que rompía los tests al dejar de poblarla desde
     *   InstalacionFactory.
     *
     *   Mismo patrón que `fk_instalaciones_proyecto` (Q-103): drift por
     *   modificación manual en dev sin migración correspondiente.
     *
     *   IDEMPOTENTE: si ya es nullable, no hace nada.
     */
    public function up(): void
    {
        $col = DB::selectOne("
            SELECT IS_NULLABLE
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'instalaciones'
              AND COLUMN_NAME = 'id_usuario_asignado'
        ");

        if ($col && strtoupper($col->IS_NULLABLE) === 'YES') {
            echo "  [skip] id_usuario_asignado ya es nullable.\n";
            return;
        }

        DB::statement("
            ALTER TABLE `instalaciones`
            MODIFY `id_usuario_asignado` VARCHAR(255) NULL DEFAULT NULL
        ");
        echo "  [modify] id_usuario_asignado ahora es NULL DEFAULT NULL.\n";
    }

    public function down(): void
    {
        // No-op intencional (Q-101b): la columna es zombie; el dominio
        // usa el pivote `instalacion_instalador`. Revertir a NOT NULL
        // rompería el desacople.
    }
};
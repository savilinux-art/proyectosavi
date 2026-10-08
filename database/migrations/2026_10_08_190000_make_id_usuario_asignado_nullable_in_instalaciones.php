<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Q-104 (revisado 8-oct-2026 tras drift descubierto en prod):
     *
     * Esta migración originalmente hacía ALTER TABLE asumiendo que la
     * columna id_usuario_asignado existía. En prod NO EXISTE (nunca
     * existió o fue dropeada manualmente — el dominio usa el pivote
     * instalacion_instalador, ver Q-93).
     *
     * La hacemos idempotente:
     *   - Si la columna no existe       → [skip] (equivalente funcional)
     *   - Si existe y ya es nullable    → [skip]
     *   - Si existe y NO es nullable    → [modify]
     */
    public function up(): void
    {
        if (!$this->columnExists('id_usuario_asignado')) {
            echo "  [skip] id_usuario_asignado no existe (nada que hacer).\n";
            return;
        }

        if ($this->isNullable('id_usuario_asignado')) {
            echo "  [skip] id_usuario_asignado ya es nullable.\n";
            return;
        }

        DB::statement("
            ALTER TABLE `instalaciones`
            MODIFY `id_usuario_asignado` VARCHAR(255) NULL DEFAULT NULL
        ");
        echo "  [modify] id_usuario_asignado ahora es nullable.\n";
    }

    public function down(): void
    {
        // No-op intencional (Q-93: la columna se eliminará en sesión dedicada).
    }

    private function columnExists(string $col): bool
    {
        return (bool) DB::selectOne("
            SELECT 1 FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'instalaciones'
              AND COLUMN_NAME = ?
        ", [$col]);
    }

    private function isNullable(string $col): bool
    {
        $row = DB::selectOne("
            SELECT IS_NULLABLE FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'instalaciones'
              AND COLUMN_NAME = ?
        ", [$col]);

        return $row && $row->IS_NULLABLE === 'YES';
    }
};
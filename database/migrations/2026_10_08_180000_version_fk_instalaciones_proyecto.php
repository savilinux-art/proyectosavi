<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Q-103 (revisado 8-oct-2026):
     *   La constraint `fk_instalaciones_proyecto` existe en BD pero NUNCA
     *   se versionó. Se creó manualmente (ALTER TABLE directo) antes del
     *   4-oct-2026. Esta migración la versiona — es IDEMPOTENTE.
     *
     * Evidencia: SHOW CREATE TABLE instalaciones muestra la constraint,
     * pero grep en database/migrations/ no encuentra ninguna que la cree.
     */
    public function up(): void
    {
        if ($this->fkExists('fk_instalaciones_proyecto')) {
            echo "  [skip] fk_instalaciones_proyecto ya existe.\n";
            return;
        }

        // Defensa: eliminar FK legacy a `ventas` si existiera en algún entorno.
        $legacy = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'instalaciones'
              AND COLUMN_NAME = 'nombre_proyecto'
              AND REFERENCED_TABLE_NAME = 'ventas'
        ");
        if ($legacy) {
            DB::statement("ALTER TABLE `instalaciones`
                DROP FOREIGN KEY `{$legacy->CONSTRAINT_NAME}`");
            echo "  [drop] FK legacy a ventas: {$legacy->CONSTRAINT_NAME}\n";
        }

        DB::statement("
            ALTER TABLE `instalaciones`
            ADD CONSTRAINT `fk_instalaciones_proyecto`
            FOREIGN KEY (`nombre_proyecto`)
            REFERENCES `proyectos` (`nombre_proyecto`)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
        ");
        echo "  [add] fk_instalaciones_proyecto creada.\n";
    }

    public function down(): void
    {
        // No-op intencional (Q-103):
        // el desacople Proyecto↔Venta es unidireccional por diseño.
    }

    private function fkExists(string $name): bool
    {
        return (bool) DB::selectOne("
            SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'instalaciones'
              AND CONSTRAINT_NAME = ?
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [$name]);
    }
};
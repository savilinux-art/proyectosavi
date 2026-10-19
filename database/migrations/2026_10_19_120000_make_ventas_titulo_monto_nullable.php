<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $cols = DB::select("
            SELECT COLUMN_NAME, IS_NULLABLE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'ventas'
              AND COLUMN_NAME IN ('titulo_venta','monto_venta')
        ");

        $estado = collect($cols)->pluck('IS_NULLABLE', 'COLUMN_NAME');

        $yaNullable = ($estado['titulo_venta'] ?? 'NO') === 'YES'
                   && ($estado['monto_venta']  ?? 'NO') === 'YES';

        if ($yaNullable) {
            echo "[skip] columnas ya son nullable\n";
            return;
        }

        Schema::table('ventas', function (Blueprint $table) {
            $table->string('titulo_venta', 255)->nullable()->change();
            $table->decimal('monto_venta', 10, 2)->nullable()->change();
        });

        echo "[ok] titulo_venta y monto_venta ahora son nullable\n";
    }

    public function down(): void
    {
        $nullCount = DB::table('ventas')
            ->where(function ($q) {
                $q->whereNull('titulo_venta')->orWhereNull('monto_venta');
            })
            ->count();

        if ($nullCount > 0) {
            throw new \RuntimeException(
                "Rollback bloqueado: hay {$nullCount} ventas con NULL. Limpiá antes."
            );
        }

        Schema::table('ventas', function (Blueprint $table) {
            $table->string('titulo_venta', 255)->nullable(false)->change();
            $table->decimal('monto_venta', 10, 2)->nullable(false)->change();
        });
    }
};

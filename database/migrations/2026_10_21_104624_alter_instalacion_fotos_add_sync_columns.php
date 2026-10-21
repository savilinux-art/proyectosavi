<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instalacion_fotos', function (Blueprint $table) {
            // uuid_cliente para idempotencia (D-1 §14.1)
            // NULL permitido: B-13 aún no existe, B-15 no crea fotos.
            // Los envíos del sync push (B-16) siempre lo traerán.
            $table->uuid('uuid_cliente')
                  ->nullable()
                  ->after('instalacion_id');
        });

        // UNIQUE separado para nombrarlo explícitamente
        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->unique('uuid_cliente', 'uq_instalacion_fotos_uuid_cliente');
        });

        // Índice para pull delta (D-6 §14.6). Hoy no existe.
        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->index('updated_at', 'idx_instalacion_fotos_updated_at');
        });

        // Cambiar FK: CASCADE → RESTRICT (§15.2, §14.5)
        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->dropForeign('instalacion_fotos_instalacion_id_foreign');
        });

        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->foreign('instalacion_id', 'fk_instalacion_fotos_instalacion_id')
                  ->references('id')->on('instalaciones')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->dropForeign('fk_instalacion_fotos_instalacion_id');
        });

        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->foreign('instalacion_id', 'instalacion_fotos_instalacion_id_foreign')
                  ->references('id')->on('instalaciones')
                  ->onDelete('cascade');
        });

        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->dropIndex('idx_instalacion_fotos_updated_at');
            $table->dropUnique('uq_instalacion_fotos_uuid_cliente');
            $table->dropColumn('uuid_cliente');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instalacion_eventos', function (Blueprint $table) {
            // uuid_cliente para idempotencia (D-1 §14.1)
            // NULL permitido: eventos creados por HTTP (B-15) sin UUID.
            // Los eventos del sync push (B-16) siempre lo tendrán.
            $table->uuid('uuid_cliente')
                  ->nullable()
                  ->after('instalacion_id');
        });

        // UNIQUE separado para nombrarlo explícitamente
        Schema::table('instalacion_eventos', function (Blueprint $table) {
            $table->unique('uuid_cliente', 'uq_eventos_uuid_cliente');
        });

        // Índice para pull delta (D-6 §14.6)
        Schema::table('instalacion_eventos', function (Blueprint $table) {
            $table->index('updated_at', 'idx_eventos_updated_at');
        });

        // Cambiar FK: CASCADE → RESTRICT (§15.2, §14.5)
        Schema::table('instalacion_eventos', function (Blueprint $table) {
            $table->dropForeign('instalacion_eventos_instalacion_id_foreign');
        });

        Schema::table('instalacion_eventos', function (Blueprint $table) {
            $table->foreign('instalacion_id', 'fk_instalacion_eventos_instalacion_id')
                  ->references('id')->on('instalaciones')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('instalacion_eventos', function (Blueprint $table) {
            $table->dropForeign('fk_instalacion_eventos_instalacion_id');
        });

        Schema::table('instalacion_eventos', function (Blueprint $table) {
            $table->foreign('instalacion_id', 'instalacion_eventos_instalacion_id_foreign')
                  ->references('id')->on('instalaciones')
                  ->onDelete('cascade');
        });

        Schema::table('instalacion_eventos', function (Blueprint $table) {
            $table->dropIndex('idx_eventos_updated_at');
            $table->dropUnique('uq_eventos_uuid_cliente');
            $table->dropColumn('uuid_cliente');
        });
    }
};

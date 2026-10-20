<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evidencias_entrega', function (Blueprint $table) {
            // B-03: extensión para Módulo A (firma cliente)
            $table->enum('tipo', ['firma_cliente', 'pdf_cliente_firmado', 'foto_entrega'])
                ->default('firma_cliente')
                ->after('archivo_tamano');

            $table->enum('estado', ['vigente', 'invalidada_por_edicion'])
                ->default('vigente')
                ->after('tipo');

            $table->timestamp('invalidada_at')->nullable()->after('estado');
            $table->string('invalidada_por', 255)->nullable()->after('invalidada_at');
            $table->text('invalidada_motivo')->nullable()->after('invalidada_por');

            $table->index(['salida_id', 'estado'], 'evidencias_entrega_salida_estado_idx');
        });
    }

    public function down(): void
    {
        Schema::table('evidencias_entrega', function (Blueprint $table) {
            // dropIndex ANTES de dropColumn: el índice usa 'estado',
            // si dropea 'estado' primero MariaDB lo elimina implícitamente
            // pero emite warning y el down() no queda idempotente.
            $table->dropIndex('evidencias_entrega_salida_estado_idx');
            $table->dropColumn([
                'tipo',
                'estado',
                'invalidada_at',
                'invalidada_por',
                'invalidada_motivo',
            ]);
        });
    }
};

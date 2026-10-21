<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_log', function (Blueprint $table) {
            $table->id();

            // Identificación de la operación
            $table->string('tabla', 50);
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->uuid('uuid_cliente');
            $table->string('operacion', 20); // 'create' | 'update'

            // Contenido y resultado
            $table->json('payload_json');
            $table->string('resultado', 20);
            // 'ok' | 'conflicto' | 'huerfano' | 'error' | 'duplicado_distinto'
            $table->text('detalle')->nullable();

            // Tiempos
            $table->timestamp('intentado_at');
            $table->timestamp('aplicado_at')->nullable();
            $table->timestamps();

            // Idempotencia (D-1 §14.1)
            $table->unique(['tabla', 'uuid_cliente'], 'uq_sync_log_tabla_uuid');

            // Consulta admin: "problemas recientes"
            $table->index(['resultado', 'intentado_at'], 'idx_sync_log_resultado_fecha');

            // Lookup por registro afectado
            $table->index('registro_id', 'idx_sync_log_registro');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_log');
    }
};

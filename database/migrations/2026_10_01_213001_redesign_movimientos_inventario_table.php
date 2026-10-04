<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('movimientos_inventario');

        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();

            // 🎯 Trazabilidad: material + proyecto
            $table->foreignId('inventario_id')
                  ->constrained('inventario')
                  ->cascadeOnDelete();

            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->cascadeOnDelete();

            // Diseño "ancho" (compatible con código actual)
            $table->integer('entrada')->nullable();
            $table->integer('salida')->nullable();
            $table->integer('ajuste')->nullable();
            $table->integer('devolucion')->nullable();
            $table->integer('apartado')->nullable();
            $table->integer('devolucion_proveedor')->nullable();

            // Auditoría
            $table->string('modificado_por')->nullable();
            $table->foreign('modificado_por')
                  ->references('usuario')
                  ->on('usuarios')
                  ->nullOnDelete();

            $table->text('comentarios')->nullable();
            $table->timestamps();

            // Índices para consultas frecuentes
            $table->index(['proyecto_id', 'inventario_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
        // (opcional: recrear la versión vieja si hace falta rollback)
    }
};
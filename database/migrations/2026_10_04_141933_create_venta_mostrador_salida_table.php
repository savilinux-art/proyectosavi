<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta_mostrador_salida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_mostrador_id')
                  ->constrained('ventas_mostrador')
                  ->cascadeOnDelete();
            $table->foreignId('salida_inventario_id')
                  ->constrained('salidas_inventario')
                  ->restrictOnDelete();
            $table->timestamps();

            $table->unique(
                ['venta_mostrador_id', 'salida_inventario_id'],
                'venta_mostrador_salida_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_mostrador_salida');
    }
};
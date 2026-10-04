<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta_mostrador_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_mostrador_id')
                  ->constrained('ventas_mostrador')
                  ->cascadeOnDelete();
            $table->foreignId('inventario_id')
                  ->constrained('inventario')
                  ->restrictOnDelete();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index('inventario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_mostrador_detalles');
    }
};
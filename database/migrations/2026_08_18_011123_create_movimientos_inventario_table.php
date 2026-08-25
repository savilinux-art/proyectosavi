<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventario_id');
            $table->foreign('inventario_id')->references('id')->on('inventario');
            $table->integer('entrada')->nullable();
            $table->integer('salida')->nullable();
            $table->integer('ajuste')->nullable();
            $table->integer('devolucion')->nullable();
            $table->integer('apartado')->nullable();
            $table->string('instalacion')->nullable();
            $table->foreign('instalacion')->references('nombre_proyecto')->on('ventas');
            $table->integer('devolucion_proveedor')->nullable();
            $table->string('modificado_por');
            $table->foreign('modificado_por')->references('usuario')->on('usuarios');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
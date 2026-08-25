<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo_venta');
            $table->string('nombre_proyecto')->unique();
            $table->string('moneda');
            $table->decimal('monto_venta', 10, 2);
            $table->string('requerimiento_venta');
            $table->mediumBlob('cotizacion')->nullable();
            $table->point('ubicacion')->nullable();
            $table->string('vendedor');
            $table->foreign('vendedor')->references('usuario')->on('usuarios');
            $table->dateTime('fecha_hora_levantamiento');
            $table->mediumBlob('levantamiento')->nullable();
            $table->boolean('venta_ganada')->default(false);
            $table->string('razon_perdida_venta')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
};
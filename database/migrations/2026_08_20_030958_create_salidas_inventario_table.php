<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salidas_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_proyecto');
            $table->foreign('nombre_proyecto')->references('nombre_proyecto')->on('ventas');
            $table->string('entregado_por');
            $table->foreign('entregado_por')->references('usuario')->on('usuarios');
            $table->string('entregado_a');
            $table->foreign('entregado_a')->references('usuario')->on('usuarios');
            $table->json('productos');
            $table->dateTime('fecha_hora_salida');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salidas_inventario');
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('devoluciones_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_proyecto')->nullable();
            $table->foreign('nombre_proyecto')->references('nombre_proyecto')->on('ventas')->onDelete('set null');
            $table->string('devuelto_por');
            $table->foreign('devuelto_por')->references('usuario')->on('usuarios');
            $table->string('recibido_por');
            $table->foreign('recibido_por')->references('usuario')->on('usuarios');
            $table->json('productos');
            $table->dateTime('fecha_hora_devolucion');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('devoluciones_inventario');
    }
};
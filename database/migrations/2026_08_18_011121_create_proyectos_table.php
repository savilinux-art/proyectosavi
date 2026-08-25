<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_proyecto');
            $table->foreign('nombre_proyecto')->references('nombre_proyecto')->on('ventas');
            $table->string('correo_electronico');
            $table->point('ubicacion')->nullable();
            $table->mediumBlob('propuesta_economica')->nullable();
            $table->mediumBlob('archivo_as_built')->nullable();
            $table->string('credenciales')->nullable();
            $table->mediumBlob('salida_inventario')->nullable();
            $table->mediumBlob('devolucion_inventario')->nullable();
            $table->string('modificado_por');
            $table->foreign('modificado_por')->references('usuario')->on('usuarios');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
};
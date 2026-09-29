<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->binary('propuesta_economica')->nullable();
            $table->binary('archivo_as_built')->nullable();
            $table->string('credenciales')->nullable();
            $table->binary('salida_inventario')->nullable();
            $table->binary('devolucion_inventario')->nullable();
            $table->string('modificado_por');
            $table->foreign('modificado_por')->references('usuario')->on('usuarios');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE `proyectos` MODIFY `propuesta_economica` MEDIUMBLOB NULL, MODIFY `archivo_as_built` MEDIUMBLOB NULL, MODIFY `salida_inventario` MEDIUMBLOB NULL, MODIFY `devolucion_inventario` MEDIUMBLOB NULL, ADD `ubicacion` POINT NULL AFTER `correo_electronico`');
    }

    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
};
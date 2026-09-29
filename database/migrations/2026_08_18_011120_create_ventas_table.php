<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->binary('cotizacion')->nullable();
            $table->string('vendedor');
            $table->foreign('vendedor')->references('usuario')->on('usuarios');
            $table->dateTime('fecha_hora_levantamiento');
            $table->binary('levantamiento')->nullable();
            $table->boolean('venta_ganada')->default(false);
            $table->string('razon_perdida_venta')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE `ventas` MODIFY `cotizacion` MEDIUMBLOB NULL, MODIFY `levantamiento` MEDIUMBLOB NULL, ADD `ubicacion` POINT NULL AFTER `cotizacion`');
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
};
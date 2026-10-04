<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trazabilidad_discrepancias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id');
            $table->unsignedBigInteger('inventario_id')->nullable();
            $table->enum('tipo', ['faltante', 'sobrante']);
            $table->integer('cantidad_discrepancia');
            $table->enum('estado', ['abierta', 'resuelta'])->default('abierta');
            $table->dateTime('detectado_en');
            $table->dateTime('resuelto_en')->nullable();
            $table->string('resuelto_por', 255)->nullable();
            $table->text('notas_resolucion')->nullable();
            $table->unsignedBigInteger('corregido_por_salida_id')->nullable();
            $table->unsignedBigInteger('corregido_por_cotizacion_id')->nullable();
            $table->timestamps();

            $table->index('proyecto_id', 'traz_disc_proyecto_id_index');
            $table->index('estado',      'traz_disc_estado_index');
            $table->index(
                ['proyecto_id', 'inventario_id', 'estado'],
                'traz_disc_proy_inv_estado_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trazabilidad_discrepancias');
    }
};
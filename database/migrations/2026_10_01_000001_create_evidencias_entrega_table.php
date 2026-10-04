<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidencias_entrega', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salida_id');                       // FK lógica → salidas_inventario.id
            $table->unsignedBigInteger('proyecto_id');                     // FK lógica → proyectos.id
            $table->string('archivo_path', 500);                           // relativo a storage/app/evidencias/{proyecto_id}/
            $table->string('archivo_nombre_original', 255)->nullable();
            $table->string('archivo_mime', 100)->nullable();
            $table->unsignedBigInteger('archivo_tamano')->nullable();      // bytes
            $table->text('notas')->nullable();
            $table->string('subido_por', 255);                             // Q-108: FK lógica → usuarios.usuario
            $table->timestamps();

            $table->index('salida_id',   'evidencias_entrega_salida_id_index');
            $table->index('proyecto_id', 'evidencias_entrega_proyecto_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidencias_entrega');
    }
};
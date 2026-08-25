<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instalaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_proyecto');
            $table->foreign('nombre_proyecto')->references('nombre_proyecto')->on('ventas');
            $table->binary('ubicacion_actual')->nullable();
            $table->string('id_usuario_asignado');
            $table->foreign('id_usuario_asignado')->references('usuario')->on('usuarios');
            $table->binary('evidencia_inicio')->nullable();
            $table->binary('incidencias')->nullable();
            $table->binary('evidencia_fin')->nullable();
            $table->json('check_list')->nullable();
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin')->nullable();
            $table->string('estatus_instalacion');
            $table->foreign('estatus_instalacion')->references('estatus')->on('estatus');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instalaciones');
    }
};

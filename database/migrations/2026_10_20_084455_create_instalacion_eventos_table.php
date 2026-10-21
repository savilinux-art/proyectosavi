<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instalacion_eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instalacion_id');
            $table->enum('tipo', [
                'iniciada',
                'pausada',
                'reanudada',
                'finalizada',
                'incidencia',
                'checklist_actualizado',
                'nota_agregada',
            ]);

            // Ubicación — todos nullable porque no siempre hay GPS
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->unsignedInteger('precision_metros')->nullable();
            $table->string('fuente', 20)->nullable(); // gps|network|cached|mock|no_disponible

            // Datos flexibles: motivo de pausa, texto de nota, etc.
            $table->json('payload_json')->nullable();

            // capturado_at = hora del dispositivo. created_at = hora del servidor.
            $table->dateTime('capturado_at');
            $table->string('por', 255); // usuarios.usuario

            $table->timestamps();

            $table->foreign('instalacion_id')
                  ->references('id')
                  ->on('instalaciones')
                  ->onDelete('cascade');

            $table->index(['instalacion_id', 'tipo', 'capturado_at']);
            $table->index(['por', 'capturado_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instalacion_eventos');
    }
};
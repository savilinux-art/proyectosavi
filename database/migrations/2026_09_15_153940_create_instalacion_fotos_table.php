<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instalacion_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instalacion_id')->constrained('instalaciones')->cascadeOnDelete();
            $table->string('ruta');
            $table->string('nombre_original')->nullable();
            $table->string('mime', 100)->nullable();
            $table->unsignedInteger('tamano_kb')->nullable();
            $table->enum('tipo', ['inicio', 'proceso', 'fin', 'incidencia'])->default('proceso');
            $table->text('descripcion')->nullable();
            $table->string('subida_por_usuario')->nullable();
            $table->timestamps();
            $table->index(['instalacion_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instalacion_fotos');
    }
};

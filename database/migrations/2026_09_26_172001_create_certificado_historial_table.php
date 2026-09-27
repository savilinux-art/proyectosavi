<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificado_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recordatorio_id')->constrained('recordatorios')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->date('fecha_vencimiento_anterior');
            $table->date('fecha_vencimiento_nueva');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificado_historial');
    }
};

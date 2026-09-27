<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones_web', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('recordatorio_id')->nullable()->constrained('recordatorios')->nullOnDelete();
            $table->string('titulo');
            $table->text('cuerpo');
            $table->dateTime('leida_at')->nullable();
            $table->timestamps();

            $table->index(['usuario_id', 'leida_at'], 'idx_notif_no_leidas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones_web');
    }
};
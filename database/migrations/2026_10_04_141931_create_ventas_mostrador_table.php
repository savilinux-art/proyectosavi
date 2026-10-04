<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas_mostrador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->restrictOnDelete();
            $table->enum('estado', ['pendiente', 'completada', 'cancelada'])
                  ->default('pendiente');
            $table->decimal('total', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->string('creado_por');
            $table->string('modificado_por')->nullable();
            $table->timestamps();

            $table->index('estado');
            $table->index('creado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas_mostrador');
    }
};

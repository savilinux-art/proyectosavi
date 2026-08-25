<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            // Cambiar de unsignedBigInteger a string para que coincida con usuarios.usuario
            $table->string('usuario_id'); // <-- Cambiar a string
            $table->foreign('usuario_id')->references('usuario')->on('usuarios'); // <-- Apuntar a 'usuario'
            $table->text('mensaje');
            $table->string('tipo');
            $table->integer('referencia_id')->nullable();
            $table->boolean('leida')->default(false);
            $table->dateTime('fecha_creacion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
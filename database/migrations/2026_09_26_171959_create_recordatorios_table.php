<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();

            // Tipo y destinatario
            $table->enum('tipo', ['general', 'certificado', 'sistema'])->default('general');
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('usuarios')->nullOnDelete();

            // Contenido
            $table->string('titulo');
            $table->text('descripcion')->nullable();

            // Programación
            $table->dateTime('fecha_hora_programada');
            $table->enum('estatus', ['pendiente', 'enviado', 'cancelado', 'completado', 'error'])
                  ->default('pendiente');
            $table->enum('recurrencia', ['una_vez', 'diario', 'semanal', 'mensual', 'personalizado'])
                  ->default('una_vez');
            $table->json('regla_recurrencia')->nullable();

            // Canales y envío
            $table->json('canal')->default(json_encode(['telegram']));
            $table->dateTime('enviado_at')->nullable();
            $table->unsignedTinyInteger('intentos')->default(0);
            $table->text('ultimo_error')->nullable();

            // Relación polimórfica (Instalacion, Proyecto, Venta, Cliente)
            $table->nullableMorphs('recordable');

            // Metadata libre
            $table->json('metadata')->nullable();

            // Campos específicos de certificado (solo aplican si tipo='certificado')
            $table->string('cert_nombre')->nullable();
            $table->enum('cert_tipo', ['ssl', 'csd', 'dominio', 'otro'])->nullable();
            $table->string('cert_emisor')->nullable();
            $table->string('cert_serie')->nullable();
            $table->date('cert_fecha_emision')->nullable();
            $table->date('cert_fecha_vencimiento')->nullable();
            $table->string('cert_link_renovacion', 500)->nullable();
            $table->string('cert_archivo_path', 500)->nullable();
            $table->json('cert_avisos_dias')->nullable(); // [15,7,3,1,0]
            $table->dateTime('cert_renovado_at')->nullable();

            $table->timestamps();

            // Índices para consultas del scheduler
            $table->index(['estatus', 'fecha_hora_programada'], 'idx_rec_pendientes');
            $table->index(['tipo', 'estatus'], 'idx_rec_tipo_estatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};

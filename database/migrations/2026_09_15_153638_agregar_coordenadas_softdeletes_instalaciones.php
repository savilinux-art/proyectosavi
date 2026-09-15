<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instalaciones', function (Blueprint $table) {
            $table->decimal('latitud', 10, 7)->nullable()->after('ubicacion_actual');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
            $table->string('direccion', 255)->nullable()->after('longitud');
            $table->timestamp('ubicacion_actualizada_en')->nullable()->after('direccion');
            $table->softDeletes();
            $table->index('estatus_instalacion');
            $table->index('fecha_hora_inicio');
        });
    }

    public function down(): void
    {
        Schema::table('instalaciones', function (Blueprint $table) {
            $table->dropIndex(['estatus_instalacion']);
            $table->dropIndex(['fecha_hora_inicio']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'latitud', 'longitud', 'direccion', 'ubicacion_actualizada_en',
            ]);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->decimal('latitud', 10, 7)->nullable()->after('descripcion');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
            $table->unsignedInteger('precision_metros')->nullable()->after('longitud');
            $table->string('fuente', 20)->nullable()->after('precision_metros');
        });
    }

    public function down(): void
    {
        Schema::table('instalacion_fotos', function (Blueprint $table) {
            $table->dropColumn(['latitud', 'longitud', 'precision_metros', 'fuente']);
        });
    }
};

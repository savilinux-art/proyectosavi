<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('cotizacion_path')->nullable()->after('cotizacion');
            $table->string('levantamiento_path')->nullable()->after('levantamiento');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['cotizacion_path', 'levantamiento_path']);
        });
    }
};

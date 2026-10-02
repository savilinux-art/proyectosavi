<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devoluciones_inventario', function (Blueprint $table) {
            $table->dropColumn('productos');
        });
    }

    public function down(): void
    {
        Schema::table('devoluciones_inventario', function (Blueprint $table) {
            $table->longText('productos')->nullable();
        });
    }
};

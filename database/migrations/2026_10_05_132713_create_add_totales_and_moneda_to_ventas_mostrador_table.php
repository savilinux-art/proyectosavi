<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas_mostrador', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->default(0)->after('total');
            $table->decimal('iva', 12, 2)->default(0)->after('subtotal');
            $table->string('moneda', 3)->default('MXN')->after('iva');
        });
    }

    public function down(): void
    {
        Schema::table('ventas_mostrador', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'iva', 'moneda']);
        });
    }
};

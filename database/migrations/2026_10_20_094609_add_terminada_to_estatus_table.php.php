<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('estatus')->insertOrIgnore([
            'estatus'    => 'terminada',
            'tipo'       => 'instalacion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('estatus')
            ->where('estatus', 'terminada')
            ->where('tipo', 'instalacion')
            ->delete();
    }
};
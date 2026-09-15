<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $nuevos = ['pendiente', 'asignada', 'completada', 'cancelada'];
        $exist  = DB::table('estatus')->where('tipo', 'instalacion')->pluck('estatus')->toArray();

        foreach ($nuevos as $e) {
            if (!in_array($e, $exist)) {
                DB::table('estatus')->insert([
                    'estatus'    => $e,
                    'tipo'       => 'instalacion',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('estatus')->whereIn('estatus', ['pendiente','asignada','completada','cancelada'])
            ->where('tipo', 'instalacion')->delete();
    }
};
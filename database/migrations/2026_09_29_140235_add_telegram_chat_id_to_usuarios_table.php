<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega telegram_chat_id a usuarios de forma idempotente.
 *
 * Contexto:
 *   La columna existe en laptop (fresh install) pero NO en prod.
 *   Fue agregada a create_usuarios_table.php, pero esa migración
 *   ya corrió en prod, así que el cambio nunca llegó ahí.
 *
 *   Idempotente: no-op si la columna ya existe.
 *
 * Aplicar en server el día que se capturen todos los chat_ids.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('usuarios', 'telegram_chat_id')) {
            return;
        }
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('telegram_chat_id')->nullable()->unique()->after('correo');
        });
    }

    public function down(): void
    {
        // No-op: remover la columna perdería chat_ids capturados.
    }
};

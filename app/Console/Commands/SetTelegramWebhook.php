<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;

class SetTelegramWebhook extends Command
{
    protected $signature   = 'telegram:set-webhook {--reset : Descarta updates pendientes}';
    protected $description = 'Configura el webhook del bot de Telegram';

    public function handle()
    {
        $token    = config('telegram.bot_token');
        $telegram = new Api($token);
        $url      = config('app.url') . '/api/telegram/webhook';

        try {
            // 🔧 FIX: Opcionalmente limpiar updates pendientes primero
            if ($this->option('reset')) {
                $this->warn('🗑️  Descartando updates pendientes...');
                $telegram->removeWebhook(); // esto por sí solo no borra pendientes
                file_get_contents("https://api.telegram.org/bot{$token}/deleteWebhook?drop_pending_updates=true");
                $this->info('✅ Updates pendientes descartados.');
            }

            $response = $telegram->setWebhook(['url' => $url]);
            $this->info('✅ Webhook configurado: ' . $url);

            // Info del webhook
            $info = $telegram->getWebhookInfo();
            $this->line('Estado actual:');
            $this->line('  pending_update_count: ' . ($info['pending_update_count'] ?? 0));
            $this->line('  last_error_message:   ' . ($info['last_error_message'] ?? 'ninguno'));

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}
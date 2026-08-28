<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Configura el webhook del bot de Telegram';

    public function handle()
    {
        $telegram = new Api(config('telegram.bot_token'));
        $url = config('app.url') . '/api/telegram/webhook';

        try {
            $response = $telegram->setWebhook(['url' => $url]);
            $this->info('✅ Webhook configurado correctamente.');
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}
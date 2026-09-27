<?php

namespace App\Services\Telegram;

use App\Models\Recordatorio;
use App\Models\Usuario;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CommandRouter
{
    public function __construct(
        protected TelegramService $telegram
    ) {}

    /**
     * Punto de entrada. Recibe $message de Telegram y decide qué hacer.
     * Devuelve true si el mensaje fue un comando reconocido.
     */
    public function handle(array $message): bool
    {
        $chatId = $message['chat']['id'] ?? null;
        $text   = trim($message['text'] ?? '');

        if (!$chatId || $text === '') {
            return false;
        }

        // Solo procesamos si empieza con "/"
        if (!str_starts_with($text, '/')) {
            return false;
        }

        // Parseo: /comando arg1 arg2...
        $partes  = preg_split('/\s+/', $text, 2);
        $comando = strtolower(ltrim($partes[0], '/'));
        $args    = $partes[1] ?? '';

        // Quita @botname (Telegram lo agrega en grupos)
        if (str_contains($comando, '@')) {
            $comando = explode('@', $comando)[0];
        }

        $usuario = Usuario::where('telegram_chat_id', $chatId)->first();
        if (!$usuario) {
            $this->telegram->sendMessage($chatId, '❌ No estás registrado en el sistema.');
            return true;
        }

        return match ($comando) {
            'start', 'help'              => $this->cmdHelp($chatId),
            'recordar'                   => $this->cmdRecordar($chatId, $usuario, $args),
            'recordatorios'              => $this->cmdRecordatorios($chatId, $usuario),
            'cancelar_recordatorio'      => $this->cmdCancelar($chatId, $usuario, $args),
            'certificados'               => $this->cmdCertificados($chatId, $usuario),
            'nuevo_cert'                 => $this->cmdNuevoCert($chatId, $usuario, $args),
            default                      => $this->cmdDesconocido($chatId, $comando),
        };
    }

    /* ============================================================
     *  /help y /start
     * ============================================================ */
    protected function cmdHelp(string $chatId): bool
    {
        $texto = "🤖 *Comandos disponibles*\n\n" .
            "📝 `/recordar <texto> | <fecha>`\n" .
            "   Crea un recordatorio.\n" .
            "   Ej: `/recordar Comprar tornillos | 2026-10-01 15:00`\n\n" .
            "📋 `/recordatorios`\n" .
            "   Lista tus recordatorios pendientes.\n\n" .
            "❌ `/cancelar_recordatorio <id>`\n" .
            "   Cancela un recordatorio pendiente.\n\n" .
            "🔐 `/certificados`\n" .
            "   Lista certificados con días restantes.\n\n" .
            "🆕 `/nuevo_cert <nombre> | <YYYY-MM-DD>`\n" .
            "   Registra un certificado rápido.\n" .
            "   Ej: `/nuevo_cert SSL mitienda.com | 2027-03-15`";

        $this->telegram->sendMessage($chatId, $texto);
        return true;
    }

    /* ============================================================
     *  /recordar <texto> | <fecha>
     * ============================================================ */
    protected function cmdRecordar(string $chatId, Usuario $usuario, string $args): bool
    {
        if (!str_contains($args, '|')) {
            $this->telegram->sendMessage(
                $chatId,
                "⚠️ Formato incorrecto.\n\n" .
                "Uso: `/recordar <texto> | <fecha>`\n" .
                "Ej: `/recordar Comprar tornillos | 2026-10-01 15:00`"
            );
            return true;
        }

        [$titulo, $fechaRaw] = array_map('trim', explode('|', $args, 2));

        if ($titulo === '' || $fechaRaw === '') {
            $this->telegram->sendMessage($chatId, '⚠️ Título y fecha son obligatorios.');
            return true;
        }

        try {
            $fecha = Carbon::parse($fechaRaw);
        } catch (\Throwable $e) {
            $this->telegram->sendMessage(
                $chatId,
                "❌ No pude interpretar la fecha: `{$fechaRaw}`\n\n" .
                "Formatos válidos:\n" .
                "• `2026-10-01 15:00`\n" .
                "• `2026-10-01`\n" .
                "• `mañana 15:00`"
            );
            return true;
        }

        if ($fecha->isPast()) {
            $this->telegram->sendMessage($chatId, '⚠️ La fecha ya pasó. Usa una fecha futura.');
            return true;
        }

        $recordatorio = Recordatorio::create([
            'tipo'                  => 'general',
            'usuario_id'            => $usuario->id,
            'created_by'            => $usuario->id,
            'titulo'                => mb_substr($titulo, 0, 200),
            'fecha_hora_programada' => $fecha,
            'recurrencia'           => 'una_vez',
            'canal'                 => ['telegram', 'web'],
            'estatus'               => 'pendiente',
            'intentos'              => 0,
        ]);

        $this->telegram->sendMessage(
            $chatId,
            "✅ Recordatorio #{$recordatorio->id} creado.\n\n" .
            "📝 {$titulo}\n" .
            "🕒 " . $fecha->format('d/m/Y H:i')
        );
        return true;
    }

    /* ============================================================
     *  /recordatorios
     * ============================================================ */
    protected function cmdRecordatorios(string $chatId, Usuario $usuario): bool
    {
        $lista = Recordatorio::where('usuario_id', $usuario->id)
            ->where('estatus', 'pendiente')
            ->where('tipo', '!=', 'certificado') // los certs van en /certificados
            ->orderBy('fecha_hora_programada')
            ->limit(10)
            ->get();

        if ($lista->isEmpty()) {
            $this->telegram->sendMessage($chatId, '📭 No tienes recordatorios pendientes.');
            return true;
        }

        $texto = "📋 *Tus recordatorios pendientes* (top 10)\n\n";
        foreach ($lista as $r) {
            $texto .= "• *#{$r->id}* {$r->titulo}\n";
            $texto .= "  🕒 {$r->fecha_hora_programada->format('d/m/Y H:i')}\n\n";
        }
        $texto .= "Para cancelar: `/cancelar_recordatorio <id>`";

        $this->telegram->sendMessage($chatId, $texto);
        return true;
    }

    /* ============================================================
     *  /cancelar_recordatorio <id>
     * ============================================================ */
    protected function cmdCancelar(string $chatId, Usuario $usuario, string $args): bool
    {
        $id = (int) trim($args);
        if ($id <= 0) {
            $this->telegram->sendMessage($chatId, '⚠️ Uso: `/cancelar_recordatorio <id>`');
            return true;
        }

        $recordatorio = Recordatorio::where('id', $id)
            ->where('usuario_id', $usuario->id)
            ->first();

        if (!$recordatorio) {
            $this->telegram->sendMessage($chatId, "❌ No encontré el recordatorio #{$id} (o no es tuyo).");
            return true;
        }

        if ($recordatorio->estatus !== 'pendiente') {
            $this->telegram->sendMessage(
                $chatId,
                "⚠️ El recordatorio #{$id} está en estatus `{$recordatorio->estatus}` y no se puede cancelar."
            );
            return true;
        }

        $recordatorio->update(['estatus' => 'cancelado']);
        $this->telegram->sendMessage($chatId, "✅ Recordatorio #{$id} cancelado.");
        return true;
    }

    /* ============================================================
     *  /certificados
     * ============================================================ */
    protected function cmdCertificados(string $chatId, Usuario $usuario): bool
    {
        $certs = Recordatorio::where('usuario_id', $usuario->id)
            ->where('tipo', 'certificado')
            ->where('estatus', 'pendiente')
            ->whereNotNull('cert_fecha_vencimiento')
            ->orderBy('cert_fecha_vencimiento')
            ->get();

        if ($certs->isEmpty()) {
            $this->telegram->sendMessage($chatId, '📭 No tienes certificados registrados.');
            return true;
        }

        $texto = "🔐 *Tus certificados*\n\n";
        foreach ($certs as $c) {
            $dias = $c->diasRestantesCertificado();
            $emoji = match (true) {
                $dias === null      => '⚪',
                $dias < 0           => '🔴',
                $dias === 0         => '🔴',
                $dias <= 3          => '🟠',
                $dias <= 7          => '🟡',
                $dias <= 15         => '🟢',
                default             => '⚪',
            };

            $estado = match (true) {
                $dias === null      => 'sin fecha',
                $dias < 0           => 'VENCIDO hace ' . abs($dias) . 'd',
                $dias === 0         => 'VENCE HOY',
                default             => "{$dias}d restantes",
            };

            $texto .= "{$emoji} *#{$c->id}* {$c->cert_nombre}\n";
            $texto .= "  📅 {$c->cert_fecha_vencimiento->format('d/m/Y')} — {$estado}\n\n";
        }

        $this->telegram->sendMessage($chatId, $texto);
        return true;
    }

    /* ============================================================
     *  /nuevo_cert <nombre> | <YYYY-MM-DD>
     * ============================================================ */
    protected function cmdNuevoCert(string $chatId, Usuario $usuario, string $args): bool
    {
        if (!str_contains($args, '|')) {
            $this->telegram->sendMessage(
                $chatId,
                "⚠️ Formato incorrecto.\n\n" .
                "Uso: `/nuevo_cert <nombre> | <YYYY-MM-DD>`\n" .
                "Ej: `/nuevo_cert SSL mitienda.com | 2027-03-15`"
            );
            return true;
        }

        [$nombre, $fechaRaw] = array_map('trim', explode('|', $args, 2));

        if ($nombre === '' || $fechaRaw === '') {
            $this->telegram->sendMessage($chatId, '⚠️ Nombre y fecha son obligatorios.');
            return true;
        }

        try {
            $venc = Carbon::parse($fechaRaw)->startOfDay();
        } catch (\Throwable $e) {
            $this->telegram->sendMessage($chatId, "❌ No pude interpretar la fecha: `{$fechaRaw}`");
            return true;
        }

        if ($venc->isPast()) {
            $this->telegram->sendMessage($chatId, '⚠️ La fecha de vencimiento ya pasó.');
            return true;
        }

        $avisos = [15, 7, 3, 1, 0];
        // Programa el primer aviso: el mayor del array que aún no haya pasado
        $primerAviso = collect($avisos)
            ->sortDesc()
            ->first(fn ($d) => $venc->copy()->subDays($d)->isFuture())
            ?? end($avisos);

        $cert = Recordatorio::create([
            'tipo'                  => 'certificado',
            'usuario_id'            => $usuario->id,
            'created_by'            => $usuario->id,
            'titulo'                => mb_substr("Certificado: {$nombre}", 0, 200),
            'cert_nombre'           => mb_substr($nombre, 0, 200),
            'cert_tipo'             => 'otro',
            'cert_fecha_vencimiento' => $venc,
            'cert_avisos_dias'      => $avisos,
            'recurrencia'           => 'personalizado',
            'canal'                 => ['telegram', 'web'],
            'estatus'               => 'pendiente',
            'intentos'              => 0,
            'fecha_hora_programada' => $venc->copy()->subDays($primerAviso)->setTime(9, 0, 0),
        ]);

        $this->telegram->sendMessage(
            $chatId,
            "✅ Certificado #{$cert->id} registrado.\n\n" .
            "🔐 {$nombre}\n" .
            "📅 Vence: {$venc->format('d/m/Y')}\n" .
            "🔔 Primer aviso: {$cert->fecha_hora_programada->format('d/m/Y H:i')}"
        );
        return true;
    }

    /* ============================================================
     *  Comando desconocido
     * ============================================================ */
    protected function cmdDesconocido(string $chatId, string $comando): bool
    {
        $this->telegram->sendMessage(
            $chatId,
            "❓ No conozco el comando `/{$comando}`.\n\n" .
            "Usa `/help` para ver los comandos disponibles."
        );
        return true;
    }
}

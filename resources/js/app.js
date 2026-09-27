import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

window.Echo = echo;

// Canal público de ubicaciones
echo.channel('ubicaciones')
    .listen('.ubicacion.actualizada', (data) => {
        console.log('📍 Nueva ubicación recibida:', data);
        window.dispatchEvent(new CustomEvent('ubicacion.actualizada', { detail: data }));
    });

// Log del estado de la conexión (útil para depurar)
echo.connector?.pusher?.connection?.bind('connected', () => {
    console.log('✅ Conectado a Reverb (WebSocket activo)');
});
echo.connector?.pusher?.connection?.bind('error', (err) => {
    console.error('❌ Error de conexión Reverb:', err);
});
echo.connector?.pusher?.connection?.bind('disconnected', () => {
    console.warn('⚠️ Desconectado de Reverb');
});

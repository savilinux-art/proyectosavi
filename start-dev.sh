#!/bin/bash
# start-dev.sh - Levanta todos los servicios de desarrollo

# ---- Config ----
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_DIR="$PROJECT_DIR/logs"
TELEGRAM_TOKEN="8707500603:AAF_L_peg7MVCys6Jro7QEC25A0RKgS5UWI"   # <-- pon aquí tu token
NGROK_URL="https://unvented-riverbank-endless.ngrok-free.dev"
WEBHOOK_PATH="/api/telegram/webhook"

# Colores
GREEN="\033[0;32m"; YELLOW="\033[1;33m"; RED="\033[0;31m"; NC="\033[0m"

mkdir -p "$LOG_DIR"
cd "$PROJECT_DIR" || exit 1

PIDS=()

cleanup() {
  echo -e "\n${YELLOW}>> Deteniendo servicios...${NC}"
  for pid in "${PIDS[@]}"; do
    kill "$pid" 2>/dev/null
    # por si es un proceso hijo en árbol
    pkill -P "$pid" 2>/dev/null
  done
  # por si ngrok quedó colgado
  pkill -f "ngrok http 8000" 2>/dev/null
  wait 2>/dev/null
  echo -e "${GREEN}>> Servicios detenidos.${NC}"
  exit 0
}
trap cleanup SIGINT SIGTERM EXIT

start_service() {
  local name="$1"; shift
  local logfile="$LOG_DIR/${name}.log"
  echo -e "${GREEN}>> Iniciando:${NC} $name"
  "$@" > "$logfile" 2>&1 &
  local pid=$!
  PIDS+=("$pid")
  echo "   PID: $pid  |  log: $logfile"
}

# ---- 1. Laravel serve ----
start_service "laravel-serve" php artisan serve  --host=0.0.0.0 --host=127.0.0.1 --port=8000
sleep 2   # dar tiempo a que escuche el 8000

# ---- 2. Reverb ----
start_service "reverb" php artisan reverb:start  --host=0.0.0.0  --host=127.0.0.1 --port=8080
sleep 1

# ---- 3. ngrok ----
start_service "ngrok" ngrok http 8000
sleep 4   # ngrok tarda en publicar la URL

# ---- 4. Vite / npm ----
start_service "vite" npm run dev
sleep 2

# ---- 5. Set webhook de Telegram ----
echo -e "${GREEN}>> Configurando webhook de Telegram...${NC}"
WEBHOOK_URL="${NGROK_URL}${WEBHOOK_PATH}"
RESP=$(curl -s -X POST "https://api.telegram.org/bot${TELEGRAM_TOKEN}/setWebhook" \
  -d "url=${WEBHOOK_URL}")
echo "   Respuesta: $RESP"

echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN} Todos los servicios están corriendo${NC}"
echo -e "${GREEN}========================================${NC}"
echo " Webhook: $WEBHOOK_URL"
echo " Logs en: $LOG_DIR/"
echo -e "${YELLOW} Pulsa Ctrl+C para detener todo${NC}\n"

# Mantener el script vivo y mostrar logs en vivo (opcional)
wait
#!/bin/bash
# start-dev.sh - Levanta todos los servicios de desarrollo

# ---- Config ----
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_DIR="$PROJECT_DIR/logs"
NGROK_URL="https://unvented-riverbank-endless.ngrok-free.dev"
WEBHOOK_PATH="/api/telegram/webhook"

# Telegram token desde .env (no hardcodeado)
TELEGRAM_TOKEN=$(grep '^TELEGRAM_BOT_TOKEN=' "$PROJECT_DIR/.env" | cut -d '=' -f2-)

# Colores
GREEN="\033[0;32m"; YELLOW="\033[1;33m"; RED="\033[0;31m"; CYAN="\033[0;36m"; NC="\033[0m"

mkdir -p "$LOG_DIR"
cd "$PROJECT_DIR" || exit 1

PIDS=()

cleanup() {
  echo -e "\n${YELLOW}>> Deteniendo servicios...${NC}"
  for pid in "${PIDS[@]}"; do
    kill "$pid" 2>/dev/null
    pkill -P "$pid" 2>/dev/null
  done
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

# ---- 0. Pre-chequeo: Telescope ----
echo -e "${CYAN}>> Verificando Telescope...${NC}"
if ! php artisan migrate:status 2>/dev/null | grep -q "telescope_entries"; then
  echo -e "${YELLOW}   Tablas de Telescope no encontradas. Corriendo migración...${NC}"
  php artisan migrate --force
else
  echo -e "${GREEN}   Telescope OK${NC}"
fi

# Limpieza opcional de entradas viejas (mayores a 24h)
php artisan telescope:prune --hours=24 >/dev/null 2>&1 && \
  echo -e "${CYAN}   Entradas viejas de Telescope limpiadas (>24h)${NC}"

# ---- 1. Laravel serve ----
start_service "laravel-serve" php artisan serve --host=0.0.0.0 --port=8000
sleep 2

# ---- 2. Reverb ----
start_service "reverb" php artisan reverb:start --host=0.0.0.0 --port=8080
sleep 1

# ---- 3. ngrok ----
start_service "ngrok" ngrok http 8000
sleep 4

# ---- 4. Vite / npm ----
start_service "vite" npm run dev
sleep 2

# ---- 5. Set webhook de Telegram ----
if [ -z "$TELEGRAM_TOKEN" ]; then
  echo -e "${RED}>> TELEGRAM_BOT_TOKEN no encontrado en .env, saltando webhook.${NC}"
else
  echo -e "${GREEN}>> Configurando webhook de Telegram...${NC}"
  WEBHOOK_URL="${NGROK_URL}${WEBHOOK_PATH}"
  RESP=$(curl -s -X POST "https://api.telegram.org/bot${TELEGRAM_TOKEN}/setWebhook" \
    -d "url=${WEBHOOK_URL}")
  echo "   Respuesta: $RESP"
fi

echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN} Todos los servicios están corriendo${NC}"
echo -e "${GREEN}========================================${NC}"
echo -e " Panel web:  ${CYAN}http://localhost:8000${NC}"
echo -e " Telescope:  ${CYAN}http://localhost:8000/telescope${NC}"
echo -e " Reverb:     http://localhost:8080"
echo -e " ngrok:      ${NGROK_URL}"
echo -e " Webhook:    ${NGROK_URL}${WEBHOOK_PATH}"
echo -e " Logs:       ${LOG_DIR}/"
echo -e "${YELLOW} Pulsa Ctrl+C para detener todo${NC}\n"

wait
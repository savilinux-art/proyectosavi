#!/bin/bash
# scripts/deploy-prod.sh — Deploy seguro a producción
# Uso: bash scripts/deploy-prod.sh
# Creado: 2026-10-08 (post incidente Q-104/Q-105)

set -e  # abortar si algo falla

PROYECTO_DIR="/var/www/proyectosavi"
DB_NAME="proyectosavi"
DB_USER="root"

cd "$PROYECTO_DIR"

echo "════════════════════════════════════════════════════════════"
echo "  DEPLOY SEGURO — ProyectoSAVI"
echo "  Fecha: $(date +%F\ %H:%M)"
echo "════════════════════════════════════════════════════════════"
echo ""

# ─────────────────────────────────────────────────
# PASO 1 — Ver qué viene
# ─────────────────────────────────────────────────
echo "🔍 PASO 1 — Verificando qué commits vienen..."
git fetch origin
echo ""
git log --name-only --oneline HEAD..origin/main
echo ""

if git log --name-only --oneline HEAD..origin/main | grep -qi "TelegramWebhookController"; then
    echo "⚠️  ¡ATENCIÓN! Un commit toca TelegramWebhookController.php"
    read -p "¿Continuar de todas formas? (y/N) " resp
    [ "$resp" = "y" ] || exit 1
fi

if [ -z "$(git log --oneline HEAD..origin/main)" ]; then
    echo "✅ No hay commits nuevos. Nada que deployar."
    exit 0
fi

read -p "¿Continuar con el deploy? (y/N) " resp
[ "$resp" = "y" ] || { echo "❌ Cancelado."; exit 0; }

# ─────────────────────────────────────────────────
# PASO 2 — Backup
# ─────────────────────────────────────────────────
echo ""
echo "💾 PASO 2 — Backup de BD..."
BACKUP="/tmp/prod-backup-$(date +%F-%H%M).sql"
mysqldump -u "$DB_USER" -p "$DB_NAME" > "$BACKUP"
ls -lh "$BACKUP"
echo "✅ Backup en: $BACKUP"

# ─────────────────────────────────────────────────
# PASO 3 — Pull
# ─────────────────────────────────────────────────
echo ""
echo "📦 PASO 3 — git pull..."
git pull origin main

# ─────────────────────────────────────────────────
# PASO 4 — Migraciones pendientes
# ─────────────────────────────────────────────────
echo ""
echo "📋 PASO 4 — Migraciones pendientes..."
php artisan migrate:status | grep -i pending || echo "  (ninguna pendiente)"

# ─────────────────────────────────────────────────
# PASO 5 — Preview SQL
# ─────────────────────────────────────────────────
echo ""
echo "🔎 PASO 5 — Preview del SQL que va a correr:"
php artisan migrate --pretend
echo ""

read -p "¿Ejecutar migrate --force? (y/N) " resp
if [ "$resp" = "y" ]; then
    php artisan migrate --force
    echo ""
    echo "✅ Migraciones corridas."
    php artisan migrate:status | tail -15
else
    echo "❌ migrate cancelado. Backup en $BACKUP"
    exit 0
fi

# ─────────────────────────────────────────────────
# PASO 6 — Cachés
# ─────────────────────────────────────────────────
echo ""
echo "🧹 PASO 6 — Limpiando cachés..."
php artisan view:clear
php artisan config:clear
php artisan route:clear

echo ""
echo "════════════════════════════════════════════════════════════"
echo "  ✅ DEPLOY COMPLETO"
echo "  Backup: $BACKUP"
echo "════════════════════════════════════════════════════════════"

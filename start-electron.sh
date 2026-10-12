#!/bin/bash
set -euo pipefail

DEV_PHP_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$DEV_PHP_DIR/electron"
PORT=8000
PHP_BIN="/opt/homebrew/opt/php@7.4/bin/php"
LOG_DIR="$DEV_PHP_DIR/logs"
LOG_FILE="$LOG_DIR/electron.log"
PID_FILE="$LOG_DIR/electron.pid"
ROUTER="$PROJECT_DIR/router.php"
BACKGROUND=false
CMD="${1:-}"

if [ "$CMD" = "-d" ] || [ "$CMD" = "--background" ]; then
  BACKGROUND=true
fi

stop_server() {
  if [ -f "$PID_FILE" ]; then
    OLD_PID=$(cat "$PID_FILE" 2>/dev/null || true)
    if [ -n "$OLD_PID" ] && kill -0 "$OLD_PID" 2>/dev/null; then
      kill "$OLD_PID" 2>/dev/null || true
      sleep 1
    fi
    rm -f "$PID_FILE"
  fi
  if lsof -Pi :"$PORT" -sTCP:LISTEN -t >/dev/null 2>&1; then
    lsof -Pi :"$PORT" -sTCP:LISTEN -t | xargs kill 2>/dev/null || true
    sleep 1
  fi
}

if [ "$CMD" = "stop" ]; then
  stop_server
  echo "Servidor detenido."
  exit 0
fi

if [ "$CMD" = "status" ]; then
  if lsof -Pi :"$PORT" -sTCP:LISTEN -t >/dev/null 2>&1; then
    PID=$(lsof -Pi :"$PORT" -sTCP:LISTEN -t | head -1)
    echo "✅ Activo en http://localhost:$PORT (PID $PID)"
    exit 0
  fi
  echo "❌ No hay servidor en el puerto $PORT"
  exit 1
fi

if [ "$CMD" = "restart" ]; then
  stop_server
fi

mkdir -p "$LOG_DIR"

echo "=========================================="
echo "  Cooperativa Electron - arranque local"
echo "=========================================="

if [ ! -x "$PHP_BIN" ]; then
  echo "❌ PHP 7.4 no encontrado en: $PHP_BIN"
  echo "   Instala con: brew install php@7.4"
  exit 1
fi

if ! mysql -u root -e "USE copy_electron;" >/dev/null 2>&1; then
  echo "❌ No se puede conectar a la BD copy_electron"
  echo "   Verifica MySQL: brew services start mysql"
  exit 1
fi

# MySQL 8 activa ONLY_FULL_GROUP_BY; la app legacy no lo soporta (Error 1055)
mysql -u root -e "SET GLOBAL sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';" >/dev/null 2>&1 || true

if lsof -Pi :"$PORT" -sTCP:LISTEN -t >/dev/null 2>&1; then
  PID=$(lsof -Pi :"$PORT" -sTCP:LISTEN -t | head -1)
  echo "✅ Ya hay un servidor en el puerto $PORT (PID $PID)"
  echo "🌐 http://localhost:$PORT"
  exit 0
fi

echo "🚀 Iniciando servidor PHP en puerto $PORT..."
echo "🌐 App:   http://localhost:$PORT"
echo "🔐 Login: http://localhost:$PORT/index.php/cooperativa/login"
echo "📄 Log:   $LOG_FILE"
echo ""

cd "$PROJECT_DIR"

if [ "$BACKGROUND" = true ]; then
  # Desacoplar del terminal (evita SIGHUP al cerrar la sesión)
  nohup "$PHP_BIN" -S "127.0.0.1:$PORT" "$ROUTER" >> "$LOG_FILE" 2>&1 < /dev/null &
  PID=$!
  echo "$PID" > "$PID_FILE"
  disown -h "$PID" 2>/dev/null || disown "$PID" 2>/dev/null || true
  sleep 2
  if kill -0 "$PID" 2>/dev/null && lsof -Pi :"$PORT" -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "✅ Servidor en segundo plano (PID $PID)"
    echo "   PID guardado en: $PID_FILE"
    echo "   Detener: ./start-electron.sh stop"
    exit 0
  fi
  echo "❌ No arrancó. Revisa: $LOG_FILE"
  rm -f "$PID_FILE"
  exit 1
fi

echo "⚠️  NO CIERRES ESTA VENTANA mientras uses la plataforma."
echo "    Para detener: Ctrl+C"
echo "=========================================="
exec "$PHP_BIN" -S "127.0.0.1:$PORT" "$ROUTER" 2>&1 | tee -a "$LOG_FILE"

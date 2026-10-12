#!/bin/bash

# Script para iniciar el servidor PHP del Portal de Usuarios
# Uso: ./start.sh [puerto]

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
PORT=${1:-8002}
PHP_VERSION="8.1"

# Verificar si el puerto está en uso
if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "❌ Error: Puerto $PORT ya está en uso"
    exit 1
fi

# Determinar el comando PHP según la versión
PHP_CMD="php"
if [ "$PHP_VERSION" = "7.4" ]; then
    PHP_CMD="/opt/homebrew/opt/php@7.4/bin/php"
elif [ "$PHP_VERSION" = "8.1" ]; then
    PHP_CMD="php"
fi

# Cambiar al directorio del proyecto
cd "$PROJECT_DIR"

# Iniciar el servidor
echo "🚀 Iniciando Portal de Usuarios..."
echo "📁 Directorio: $PROJECT_DIR"
echo "🌐 Puerto: $PORT"
echo "🐘 PHP: $PHP_VERSION"
echo ""

$PHP_CMD -S localhost:$PORT > /dev/null 2>&1 &
PID=$!

echo "✅ Servidor iniciado correctamente"
echo "🌐 URL: http://localhost:$PORT"
echo "📝 PID: $PID"
echo ""
echo "💡 Para detener el servidor, ejecuta: ./stop.sh"
echo "   O usa: pkill -f 'php -S localhost:$PORT'"


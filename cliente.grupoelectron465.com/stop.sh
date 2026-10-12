#!/bin/bash

# Script para detener el servidor PHP del Portal de Usuarios
# Uso: ./stop.sh [puerto]

PORT=${1:-8002}

echo "🛑 Deteniendo servidor en puerto $PORT..."

# Buscar y detener el proceso
pkill -f "php -S localhost:$PORT" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Servidor detenido correctamente"
else
    echo "⚠️  No se encontró un servidor ejecutándose en el puerto $PORT"
fi


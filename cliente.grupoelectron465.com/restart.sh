#!/bin/bash

# Script para reiniciar el servidor PHP del Portal de Usuarios
# Uso: ./restart.sh [puerto]

PORT=${1:-8002}

echo "🔄 Reiniciando servidor..."

# Detener el servidor actual
./stop.sh $PORT

# Esperar un momento
sleep 1

# Iniciar el servidor nuevamente
./start.sh $PORT


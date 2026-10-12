#!/bin/bash
PORT=8000
pkill -f "php -S 127.0.0.1:$PORT" 2>/dev/null || true
pkill -f "php -S localhost:$PORT" 2>/dev/null || true
echo "🛑 Servidor en puerto $PORT detenido"

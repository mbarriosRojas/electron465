#!/bin/bash

# Script para conectarse rápidamente a la base de datos local
# Uso: ./connect-db.sh

DB_HOST="localhost"
DB_USER="grupoele_admin"
DB_PASS="Za63qj2p"
DB_NAME="grupoele_electron"

echo "🔌 Conectando a la base de datos local..."
echo "📊 Base de datos: $DB_NAME"
echo "👤 Usuario: $DB_USER"
echo ""

# Conectar a MySQL
mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME"


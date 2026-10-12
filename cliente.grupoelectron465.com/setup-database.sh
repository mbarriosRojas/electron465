#!/bin/bash

# Script para crear la tabla usuarios_portal en la base de datos
# Uso: ./setup-database.sh

PROJECT_DIR="/Users/mauriciobarrios/Desarrollo/dev_php/portal-usuarios"
SCHEMA_FILE="$PROJECT_DIR/database/schema.sql"

# Cargar configuración de base de datos desde config.php
# Extraer valores usando grep y sed (método simple)
DB_HOST="localhost"
DB_USER="grupoele_admin"
DB_PASS="Za63qj2p"
DB_NAME="grupoele_electron"

echo "🔧 Configurando base de datos para Portal de Usuarios..."
echo "📊 Base de datos: $DB_NAME"
echo ""

# Verificar si la tabla ya existe
TABLE_EXISTS=$(mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_NAME' AND table_name = 'usuarios_portal';" 2>/dev/null | tail -1)

if [ "$TABLE_EXISTS" = "1" ]; then
    echo "⚠️  La tabla 'usuarios_portal' ya existe."
    read -p "¿Deseas recrearla? (s/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        echo "🗑️  Eliminando tabla existente..."
        mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" -e "DROP TABLE IF EXISTS usuarios_portal;" 2>/dev/null
        echo "✅ Tabla eliminada"
    else
        echo "❌ Operación cancelada"
        exit 0
    fi
fi

# Crear la tabla
echo "📝 Creando tabla 'usuarios_portal'..."
mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" < "$SCHEMA_FILE" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Tabla creada exitosamente"
    echo ""
    echo "📋 Estructura de la tabla:"
    mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" -e "DESCRIBE usuarios_portal;" 2>/dev/null
else
    echo "❌ Error al crear la tabla"
    exit 1
fi


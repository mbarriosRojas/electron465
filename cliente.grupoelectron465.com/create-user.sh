#!/bin/bash

# Script para crear usuarios en usuarios_portal
# Uso: ./create-user.sh [documento_id] [password] [email_opcional]

DB_HOST="localhost"
DB_USER="grupoele_admin"
DB_PASS="Za63qj2p"
DB_NAME="grupoele_electron"

if [ -z "$1" ] || [ -z "$2" ]; then
    echo "❌ Error: Debes proporcionar documento_id y password"
    echo ""
    echo "Uso: $0 [documento_id] [password] [email_opcional]"
    echo ""
    echo "Ejemplo:"
    echo "  $0 19997832 mejl202."
    echo "  $0 19997832 mejl202. usuario@ejemplo.com"
    exit 1
fi

DOCUMENTO_ID="$1"
PASSWORD="$2"
EMAIL_OPCIONAL="$3"

echo "🔧 Creando usuario en usuarios_portal..."
echo "📄 Documento: $DOCUMENTO_ID"
echo ""

# Obtener email desde t_personas si no se proporciona
if [ -z "$EMAIL_OPCIONAL" ]; then
    echo "📧 Buscando email en t_personas..."
    EMAIL=$(mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" -sN -e "SELECT correo FROM t_personas WHERE documento_id = '$DOCUMENTO_ID' LIMIT 1;" 2>/dev/null)
    
    if [ -z "$EMAIL" ]; then
        echo "❌ Error: No se encontró el documento en t_personas"
        echo "💡 Proporciona el email manualmente: $0 $DOCUMENTO_ID $PASSWORD email@ejemplo.com"
        exit 1
    fi
    
    echo "✅ Email encontrado: $EMAIL"
else
    EMAIL="$EMAIL_OPCIONAL"
    echo "📧 Usando email proporcionado: $EMAIL"
fi

# Generar hash de contraseña
echo "🔐 Generando hash de contraseña..."
PASSWORD_HASH=$(php -r "echo password_hash('$PASSWORD', PASSWORD_DEFAULT);")

if [ -z "$PASSWORD_HASH" ]; then
    echo "❌ Error: No se pudo generar el hash de contraseña"
    exit 1
fi

# Verificar si el usuario ya existe
EXISTS=$(mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" -sN -e "SELECT COUNT(*) FROM usuarios_portal WHERE documento_id = '$DOCUMENTO_ID';" 2>/dev/null)

if [ "$EXISTS" = "1" ]; then
    echo "⚠️  El usuario ya existe. Actualizando..."
    mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" << EOF 2>/dev/null
UPDATE usuarios_portal 
SET email = '$EMAIL',
    password = '$PASSWORD_HASH',
    email_verificado = 1,
    updated_at = NOW()
WHERE documento_id = '$DOCUMENTO_ID';
EOF
    echo "✅ Usuario actualizado correctamente"
else
    echo "➕ Creando nuevo usuario..."
    mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" << EOF 2>/dev/null
INSERT INTO usuarios_portal (
    documento_id, 
    email, 
    password, 
    email_verificado, 
    created_at, 
    updated_at
) VALUES (
    '$DOCUMENTO_ID',
    '$EMAIL',
    '$PASSWORD_HASH',
    1,
    NOW(),
    NOW()
);
EOF
    echo "✅ Usuario creado correctamente"
fi

# Mostrar información del usuario
echo ""
echo "📋 Información del usuario:"
mysql -u "$DB_USER" -p"$DB_PASS" -h "$DB_HOST" "$DB_NAME" -e "SELECT id, documento_id, email, email_verificado, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') as fecha_creacion FROM usuarios_portal WHERE documento_id = '$DOCUMENTO_ID';" 2>/dev/null | grep -v Warning

echo ""
echo "✅ Usuario listo para iniciar sesión"
echo "🌐 URL: http://localhost:8002/login"
echo "📄 Documento: $DOCUMENTO_ID"
echo "🔑 Password: $PASSWORD"


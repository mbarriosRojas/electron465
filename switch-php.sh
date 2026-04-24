#!/bin/bash

# Script para cambiar entre versiones de PHP
# Uso: ./switch-php.sh [7.4|8.1|current]

case "$1" in
    "7.4")
        echo "🔄 Cambiando a PHP 7.4..."
        export PATH="/opt/homebrew/opt/php@7.4/bin:$PATH"
        export PATH="/opt/homebrew/opt/php@7.4/sbin:$PATH"
        echo "✅ PHP 7.4 activado"
        /opt/homebrew/opt/php@7.4/bin/php --version
        ;;
    "8.1")
        echo "🔄 Cambiando a PHP 8.1..."
        # Remover PHP 7.4 del PATH y usar el por defecto
        export PATH=$(echo $PATH | sed 's|/opt/homebrew/opt/php@7.4/bin:||g')
        export PATH=$(echo $PATH | sed 's|/opt/homebrew/opt/php@7.4/sbin:||g')
        echo "✅ PHP 8.1 activado"
        php --version
        ;;
    "current")
        echo "📋 Versión actual de PHP:"
        php --version
        ;;
    *)
        echo "🔧 Cambiador de versiones PHP"
        echo "============================="
        echo ""
        echo "Uso: $0 [7.4|8.1|current]"
        echo ""
        echo "Versiones disponibles:"
        echo "  PHP 7.4: /opt/homebrew/opt/php@7.4/bin/php --version"
        echo "  PHP 8.1: php --version"
        echo ""
        echo "💡 Para hacer el cambio permanente, agrega al ~/.zshrc:"
        echo "   echo 'export PATH=\"/opt/homebrew/opt/php@7.4/bin:\$PATH\"' >> ~/.zshrc"
        ;;
esac

#!/bin/bash

# Gestor de proyectos PHP
# Uso: ./php-manager.sh [start|stop|list|phpmyadmin] [nombre_proyecto] [puerto]

DEV_PHP_DIR="/Users/mauriciobarrios/Desarrollo/dev_php"
CONFIG_FILE="$DEV_PHP_DIR/php-projects.conf"

# Función para crear configuración inicial
create_config() {
    if [ ! -f "$CONFIG_FILE" ]; then
        cat > "$CONFIG_FILE" << EOF
# Configuración de proyectos PHP
# Formato: nombre_proyecto|puerto|php_version|estado
plataforma|8000|7.4|stopped
phpmyadmin|8081|7.4|running
EOF
    fi
}

# Función para verificar si phpMyAdmin está corriendo
check_phpmyadmin() {
    local phpmyadmin_port=8081
    if ! lsof -Pi :$phpmyadmin_port -sTCP:LISTEN -t >/dev/null 2>&1; then
        echo "📊 phpMyAdmin no está corriendo. Iniciándolo automáticamente..."
        start_phpmyadmin $phpmyadmin_port
        sleep 1
    fi
}

# Función para iniciar un proyecto
start_project() {
    local project_name=$1
    local port=$2
    local auto_phpmyadmin=${3:-true}
    
    if [ -z "$project_name" ]; then
        echo "❌ Error: Debes especificar el nombre del proyecto"
        echo ""
        echo "💡 Proyectos disponibles:"
        get_available_projects
        return 1
    fi
    
    # Verificar si el proyecto existe
    if [ ! -d "$DEV_PHP_DIR/$project_name" ]; then
        echo "❌ Error: Proyecto '$project_name' no encontrado en $DEV_PHP_DIR"
        echo ""
        echo "💡 Proyectos disponibles:"
        get_available_projects
        return 1
    fi
    
    # Buscar configuración del proyecto
    local project_info=$(grep "^$project_name|" "$CONFIG_FILE" 2>/dev/null)
    
    if [ -z "$project_info" ]; then
        # Si no existe configuración, usar valores por defecto
        local default_port=${2:-8000}
        local php_version="7.4"
        echo "$project_name|$default_port|$php_version|stopped" >> "$CONFIG_FILE"
        project_info="$project_name|$default_port|$php_version|stopped"
    fi
    
    IFS='|' read -r name port php_version status <<< "$project_info"
    
    # Verificar si el puerto está en uso
    if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null 2>&1; then
        echo "❌ Error: Puerto $port ya está en uso"
        return 1
    fi
    
    # Determinar la versión de PHP
    local php_cmd
    if [ "$php_version" = "7.4" ]; then
        php_cmd="/opt/homebrew/opt/php@7.4/bin/php"
    elif [ "$php_version" = "8.1" ]; then
        php_cmd="php"
    else
        php_cmd="php"
    fi
    
    # Iniciar phpMyAdmin automáticamente si está habilitado
    if [ "$auto_phpmyadmin" = "true" ]; then
        check_phpmyadmin
    fi
    
    # Iniciar el servidor
    echo "🚀 Iniciando proyecto '$name' en puerto $port con PHP $php_version..."
    cd "$DEV_PHP_DIR/$project_name" && $php_cmd -S localhost:$port > /dev/null 2>&1 &
    local pid=$!
    
    # Actualizar estado en configuración
    sed -i.bak "s/^$name|.*|$/$name|$port|$php_version|running/" "$CONFIG_FILE"
    
    echo "✅ Proyecto '$name' iniciado correctamente"
    echo "🌐 URL: http://localhost:$port"
    echo "📝 PID: $pid"
}

# Función para detener un proyecto
stop_project() {
    local project_name=$1
    
    if [ -z "$project_name" ]; then
        echo "❌ Error: Debes especificar el nombre del proyecto"
        return 1
    fi
    
    # Buscar configuración del proyecto
    local project_info=$(grep "^$project_name|" "$CONFIG_FILE" 2>/dev/null)
    
    if [ -z "$project_info" ]; then
        echo "❌ Error: Proyecto '$project_name' no encontrado en configuración"
        return 1
    fi
    
    IFS='|' read -r name port php_version status <<< "$project_info"
    
    # Detener el proceso
    pkill -f "php -S localhost:$port" 2>/dev/null
    
    # Actualizar estado en configuración
    sed -i.bak "s/^$name|.*|$/$name|$port|$php_version|stopped/" "$CONFIG_FILE"
    
    echo "🛑 Proyecto '$name' detenido"
}

# Función para obtener proyectos disponibles
get_available_projects() {
    for item in "$DEV_PHP_DIR"/*; do
        if [ -d "$item" ]; then
            local project_name=$(basename "$item")
            # Excluir archivos de configuración y scripts
            if [[ ! "$project_name" =~ ^(php-manager|php-projects|switch-php|README|\.git) ]]; then
                echo "  - $project_name"
            fi
        fi
    done
}

# Función para listar proyectos
list_projects() {
    echo "📋 Proyectos PHP en $DEV_PHP_DIR:"
    echo "================================"
    printf "%-20s %-8s %-6s %-10s\n" "PROYECTO" "PUERTO" "PHP" "ESTADO"
    echo "----------------------------------------"
    
    # Listar proyectos del directorio
    for item in "$DEV_PHP_DIR"/*; do
        if [ -d "$item" ]; then
            local project=$(basename "$item")
            # Excluir archivos de configuración y scripts
            if [[ ! "$project" =~ ^(php-manager|php-projects|switch-php|README|\.git) ]]; then
                local project_info=$(grep "^$project|" "$CONFIG_FILE" 2>/dev/null)
                if [ ! -z "$project_info" ]; then
                    IFS='|' read -r name port php_version status <<< "$project_info"
                    printf "%-20s %-8s %-6s %-10s\n" "$name" "$port" "$php_version" "$status"
                else
                    printf "%-20s %-8s %-6s %-10s\n" "$project" "8000" "7.4" "stopped"
                fi
            fi
        fi
    done
    
    # Mostrar phpMyAdmin si está configurado
    local phpmyadmin_info=$(grep "^phpmyadmin|" "$CONFIG_FILE" 2>/dev/null)
    if [ ! -z "$phpmyadmin_info" ]; then
        IFS='|' read -r name port php_version status <<< "$phpmyadmin_info"
        printf "%-20s %-8s %-6s %-10s\n" "$name" "$port" "$php_version" "$status"
    fi
}

# Función para iniciar phpMyAdmin
start_phpmyadmin() {
    local port=${1:-8081}
    
    # Verificar si el puerto está en uso
    if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null 2>&1; then
        echo "❌ Error: Puerto $port ya está en uso"
        return 1
    fi
    
    echo "🚀 Iniciando phpMyAdmin en puerto $port..."
    cd /opt/homebrew/share/phpmyadmin && /opt/homebrew/opt/php@7.4/bin/php -S localhost:$port > /dev/null 2>&1 &
    local pid=$!
    
    echo "✅ phpMyAdmin iniciado correctamente"
    echo "🌐 URL: http://localhost:$port"
    echo "👤 Usuario: grupoele_admin"
    echo "🔑 Contraseña: Za63qj2p"
    echo "📝 PID: $pid"
}

# Función para mostrar ayuda
show_help() {
    echo "🔧 Gestor de Proyectos PHP"
    echo "=========================="
    echo ""
    echo "Uso: $0 [comando] [argumentos]"
    echo ""
    echo "Comandos disponibles:"
    echo "  start [nombre] [puerto]     - Iniciar un proyecto (phpMyAdmin se inicia automáticamente)"
    echo "  stop [nombre]               - Detener un proyecto"
    echo "  list                        - Listar todos los proyectos disponibles"
    echo "  phpmyadmin [puerto]         - Iniciar phpMyAdmin manualmente (puerto por defecto: 8081)"
    echo "  help                        - Mostrar esta ayuda"
    echo ""
    echo "Ejemplos:"
    echo "  $0 start portal-usuarios"
    echo "  $0 start portal-usuarios 8002"
    echo "  $0 start respaldo_electron"
    echo "  $0 stop portal-usuarios"
    echo "  $0 list"
    echo "  $0 phpmyadmin 8082"
    echo ""
    echo "📁 Proyectos se encuentran en: $DEV_PHP_DIR"
    echo ""
    echo "💡 Nota: phpMyAdmin se inicia automáticamente al iniciar cualquier proyecto"
}

# Crear configuración si no existe
create_config

# Procesar comandos
case "$1" in
    "start")
        start_project "$2" "$3" "true"
        ;;
    "stop")
        stop_project "$2"
        ;;
    "list")
        list_projects
        ;;
    "phpmyadmin")
        start_phpmyadmin "$2"
        ;;
    "help"|"--help"|"-h"|"")
        show_help
        ;;
    *)
        echo "❌ Comando desconocido: $1"
        show_help
        exit 1
        ;;
esac

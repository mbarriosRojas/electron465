#!/bin/bash

# Gestor de proyectos PHP
# Uso: ./php-manager.sh [start|stop|list|phpmyadmin] [nombre_proyecto] [puerto]

DEV_PHP_DIR="$(cd "$(dirname "$0")" && pwd)"
CONFIG_FILE="$DEV_PHP_DIR/php-projects.conf"
EXCLUDED_DIRS='^(docs|logs|php-manager|php-projects|switch-php|README|\.git)'

is_port_listening() {
    lsof -Pi :"$1" -sTCP:LISTEN -t >/dev/null 2>&1
}

wait_for_port() {
    local port=$1
    local retries=${2:-10}
    local i=0
    while [ $i -lt $retries ]; do
        if is_port_listening "$port"; then
            return 0
        fi
        sleep 0.5
        i=$((i + 1))
    done
    return 1
}

set_project_status() {
    local name=$1
    local port=$2
    local php_version=$3
    local status=$4
    if grep -q "^$name|" "$CONFIG_FILE" 2>/dev/null; then
        sed -i.bak "s/^$name|.*|$/$name|$port|$php_version|$status/" "$CONFIG_FILE"
    fi
}

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
    if ! is_port_listening "$phpmyadmin_port"; then
        echo "📊 phpMyAdmin no está corriendo. Iniciándolo automáticamente..."
        start_phpmyadmin "$phpmyadmin_port" || return 1
    fi
}

# Función para iniciar un proyecto
start_project() {
    local project_name=$1
    local port_override=$2
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
    
    IFS='|' read -r name config_port php_version status <<< "$project_info"
    local port="${port_override:-$config_port}"

    if is_port_listening "$port"; then
        echo "✅ Puerto $port ya está activo"
        echo "🌐 URL: http://localhost:$port"
        set_project_status "$name" "$port" "$php_version" "running"
        return 0
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

    if [ ! -x "$php_cmd" ] && ! command -v "$php_cmd" >/dev/null 2>&1; then
        echo "❌ Error: PHP no encontrado ($php_cmd). Ejecuta: ./switch-php.sh $php_version"
        return 1
    fi
    
    # Iniciar phpMyAdmin automáticamente si está habilitado
    if [ "$auto_phpmyadmin" = "true" ]; then
        check_phpmyadmin
    fi
    
    # Iniciar el servidor (nohup para que sobreviva al cierre de terminal)
    mkdir -p "$DEV_PHP_DIR/logs"
    local log_file="$DEV_PHP_DIR/logs/${name}.log"
    echo "🚀 Iniciando proyecto '$name' en puerto $port con PHP $php_version..."
    local router_script="$DEV_PHP_DIR/$project_name/router.php"
    local serve_target="-t ."
    if [ -f "$router_script" ]; then
        serve_target="$router_script"
    fi
    cd "$DEV_PHP_DIR/$project_name" && nohup $php_cmd -S "127.0.0.1:$port" $serve_target >> "$log_file" 2>&1 &
    local pid=$!
    disown $pid 2>/dev/null || true

    if ! wait_for_port "$port"; then
        echo "❌ Error: el servidor no respondió en el puerto $port"
        echo "📄 Revisa el log: $log_file"
        tail -5 "$log_file" 2>/dev/null
        kill "$pid" 2>/dev/null
        set_project_status "$name" "$port" "$php_version" "stopped"
        return 1
    fi

    set_project_status "$name" "$port" "$php_version" "running"
    
    echo "✅ Proyecto '$name' iniciado correctamente"
    echo "🌐 URL: http://localhost:$port"
    echo "📝 PID: $pid"
    echo "📄 Log: $log_file"
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
    
    pkill -f "php -S .*:$port" 2>/dev/null
    set_project_status "$name" "$port" "$php_version" "stopped"
    
    echo "🛑 Proyecto '$name' detenido"
}

# Función para obtener proyectos disponibles
get_available_projects() {
    for item in "$DEV_PHP_DIR"/*; do
        if [ -d "$item" ]; then
            local project_name=$(basename "$item")
            # Excluir archivos de configuración y scripts
            if [[ ! "$project_name" =~ $EXCLUDED_DIRS ]]; then
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
            if [[ ! "$project" =~ $EXCLUDED_DIRS ]]; then
                local project_info=$(grep "^$project|" "$CONFIG_FILE" 2>/dev/null)
                if [ ! -z "$project_info" ]; then
                    IFS='|' read -r name port php_version status <<< "$project_info"
                    if is_port_listening "$port"; then
                        status="running"
                    elif [ "$status" = "running" ]; then
                        status="stopped"
                        set_project_status "$name" "$port" "$php_version" "stopped"
                    fi
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
        if is_port_listening "$port"; then
            status="running"
        elif [ "$status" = "running" ]; then
            status="stopped"
            set_project_status "$name" "$port" "$php_version" "stopped"
        fi
        printf "%-20s %-8s %-6s %-10s\n" "$name" "$port" "$php_version" "$status"
    fi
}

show_status() {
    echo "🔍 Estado de puertos locales:"
    echo "=============================="
    for port in 8000 8081; do
        if is_port_listening "$port"; then
            local pid
            pid=$(lsof -Pi :"$port" -sTCP:LISTEN -t 2>/dev/null | head -1)
            echo "✅ Puerto $port: ACTIVO (PID $pid) → http://localhost:$port"
        else
            echo "❌ Puerto $port: inactivo"
        fi
    done
    echo ""
    echo "💡 phpMyAdmin usa el puerto 8081 (no 8001)."
}

# Función para iniciar phpMyAdmin
start_phpmyadmin() {
    local port=${1:-8081}
    
    # Verificar si el puerto está en uso
    if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null 2>&1; then
        echo "❌ Error: Puerto $port ya está en uso"
        return 1
    fi
    
    mkdir -p "$DEV_PHP_DIR/logs"
    echo "🚀 Iniciando phpMyAdmin en puerto $port..."
    cd /opt/homebrew/share/phpmyadmin && nohup /opt/homebrew/opt/php@7.4/bin/php -S "127.0.0.1:$port" >> "$DEV_PHP_DIR/logs/phpmyadmin.log" 2>&1 &
    local pid=$!
    disown $pid 2>/dev/null || true

    if ! wait_for_port "$port"; then
        echo "❌ Error: phpMyAdmin no respondió en el puerto $port"
        tail -5 "$DEV_PHP_DIR/logs/phpmyadmin.log" 2>/dev/null
        kill "$pid" 2>/dev/null
        return 1
    fi

    set_project_status "phpmyadmin" "$port" "7.4" "running"
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
    echo "  status                      - Verificar puertos 8000 y 8081"
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
    echo "💡 Cooperativa Electron: ./php-manager.sh start electron  → http://localhost:8000"
    echo "💡 phpMyAdmin (BD local):  ./php-manager.sh phpmyadmin   → http://localhost:8081"
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
    "status")
        show_status
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

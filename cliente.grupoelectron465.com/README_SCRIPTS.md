# Scripts de Gestión - Portal de Usuarios

Scripts para gestionar el servidor de desarrollo del Portal de Usuarios.

## 📋 Scripts Disponibles

### `start.sh` - Iniciar el servidor
Inicia el servidor PHP en el puerto 8002 (o el que especifiques).

```bash
./start.sh          # Inicia en puerto 8002 (por defecto)
./start.sh 8003     # Inicia en puerto 8003
```

### `stop.sh` - Detener el servidor
Detiene el servidor PHP que está ejecutándose.

```bash
./stop.sh           # Detiene el servidor en puerto 8002
./stop.sh 8003      # Detiene el servidor en puerto 8003
```

### `restart.sh` - Reiniciar el servidor
Reinicia el servidor PHP.

```bash
./restart.sh        # Reinicia el servidor en puerto 8002
./restart.sh 8003   # Reinicia el servidor en puerto 8003
```

## 🔧 Gestión con php-manager.sh

También puedes usar el gestor general de proyectos PHP:

```bash
# Desde el directorio raíz dev_php
cd /Users/mauriciobarrios/Desarrollo/dev_php

# Iniciar portal-usuarios
./php-manager.sh start portal-usuarios

# Detener portal-usuarios
./php-manager.sh stop portal-usuarios

# Listar todos los proyectos
./php-manager.sh list
```

## 🌐 Acceso

Una vez iniciado, el proyecto estará disponible en:
- **URL:** http://localhost:8002
- **Login:** http://localhost:8002/login
- **Registro:** http://localhost:8002/register

## 📝 Notas

- El proyecto usa **PHP 8.1** por defecto
- El puerto por defecto es **8002** (para evitar conflictos con otros proyectos)
- Los scripts verifican automáticamente si el puerto está en uso antes de iniciar

## 🐛 Solución de Problemas

### Puerto en uso
Si el puerto está ocupado, puedes:
1. Usar otro puerto: `./start.sh 8003`
2. Detener el proceso que usa el puerto: `./stop.sh`

### Verificar si el servidor está corriendo
```bash
ps aux | grep "php -S localhost:8002"
```

### Detener manualmente
```bash
pkill -f "php -S localhost:8002"
```


# 🚀 Dev PHP - Gestor de Proyectos PHP

Este directorio contiene todos tus proyectos PHP y las herramientas para gestionarlos fácilmente.

## 📁 Estructura

```
/Users/mauriciobarrios/Desarrollo/dev_php/
├── plataforma/          # Tu proyecto CodeIgniter
├── php-manager.sh       # Gestor de proyectos PHP
├── switch-php.sh        # Cambiador de versiones PHP
├── php-projects.conf    # Configuración automática
└── README.md           # Este archivo
```

## 🛠️ Comandos disponibles

### Gestión de proyectos
```bash
# Iniciar un proyecto
./php-manager.sh start plataforma

# Iniciar con puerto específico
./php-manager.sh start plataforma 8001

# Detener un proyecto
./php-manager.sh stop plataforma

# Listar todos los proyectos
./php-manager.sh list

# Iniciar phpMyAdmin
./php-manager.sh phpmyadmin
```

### Cambio de versiones PHP
```bash
# Cambiar a PHP 7.4
./switch-php.sh 7.4

# Cambiar a PHP 8.1
./switch-php.sh 8.1

# Ver versión actual
./switch-php.sh current
```

## 🌐 Accesos

- **Tu plataforma:** `http://localhost:8000` (PHP 7.4)
- **phpMyAdmin:** `http://localhost:8081` (PHP 7.4)
  - Usuario: `grupoele_admin`
  - Contraseña: `Za63qj2p`

## 🚀 Arranque rápido (respaldo_electron)

1) Cambiar a PHP 7.4 (recomendado para CodeIgniter antiguo)
```bash
./switch-php.sh 7.4 && ./switch-php.sh current
```

2) Iniciar el proyecto en el puerto 8000
```bash
pkill -f "php -S localhost:8000 -t ." || true
cd /Users/mauriciobarrios/Desarrollo/dev_php/respaldo_electron
/opt/homebrew/opt/php@7.4/bin/php -S localhost:8000 -t .
```

3) Abrir en el navegador
- `http://localhost:8000`
- Reportes > Reportes Especiales

## 🔁 Reinicio completo (cuando algo queda colgado)
```bash
# Cerrar servidor anterior
pkill -f "php -S localhost:8000 -t ." || true

# Desbloquear puerto (opcional)
lsof -i :8000 | awk 'NR>1{print $2}' | xargs -r kill -9

# Forzar el modo SQL para desactivar ONLY_FULL_GROUP_BY
mysql -u grupoele_admin -pZa63qj2p -e \
  "SET GLOBAL sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';"

# Cambiar a PHP 7.4 e iniciar
./switch-php.sh 7.4
cd /Users/mauriciobarrios/Desarrollo/dev_php/respaldo_electron
nohup /opt/homebrew/opt/php@7.4/bin/php -S localhost:8000 -t . >/tmp/php_server_8000.log 2>&1 &
```

## 🧩 Configuración que debe permanecer

- `respaldo_electron/system/application/config/database.php`
  - host: `localhost` para conexiones locales
- `respaldo_electron/system/application/config/config.php`
  - `base_url`: `http://localhost:8000`
  - `base_url2`: `http://localhost:8000/`
  - sesiones: `sess_use_database = FALSE`, `sess_match_ip = FALSE`, `sess_match_useragent = FALSE`
- JS globales (`system/js/Global.js`, `system/js/general/Global.js`, etc.)
  - `sUrl` debe apuntar a `http://` y puerto `:8000`

## 🧪 Verificación rápida

1) Login sin warnings de headers/sessions
2) Abrir `http://localhost:8000/index.php/cooperativa/reportes`
3) Icono “Reportes Especiales” abre modal y carga bancos/nominas
4) Generar reporte y Exportar a Excel

## 🛠️ Solución de problemas

### Error "ERROR AL CARGAR BANCOS" en Reportes Especiales

Si al abrir el modal de Reportes Especiales aparece "ERROR AL CARGAR BANCOS":

**En servidor (problema de caché PHP/OPcache):**
```bash
# Limpiar caché de OPcache
php -r "opcache_reset();"

# O reiniciar PHP-FPM
sudo systemctl restart php-fpm
# o
sudo service php7.4-fpm restart
```

**El sistema ya tiene protección automática:**
- Sistema de reintentos (3 intentos)
- Bancos por defecto en caso de error
- Headers anti-caché
- Logs detallados en consola (F12)

**Verificación en consola del navegador:**
- `Conectividad OK` → El servidor responde
- `Debug tablas` → Las tablas existen
- `Bancos cargados correctamente` → Los bancos se cargaron

### Otros problemas

- "Expression #1 of SELECT list… ONLY_FULL_GROUP_BY"
  - Ejecutar el comando de SQL en la sección "Reinicio completo".

- "Array to string conversion" o "Cannot modify header information" en login
  - Ya está corregido en `system/libraries/Session.php`. Si aparece, reinicia servidor y limpia caché del navegador (Cmd+Shift+R).

- No abre Reportes Especiales / URLs a dominio producción
  - Verificar `sUrl` en los JS globales apunte a `http://localhost:8000`.

- Ver log del servidor embebido
```bash
tail -n 200 /tmp/php_server_8000.log
```

## 📦 phpMyAdmin

Si tienes el directorio `phpmyadmin` en la raíz de este workspace:
```bash
cd /Users/mauriciobarrios/Desarrollo/dev_php/phpmyadmin
/opt/homebrew/opt/php@7.4/bin/php -S localhost:8081 -t .
```

## 📝 Agregar nuevos proyectos

1. Copia tu proyecto a `/Users/mauriciobarrios/Desarrollo/dev_php/nombre-proyecto`
2. Ejecuta: `./php-manager.sh start nombre-proyecto`
3. ¡Listo! El sistema detecta automáticamente el proyecto

## ✅ Ventajas del sistema

- ✅ **Sin conflictos de puertos** - Cada proyecto tiene su puerto
- ✅ **Fácil cambio de versiones** - PHP 7.4 y 8.1 disponibles
- ✅ **Gestión automática** - No necesitas recordar comandos complejos
- ✅ **phpMyAdmin separado** - Puerto 8081, sin conflictos
- ✅ **Configuración persistente** - Recuerda tus proyectos

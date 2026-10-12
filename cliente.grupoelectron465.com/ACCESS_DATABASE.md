# 🔌 Acceso a la Base de Datos Local

Guía para acceder a la base de datos `grupoele_electron` de forma local.

## 📋 Credenciales

- **Host:** `localhost`
- **Usuario:** `grupoele_admin`
- **Contraseña:** `Za63qj2p`
- **Base de datos:** `grupoele_electron`

---

## 🚀 Métodos de Acceso

### 1. Línea de Comandos (MySQL CLI)

#### Opción A: Script rápido
```bash
cd portal-usuarios
./connect-db.sh
```

#### Opción B: Comando directo
```bash
mysql -u grupoele_admin -p'Za63qj2p' -h localhost grupoele_electron
```

#### Opción C: Sin contraseña en comando (más seguro)
```bash
mysql -u grupoele_admin -p -h localhost grupoele_electron
# Te pedirá la contraseña: Za63qj2p
```

### 2. phpMyAdmin (Interfaz Web)

#### Iniciar phpMyAdmin
```bash
cd /Users/mauriciobarrios/Desarrollo/dev_php
./php-manager.sh phpmyadmin
```

#### Acceder
- **URL:** http://localhost:8081
- **Usuario:** `grupoele_admin`
- **Contraseña:** `Za63qj2p`

### 3. Herramientas Gráficas

#### MySQL Workbench
1. Descargar desde: https://dev.mysql.com/downloads/workbench/
2. Crear nueva conexión:
   - **Connection Name:** Portal Usuarios Local
   - **Hostname:** `localhost`
   - **Port:** `3306`
   - **Username:** `grupoele_admin`
   - **Password:** `Za63qj2p`
   - **Default Schema:** `grupoele_electron`

#### TablePlus (macOS)
1. Descargar desde: https://tableplus.com/
2. Crear nueva conexión MySQL:
   - **Name:** Portal Usuarios Local
   - **Host:** `localhost`
   - **Port:** `3306`
   - **User:** `grupoele_admin`
   - **Password:** `Za63qj2p`
   - **Database:** `grupoele_electron`

#### DBeaver (Multiplataforma)
1. Descargar desde: https://dbeaver.io/
2. Crear nueva conexión MySQL:
   - **Server Host:** `localhost`
   - **Port:** `3306`
   - **Database:** `grupoele_electron`
   - **Username:** `grupoele_admin`
   - **Password:** `Za63qj2p`

---

## 📝 Comandos Útiles en MySQL CLI

Una vez conectado, puedes usar:

```sql
-- Ver todas las tablas
SHOW TABLES;

-- Ver estructura de una tabla
DESCRIBE usuarios_portal;
-- o
SHOW CREATE TABLE usuarios_portal;

-- Ver datos de una tabla
SELECT * FROM usuarios_portal;

-- Contar registros
SELECT COUNT(*) FROM usuarios_portal;

-- Ver bases de datos disponibles
SHOW DATABASES;

-- Cambiar de base de datos
USE grupoele_electron;

-- Salir
EXIT;
-- o
\q
```

---

## 🔍 Consultas Útiles para Portal de Usuarios

```sql
-- Ver todos los usuarios registrados
SELECT id, documento_id, email, email_verificado, created_at 
FROM usuarios_portal;

-- Ver usuarios no verificados
SELECT * FROM usuarios_portal WHERE email_verificado = 0;

-- Ver usuarios verificados
SELECT * FROM usuarios_portal WHERE email_verificado = 1;

-- Buscar usuario por documento
SELECT * FROM usuarios_portal WHERE documento_id = '19997832';

-- Ver correos de clientes en t_personas
SELECT documento_id, correo, primer_nombre, primer_apellido 
FROM t_personas 
WHERE documento_id = '19997832';
```

---

## ⚠️ Notas Importantes

1. **Seguridad:** No compartas estas credenciales
2. **Producción vs Local:** Estas credenciales son para desarrollo local
3. **Backup:** Siempre haz backup antes de hacer cambios importantes
4. **Permisos:** Asegúrate de tener los permisos necesarios para la base de datos

---

## 🛠️ Solución de Problemas

### Error: "Access denied"
- Verifica que las credenciales sean correctas
- Verifica que MySQL esté corriendo: `brew services list | grep mysql`

### Error: "Can't connect to MySQL server"
- Verifica que MySQL esté corriendo: `brew services start mysql`
- Verifica el puerto: por defecto es `3306`

### Ver estado de MySQL
```bash
brew services list | grep mysql
```

### Iniciar MySQL
```bash
brew services start mysql
```

### Detener MySQL
```bash
brew services stop mysql
```


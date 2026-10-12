# Portal de Usuarios - Grupo Electrón

Sistema completo PHP unificado (Backend + Frontend) para consulta de facturas y pagos por parte de usuarios.

## 🚀 Características

✅ **Sistema unificado en PHP** - Todo en un solo proyecto  
✅ Registro solo con documento de identidad  
✅ Validación por email con código de 6 dígitos  
✅ Autenticación segura con sesiones PHP  
✅ Consulta de facturas activas  
✅ Detalle de pagos por contrato  
✅ Interfaz moderna y responsiva  

## 📁 Estructura

```
portal-usuarios/
├── index.php              # Punto de entrada (routing)
├── .htaccess             # Configuración Apache
├── config/
│   └── config.php        # Configuración de la aplicación
├── classes/              # Clases PHP
│   ├── Database.php
│   ├── Auth.php
│   ├── Usuario.php
│   ├── Factura.php
│   └── EmailService.php
├── api/                  # Endpoints API REST
│   └── index.php
├── views/                # Vistas PHP
│   ├── layout.php
│   ├── login.php
│   ├── register.php
│   ├── verify-code.php
│   ├── dashboard.php
│   ├── factura.php
│   ├── logout.php
│   └── 404.php
├── assets/               # CSS, JS, imágenes
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
└── database/
    └── schema.sql       # Script de base de datos
```

## 📦 Instalación

### 1. Subir archivos

Subir todos los archivos de la carpeta `portal-usuarios/` al subdominio/directorio deseado en tu servidor.

### 2. Crear tabla en base de datos

Ejecutar el script SQL:

```sql
CREATE TABLE IF NOT EXISTS `usuarios_portal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_id` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `codigo_verificacion` varchar(255) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL,
  `email_verificado` tinyint(1) DEFAULT 0,
  `ultimo_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documento_id` (`documento_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### 3. Configurar

Editar `config/config.php`:

- ✅ Credenciales de base de datos (ya configuradas)
- ⚠️ **Cambiar `JWT_SECRET`** por una clave secreta fuerte
- ✅ Verificar configuración de email

### 4. Configurar Apache

Asegúrate de tener habilitado `mod_rewrite`:

```apache
<Directory "/ruta/a/portal-usuarios">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### 5. Permisos

```bash
chmod 755 portal-usuarios
chmod 644 portal-usuarios/.htaccess
```

## 🎯 Uso

1. **Acceder:** `https://tudominio.com/portal-usuarios/`
2. **Registro:** Solo requiere documento de identidad
3. **Verificación:** Se envía código por email
4. **Login:** Documento + contraseña
5. **Dashboard:** Ver facturas activas
6. **Detalle:** Click en factura para ver contratos y pagos

## 🔐 Seguridad

- Contraseñas encriptadas con `password_hash()`
- Sesiones PHP seguras
- Validación de documentos y permisos
- Sanitización de inputs
- CORS configurado

## 📧 Configuración de Email

El sistema usa la función `mail()` de PHP. Para producción, se recomienda:

1. Configurar PHPMailer con SMTP
2. O usar un servicio de email profesional

Las credenciales están en `config/config.php`:
- `SMTP_USER`
- `SMTP_PASS`
- `SMTP_FROM`

## 🔄 Endpoints API

- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/register` - Registrar usuario
- `POST /api/auth/verify-code` - Verificar código
- `POST /api/auth/resend-code` - Reenviar código
- `GET /api/facturas/activas` - Obtener facturas activas
- `GET /api/facturas/contratos?factura=XXX` - Obtener contratos
- `POST /api/pagos/detalle` - Detalle de pagos

## 🐛 Solución de Problemas

### Error 404
- Verificar que `mod_rewrite` esté habilitado
- Verificar permisos del archivo `.htaccess`

### Error de conexión a BD
- Verificar credenciales en `config/config.php`
- Verificar que la base de datos existe

### Emails no se envían
- Verificar función `mail()` habilitada en PHP
- Verificar configuración SMTP
- Revisar logs del servidor

## 📝 Notas

- El sistema usa la misma base de datos existente (`grupoele_electron`)
- Solo agrega la tabla `usuarios_portal`
- Todas las consultas están basadas en los métodos originales del sistema

## ✨ Próximas Mejoras

- [ ] Implementar PHPMailer para emails más robustos
- [ ] Agregar recuperación de contraseña
- [ ] Exportar estados de cuenta en PDF
- [ ] Notificaciones push
- [ ] Filtros y búsqueda avanzada

---

**Desarrollado para Grupo Electrón 465** 🚀


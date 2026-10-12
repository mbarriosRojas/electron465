# 📧 Configuración de Email - Portal de Usuarios

## 🔧 Problema: Los correos no llegan

El sistema ahora usa **SMTP directo** para enviar correos. Si los correos no llegan, sigue estos pasos:

---

## ✅ Solución para Gmail

### 1. Verificar credenciales en `config/config.php`

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'soporteelectron465@gmail.com');
define('SMTP_PASS', 'soporte8759');  // ⚠️ Verificar esta contraseña
```

### 2. Si tienes 2FA activado en Gmail

Gmail requiere usar **"App Passwords"** (Contraseñas de aplicación) en lugar de tu contraseña normal:

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. Seguridad → Verificación en 2 pasos
3. Al final de la página: **Contraseñas de aplicaciones**
4. Genera una nueva contraseña para "Correo" y "Otro (personalizado)" → "Portal Usuarios"
5. Copia la contraseña generada (16 caracteres sin espacios)
6. Reemplaza `SMTP_PASS` en `config/config.php` con esta contraseña

### 3. Si NO tienes 2FA activado

1. Ve a: https://myaccount.google.com/lesssecureapps
2. Habilita "Permitir el acceso de aplicaciones menos seguras" (si está disponible)
3. O mejor: activa 2FA y usa App Passwords (más seguro)

---

## 🧪 Probar el envío de correos

### Opción 1: Script de prueba

```bash
cd portal-usuarios
# Edita test-email.php y cambia el email de prueba
php test-email.php
```

### Opción 2: Probar desde el portal

1. Ve a http://localhost:8002/register
2. Ingresa un documento de identidad válido
3. Revisa si llega el correo (y la carpeta de spam)

---

## 🔍 Verificar logs de errores

Los errores se registran en los logs de PHP. Para verlos:

```bash
# Ver logs de PHP (depende de tu configuración)
tail -f /var/log/php_errors.log
# o
tail -f /opt/homebrew/var/log/php-fpm.log
```

O revisa los logs del servidor PHP:
```bash
# Si usas el servidor built-in, los errores aparecen en la consola
```

---

## 🛠️ Solución de Problemas

### Error: "No se pudo conectar a smtp.gmail.com"

- Verifica tu conexión a internet
- Verifica que el puerto 587 no esté bloqueado por firewall
- Prueba con el puerto 465 (SSL) cambiando `SMTP_PORT` a 465

### Error: "Autenticación falló"

- Verifica que el usuario y contraseña sean correctos
- Si usas 2FA, asegúrate de usar una App Password
- Verifica que "Permitir acceso de aplicaciones menos seguras" esté habilitado (si no usas App Passwords)

### Error: "STARTTLS falló"

- Verifica que la extensión OpenSSL esté habilitada en PHP
- Prueba con el puerto 465 usando SSL en lugar de STARTTLS

### Los correos llegan a spam

- Verifica que el dominio "From" coincida con el dominio del servidor SMTP
- Agrega registros SPF y DKIM en tu dominio (para producción)
- Evita palabras spam en el asunto y contenido

---

## 📝 Configuración Alternativa: Otros proveedores SMTP

### Outlook/Office365
```php
define('SMTP_HOST', 'smtp.office365.com');
define('SMTP_PORT', 587);
```

### SendGrid
```php
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 587);
define('SMTP_USER', 'apikey');
define('SMTP_PASS', 'tu_api_key');
```

### Mailgun
```php
define('SMTP_HOST', 'smtp.mailgun.org');
define('SMTP_PORT', 587);
```

---

## 💡 Notas Importantes

1. **Desarrollo local**: Los correos pueden tardar unos segundos en enviarse
2. **Gmail**: Limita a 500 correos/día para cuentas gratuitas
3. **Producción**: Considera usar un servicio profesional como SendGrid, Mailgun o AWS SES
4. **Seguridad**: Nunca subas `config.php` con credenciales reales a repositorios públicos

---

## 🔐 Mejores Prácticas

1. Usa variables de entorno para credenciales en producción
2. Implementa rate limiting para evitar spam
3. Agrega logs de todos los correos enviados
4. Considera usar una cola de trabajos (queue) para envíos masivos


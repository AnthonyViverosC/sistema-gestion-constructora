# Guía de despliegue — Constructora

Pasos mínimos para llevar el sistema a un servidor de producción del cliente.

## Requisitos del servidor

- PHP 8.2 o superior con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `fileinfo`, `ctype`, `json`, `bcmath`.
- MySQL 5.7+ o MariaDB 10.3+.
- Composer 2.x.
- Servidor web (Apache o Nginx) con `mod_rewrite` o `try_files` apuntando a `public/index.php`.
- Certificado SSL/TLS válido para el dominio (las cookies de sesión están marcadas `Secure`).

## Primer despliegue

```bash
# 1. Clonar el repositorio
git clone <repo-url> /var/www/constructora
cd /var/www/constructora

# 2. Dependencias sin paquetes de desarrollo
composer install --no-dev --optimize-autoloader

# 3. Configurar el entorno
cp .env.production.example .env
# Editar .env y completar: APP_URL, DB_USERNAME, DB_PASSWORD,
# ADMIN_EMAIL, ADMIN_PASSWORD, MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD,
# MAIL_FROM_ADDRESS.

# 4. Generar la clave de aplicación
php artisan key:generate

# 5. Permisos de escritura para Laravel
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 6. Migraciones e índices de performance
php artisan migrate --force

# 7. Crear el usuario administrador inicial (lee ADMIN_* del .env)
php artisan db:seed --force

# 8. Cachear configuración y rutas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Vincular el disco de almacenamiento si se necesita servir archivos
# públicos (no aplica: los documentos se sirven desde el disco `local`).
```

## Verificación post-deploy

1. Abrir `https://<dominio>/login`, debe cargar el formulario.
2. Iniciar sesión con `ADMIN_EMAIL` / `ADMIN_PASSWORD` y cambiar la contraseña desde el perfil.
3. Crear un contrato de prueba y subir un documento.
4. Revisar que `storage/logs/laravel.log` no muestre errores.

## Despliegue de actualizaciones

```bash
cd /var/www/constructora
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

## Notas importantes

- **HTTPS obligatorio**: `SESSION_SECURE_COOKIE=true` y `SESSION_ENCRYPT=true`. Si se despliega temporalmente sin certificado, poner `SESSION_SECURE_COOKIE=false` en el `.env` para permitir login por HTTP.
- **APP_DEBUG=false** en producción. Nunca activar `true` con datos reales: expone trazas y variables de entorno.
- **Backup**: respaldar la base de datos y `storage/app/documentos/` (los archivos cargados se guardan en disco `local`).
- **Logs**: rotación diaria (`LOG_STACK=daily`). Revisar `storage/logs/laravel-YYYY-MM-DD.log`.

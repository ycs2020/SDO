#!/bin/bash

RUTA="/var/www/html/sdo"

echo "🔧 Aplicando permisos en $RUTA..."

# Cambiar grupo a www-data (sin cambiar el dueño original)
chown -R :www-data "$RUTA"

# Dar permisos 775 a carpetas (rwxrwxr-x) y activar setgid para herencia de grupo
find "$RUTA" -type d -exec chmod 2775 {} \;

# Dar permisos 664 a archivos (rw-rw-r--)
find "$RUTA" -type f -exec chmod 664 {} \;

# Asegurar herencia de grupo activando el bit g+s
find "$RUTA" -type d -exec chmod g+s {} \;

echo "✅ Permisos aplicados correctamente."

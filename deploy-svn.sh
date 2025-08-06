#!/bin/bash
#
# deploy-svn.sh
#
# Este script automatiza el despliegue de una nueva versión del plugin al repositorio SVN de WordPress.org.
# Lee la versión desde readme.txt, crea una nueva etiqueta en SVN y sube los cambios.
#
# REQUISITOS:
# 1. Debes tener un cliente de SVN instalado (ej: `sudo apt-get install subversion`).
# 2. Debes haber hecho "checkout" de tu repositorio de SVN en tu máquina local.

# --- CONFIGURACIÓN ---
# Ruta al directorio local donde hiciste "checkout" de tu repositorio SVN.
# ¡¡¡IMPORTANTE!!! CAMBIA ESTA RUTA A LA TUYA.
SVN_PATH="/home/santi/work/wp-svn/emoji-plugin"

# Ruta al directorio fuente de tu plugin (donde está este script).
SOURCE_PATH="."

# --- NO EDITAR DEBAJO DE ESTA LÍNEA ---

# 1. Extraer la versión estable desde readme.txt
echo "Obteniendo la versión desde readme.txt..."
VERSION=$(grep -i "^Stable tag:" "$SOURCE_PATH/readme.txt" | awk -F' ' '{print $3}')

if [ -z "$VERSION" ]; then
    echo "Error: No se pudo encontrar la versión en readme.txt."
    exit 1
fi

echo "Versión a desplegar: $VERSION"

# 2. Comprobar si el directorio SVN existe.
if [ ! -d "$SVN_PATH" ]; then
    echo "Error: El directorio SVN no existe en la ruta especificada: $SVN_PATH"
    echo "Por favor, clona tu repositorio SVN primero y configura la ruta en este script."
    exit 1
fi

# 3. Actualizar el repositorio SVN local.
echo "Actualizando el repositorio SVN local..."
svn up "$SVN_PATH"

# 4. Copiar los archivos del plugin al /trunk de SVN.
# Se excluyen los archivos no necesarios para la producción.
echo "Copiando archivos a /trunk..."
rsync -av --delete "$SOURCE_PATH/" "$SVN_PATH/trunk/" --exclude=".git" --exclude=".gitignore" --exclude="assets" --exclude="*.zip" --exclude="*.sh" --exclude="docker-compose.yaml" --exclude="README.md"

# 5. Copiar los recursos gráficos a /assets de SVN.
echo "Copiando recursos a /assets..."
rsync -av --delete "$SOURCE_PATH/assets/" "$SVN_PATH/assets/"

# 6. Añadir cualquier archivo nuevo al control de versiones de SVN.
echo "Buscando archivos nuevos para añadir a SVN..."
svn add "$SVN_PATH" --force --parents --depth infinity --quiet

# 7. Eliminar archivos que ya no existen.
echo "Buscando archivos eliminados para quitar de SVN..."
svn st "$SVN_PATH" | grep "^\!" | sed 's/! *//' | xargs -I% svn rm "%"

# 8. Crear la nueva etiqueta (tag) para la versión.
echo "Creando la etiqueta para la versión $VERSION..."
svn cp "$SVN_PATH/trunk" "$SVN_PATH/tags/$VERSION"

# 9. Enviar los cambios al repositorio de WordPress.org.
echo "Subiendo los cambios al repositorio SVN. Se te pedirá tu usuario y contraseña de WordPress.org."
svn ci "$SVN_PATH" --message "Lanzando la versión $VERSION"

echo "¡Despliegue completado!"

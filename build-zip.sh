#!/bin/bash
#
# build-zip.sh
#
# Este script empaqueta el plugin en un archivo .zip listo para ser subido a WordPress.org.
# Excluye archivos y directorios innecesarios para mantener el paquete limpio y ligero.

# El "slug" o nombre del directorio de tu plugin.
PLUGIN_SLUG="emoji-plugin"

# Nombre del archivo .zip de destino.
ZIP_FILE="${PLUGIN_SLUG}.zip"

# Directorio de compilación temporal.
BUILD_DIR="build"

# 1. Limpiar el archivo zip anterior y el directorio de compilación si existen.
echo "Limpiando compilaciones anteriores..."
rm -f "$ZIP_FILE"
rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR"

# 2. Copiar los archivos del plugin al directorio de compilación.
# Usamos rsync para tener un control preciso sobre qué excluir.
echo "Copiando archivos del plugin..."
rsync -av --progress ./* "$BUILD_DIR/" --exclude="*.zip" --exclude="build/" --exclude=".git/" --exclude=".gitignore" --exclude="docker-compose.yaml" --exclude="*.sh" --exclude="README.md"

# 3. Crear el archivo .zip desde el directorio de compilación.
echo "Creando el archivo ${ZIP_FILE}..."
cd "$BUILD_DIR/" || exit
zip -r "../${ZIP_FILE}" .
cd ..

# 4. Limpiar el directorio de compilación.
echo "Limpiando el directorio temporal..."
rm -rf "$BUILD_DIR"

echo "¡Éxito! El archivo del plugin está listo en: ${ZIP_FILE}"

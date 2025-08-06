# Plugin de WordPress: Emoji Reactions

Un plugin para WordPress simple y moderno que permite a los visitantes reaccionar a tus posts con un conjunto de emojis configurables. Los contadores de reacciones se almacenan y actualizan en tiempo real mediante AJAX.

## ✨ Características

- **Emojis Configurables**: Define tu propio conjunto de emojis desde la página de ajustes.
- **Potenciado por AJAX**: Los usuarios pueden reaccionar sin recargar la página para una experiencia fluida.
- **Tabla de Base de Datos Personalizada**: Almacena los contadores de reacciones de forma eficiente.
- **Desinstalación Limpia**: Elimina todos sus datos (opciones y tabla personalizada) al ser borrado.
- **Entorno Dockerizado**: Incluye un `docker-compose.yaml` para un desarrollo local rápido y sencillo.
- **Scripts de Despliegue**: Automatización para empaquetar el plugin y publicarlo en el repositorio de WordPress.org.

---

## 🚀 Desarrollo y Pruebas en Local

Este proyecto incluye una configuración de Docker Compose para levantar una instancia de WordPress con el plugin ya montado.

**Prerrequisitos:**
*   [Docker](https://www.docker.com/get-started)
*   [Docker Compose](https://docs.docker.com/compose/install/)

**Pasos:**

1.  **Clona el repositorio.**
2.  **Inicia el entorno:** `docker-compose up -d`
3.  **Accede a WordPress:** Abre tu navegador y ve a `http://localhost:8080` para completar la instalación.
4.  **Activa el Plugin:** En el panel de admin, ve a **Plugins** y activa **Emoji Reactions**.
5.  **Configura:** Ve a **Ajustes > Emoji Reactions** para personalizar los emojis.

Para detener el entorno, usa `docker-compose down`.

---

## steps_to_publish_to_wordpress_org

Publicar tu plugin en el repositorio oficial de WordPress.org requiere seguir unos pasos específicos. Estos scripts te ayudarán a automatizar gran parte del proceso.

### Paso 1: Preparar los Recursos Gráficos (Assets)

WordPress.org muestra un icono y un banner para tu plugin. Debes crearlos y colocarlos en la carpeta `/assets`:

- **Icono:** `assets/icon-256x256.png` (o .jpg)
- **Banner:** `assets/banner-772x250.png` (o .jpg)
- **Capturas de pantalla:** `assets/screenshot-1.png`, `assets-screenshot-2.png`, etc.

### Paso 2: Empaquetar el Plugin para la Revisión Inicial

Antes de que te den acceso a SVN, debes enviar un archivo `.zip` para que el equipo de WordPress revise tu plugin.

1.  Asegúrate de que los scripts son ejecutables:
    ```bash
    chmod +x build-zip.sh
    ```
2.  Ejecuta el script de compilación:
    ```bash
    ./build-zip.sh
    ```
3.  Esto creará un archivo `emoji-plugin.zip`. Súbelo en la página [Add Your Plugin](https://wordpress.org/plugins/developers/add/).

### Paso 3: Desplegar una Nueva Versión (Una vez aprobado)

Cuando tu plugin sea aprobado, recibirás acceso a un repositorio SVN. Para subir nuevas versiones:

1.  **Clona tu repositorio SVN**: La primera vez, clona el repositorio que te asignen en una carpeta fuera de este proyecto. Por ejemplo:
    ```bash
    svn co https://plugins.svn.wordpress.org/emoji-plugin/ wp-svn/emoji-plugin
    ```
2.  **Configura el script de despliegue**: Abre `deploy-svn.sh` y cambia la variable `SVN_PATH` para que apunte a la carpeta que acabas de clonar.
3.  **Actualiza la versión**: Antes de desplegar, asegúrate de incrementar el número de versión en `readme.txt` (en la línea `Stable tag`) y en la cabecera de `emoji-plugin.php`.
4.  **Ejecuta el script de despliegue**:
    ```bash
    chmod +x deploy-svn.sh
    ./deploy-svn.sh
    ```

El script se encargará de copiar los archivos, crear la etiqueta de la nueva versión y subir todo al repositorio de WordPress.org.

---

## ✍️ Autor

*   **Santiago Valdez** - <santiagovaldez@groupweird.com>

---

## 📄 Licencia

Este plugin está licenciado bajo la [GPLv2 o posterior](https://www.gnu.org/licenses/gpl-2.0.html).
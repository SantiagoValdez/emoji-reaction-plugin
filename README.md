# Plugin de WordPress: Emoji Reactions

Un plugin para WordPress simple y moderno que permite a los visitantes reaccionar a tus posts con un conjunto de emojis configurables. Los contadores de reacciones se almacenan y actualizan en tiempo real mediante AJAX.

## ✨ Características

- **Emojis Configurables**: Define tu propio conjunto de emojis desde la página de ajustes.
- **Potenciado por AJAX**: Los usuarios pueden reaccionar sin recargar la página para una experiencia fluida.
- **Tabla de Base de Datos Personalizada**: Almacena los contadores de reacciones de forma eficiente.
- **Desinstalación Limpia**: Elimina todos sus datos (opciones y tabla personalizada) al ser borrado, dejando tu base de datos limpia.
- **Configuración Fácil**: Activa, configura y listo.
- **Entorno Dockerizado**: Incluye un `docker-compose.yaml` para un desarrollo local rápido y sencillo.

---

## 🚀 Desarrollo y Pruebas en Local

Este proyecto incluye una configuración de Docker Compose para levantar una instancia de WordPress con el plugin ya montado.

**Prerrequisitos:**
*   [Docker](https://www.docker.com/get-started)
*   [Docker Compose](https://docs.docker.com/compose/install/)

**Pasos:**

1.  **Clona el repositorio:**
    ```bash
    git clone https://github.com/tu-usuario/emoji-plugin.git
    cd emoji-plugin
    ```

2.  **Inicia el entorno:**
    ```bash
    docker-compose up -d
    ```

3.  **Accede a WordPress:**
    *   Abre tu navegador y ve a `http://localhost:8080`.
    *   Sigue las instrucciones en pantalla para completar la instalación de WordPress.

4.  **Activa el Plugin:**
    *   Inicia sesión en tu nuevo panel de administración de WordPress.
    *   Navega a **Plugins**.
    *   **Emoji Reactions** estará en la lista. Haz clic en **Activar**.

5.  **Configura:**
    *   Ve a **Ajustes > Emoji Reactions** para personalizar los emojis disponibles.

Cuando termines, puedes detener los contenedores con `docker-compose down`.

---

## 📦 Instalación en Producción

1.  Descarga el archivo ZIP de la última versión desde la [página de releases](https://github.com/tu-usuario/emoji-plugin/releases).
2.  En tu panel de administración de WordPress, ve a **Plugins > Añadir nuevo > Subir Plugin**.
3.  Sube el archivo ZIP y activa el plugin.
4.  Navega a **Ajustes > Emoji Reactions** para configurar los emojis permitidos.

---

## ✍️ Autor

*   **Santiago Valdez** - <santiagovaldez@groupweird.com>

---

## 📄 Licencia

Este plugin está licenciado bajo la [GPLv2 o posterior](https://www.gnu.org/licenses/gpl-2.0.html).
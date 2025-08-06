# Emoji Reactions WordPress Plugin

A simple and modern WordPress plugin that allows visitors to react to your posts with a configurable set of emojis. Reaction counts are stored and updated in real-time via AJAX.

![Plugin Screenshot](https://i.imgur.com/YOUR_SCREENSHOT_URL.png) <!-- You can replace this with a real screenshot URL -->

## ✨ Features

- **Configurable Emojis**: Define your own set of emojis from the settings page.
- **AJAX Powered**: Users can react without reloading the page for a smooth experience.
- **Custom Database Table**: Efficiently stores reaction counts.
- **Clean Uninstall**: Removes all its data (options and custom table) upon deletion, leaving your database clean.
- **Easy Setup**: Activate, configure, and you're ready to go.
- **Dockerized Environment**: Includes a `docker-compose.yaml` for quick and easy local development.

---

## 🚀 Local Development & Testing

This project includes a Docker Compose setup to spin up a WordPress instance with the plugin already mounted.

**Prerequisites:**
*   [Docker](https://www.docker.com/get-started)
*   [Docker Compose](https://docs.docker.com/compose/install/)

**Steps:**

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/emoji-plugin.git
    cd emoji-plugin
    ```

2.  **Start the environment:**
    ```bash
    docker-compose up -d
    ```

3.  **Access WordPress:**
    *   Open your browser and go to `http://localhost:8080`.
    *   Follow the on-screen instructions to complete the WordPress installation.

4.  **Activate the Plugin:**
    *   Log in to your new WordPress admin panel.
    *   Navigate to **Plugins**.
    *   **Emoji Reactions** will be in the list. Click **Activate**.

5.  **Configure:**
    *   Go to **Settings > Emoji Reactions** to customize the available emojis.

When you're done, you can stop the containers with `docker-compose down`.

---

## 📦 Installation in Production

1.  Download the latest release ZIP file from the [releases page](https://github.com/your-username/emoji-plugin/releases).
2.  In your WordPress admin panel, go to **Plugins > Add New > Upload Plugin**.
3.  Upload the ZIP file and activate the plugin.
4.  Navigate to **Settings > Emoji Reactions** to configure the allowed emojis.

---

## 📄 License

This plugin is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

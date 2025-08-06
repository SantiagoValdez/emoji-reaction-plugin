<?php
/**
 * Plugin Name:       Emoji Reactions
 * Description:       Permite a los visitantes reaccionar a los posts con una selección de emojis configurables.
 * Version:           1.2.1
 * Author:            Santiago Valdez <santiagovaldez@groupweird.com>
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       emoji-reactions
 */

// Evita que el archivo sea accedido directamente sin pasar por WordPress.
if (!defined('ABSPATH')) {
    exit;
}

// =============================================================================
// HOOK DE ACTIVACIÓN: CREACIÓN DE LA TABLA EN LA BASE DE DATOS
// =============================================================================

/**
 * Se ejecuta una sola vez cuando el plugin es activado.
 * Crea una tabla personalizada en la base de datos para almacenar las reacciones.
 */
function er_activate() {
    // 'global $wpdb' nos da acceso al objeto de base de datos de WordPress.
    global $wpdb;
    // Define el nombre de nuestra tabla, usando el prefijo estándar de WordPress.
    $table_name = $wpdb->prefix . 'emoji_reactions';
    // Obtiene el conjunto de caracteres y el cotejamiento correctos para la base de datos.
    $charset_collate = $wpdb->get_charset_collate();

    // SQL para crear la tabla.
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) UNSIGNED NOT NULL,
        emoji varchar(255) NOT NULL,
        reaction_count int(11) NOT NULL DEFAULT 0,
        PRIMARY KEY  (id),
        UNIQUE KEY post_emoji (post_id, emoji)
    ) $charset_collate;";

    // 'dbDelta' es una función de WordPress que examina la estructura actual de la tabla,
    // la compara con la estructura deseada y la añade o modifica según sea necesario.
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
// Registra la función 'er_activate' para que se ejecute en el hook 'register_activation_hook'.
register_activation_hook(__FILE__, 'er_activate');


// =============================================================================
// PÁGINA DE AJUSTES EN EL PANEL DE ADMINISTRACIÓN
// =============================================================================

/**
 * Añade una nueva página de opciones en el menú "Ajustes" del panel de administración.
 */
function er_settings_page() {
    add_options_page(
        'Emoji Reactions Settings', // Título de la página
        'Emoji Reactions',          // Texto en el menú
        'manage_options',           // Capacidad requerida para ver la página
        'emoji-reactions',          // Slug de la página
        'er_settings_page_html'     // Función que renderiza el HTML de la página
    );
}
add_action('admin_menu', 'er_settings_page');

/**
 * Registra los ajustes del plugin para que WordPress los gestione.
 */
function er_register_settings() {
    // Registra un ajuste llamado 'er_allowed_emojis'.
    register_setting(
        'er_options', // Grupo de opciones
        'er_allowed_emojis', // Nombre de la opción
        [
            'type' => 'string', // Tipo de dato
            'sanitize_callback' => 'sanitize_text_field', // Función para limpiar el dato
            'default' => '😀,😂,❤️,👍' // Valor por defecto
        ]
    );

    // Añade una sección a nuestra página de ajustes.
    add_settings_section(
        'er_settings_section',      // ID de la sección
        'Ajustes de Reacciones',    // Título de la sección
        'er_settings_section_callback', // Función que muestra una descripción de la sección
        'emoji-reactions'           // Página en la que se mostrará
    );

    // Añade un campo a la sección que hemos creado.
    add_settings_field(
        'er_allowed_emojis_field', // ID del campo
        'Emojis Permitidos',       // Etiqueta del campo
        'er_allowed_emojis_field_callback', // Función que renderiza el campo
        'emoji-reactions',         // Página
        'er_settings_section'      // Sección
    );
}
add_action('admin_init', 'er_register_settings');

/**
 * Muestra la descripción de la sección de ajustes.
 */
function er_settings_section_callback() {
    echo 'Introduce una lista de emojis separados por comas que los usuarios podrán usar para reaccionar.';
}

/**
 * Renderiza el campo de texto para introducir los emojis permitidos.
 */
function er_allowed_emojis_field_callback() {
    // Obtiene el valor actual de la opción desde la base de datos.
    $option = get_option('er_allowed_emojis');
    // Muestra el campo de entrada de texto.
    echo "<input type='text' name='er_allowed_emojis' value='" . esc_attr($option) . "' style='width: 300px;' />";
}

/**
 * Renderiza el HTML completo de la página de ajustes.
 */
function er_settings_page_html() {
    // Comprueba si el usuario actual tiene permisos para gestionar opciones.
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // 'settings_fields' se encarga de la seguridad (nonces, etc.).
            settings_fields('er_options');
            // 'do_settings_sections' muestra todas las secciones y campos añadidos a la página.
            do_settings_sections('emoji-reactions');
            // Muestra el botón de guardar.
            submit_button('Guardar Ajustes');
            ?>
        </form>
    </div>
    <?php
}


// =============================================================================
// MOSTRAR REACCIONES EN EL FRONT-END
// =============================================================================

/**
 * Añade el contenedor de reacciones al final del contenido de un post.
 *
 * @param string $content El contenido original del post.
 * @return string El contenido modificado con las reacciones.
 */
function er_display_reactions($content) {
    // Solo muestra las reacciones en las páginas de posts individuales.
    if (is_single()) {
        global $post;
        $post_id = $post->ID;

        // Obtiene la lista de emojis permitidos desde las opciones.
        $allowed_emojis_str = get_option('er_allowed_emojis', '😀,😂,❤️,👍');
        $allowed_emojis = explode(',', $allowed_emojis_str);

        $reactions_html = '<div class="emoji-reactions-container">';

        global $wpdb;
        $table_name = $wpdb->prefix . 'emoji_reactions';

        // Itera sobre cada emoji permitido para crear su botón.
        foreach ($allowed_emojis as $emoji) {
            $emoji = trim($emoji);
            if (empty($emoji)) continue;

            // Obtiene el recuento actual para este emoji y este post.
            $count = $wpdb->get_var($wpdb->prepare(
                "SELECT reaction_count FROM $table_name WHERE post_id = %d AND emoji = %s",
                $post_id, $emoji
            ));

            // Crea el HTML del botón con atributos de datos para el JavaScript.
            $reactions_html .= sprintf(
                '<button class="emoji-reaction-button" data-post-id="%d" data-emoji="%s">%s <span class="emoji-reaction-count">%d</span></button>',
                $post_id,
                esc_attr($emoji),
                $emoji,
                (int)$count
            );
        }

        $reactions_html .= '</div>';
        // Añade el HTML de las reacciones al final del contenido del post.
        $content .= $reactions_html;
    }
    return $content;
}
add_filter('the_content', 'er_display_reactions');


// =============================================================================
// CARGA DE SCRIPTS Y ESTILOS (ASSETS)
// =============================================================================

/**
 * Pone en cola los archivos CSS y JavaScript necesarios para el plugin.
 */
function er_enqueue_assets() {
    // Solo carga los assets en las páginas de posts individuales.
    if (is_single()) {
        // Carga el archivo de estilos.
        wp_enqueue_style(
            'er-style',
            plugin_dir_url(__FILE__) . 'assets/style.css',
            [], // Sin dependencias
            '1.2.0' // Versión del archivo
        );

        // Carga el archivo JavaScript.
        wp_enqueue_script(
            'er-script',
            plugin_dir_url(__FILE__) . 'assets/reactions.js',
            [], // Sin dependencias
            '1.2.0', // Versión del archivo
            true // Cargar en el footer
        );

        // Pasa datos de PHP a JavaScript de forma segura (incluyendo el nonce de seguridad).
        wp_localize_script('er-script', 'er_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('er_react_nonce')
        ]);
    }
}
add_action('wp_enqueue_scripts', 'er_enqueue_assets');


// =============================================================================
// MANEJADOR DE AJAX PARA GUARDAR LAS REACCIONES
// =============================================================================

/**
 * Se ejecuta cuando se recibe una petición AJAX para registrar una reacción.
 */
function er_react_callback() {
    // 1. VERIFICACIÓN DE SEGURIDAD
    // Comprueba el nonce. Si no es válido, la ejecución se detiene.
    check_ajax_referer('er_react_nonce', 'nonce');

    // 2. OBTENCIÓN Y VALIDACIÓN DE DATOS
    global $wpdb;
    $table_name = $wpdb->prefix . 'emoji_reactions';

    // Obtiene el ID del post y lo convierte a un entero.
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    // **LA CORRECCIÓN CLAVE ESTÁ AQUÍ**
    // Decodifica el emoji que viene de JavaScript.
    $raw_emoji = isset($_POST['emoji']) ? urldecode($_POST['emoji']) : '';

    // Obtiene la lista de emojis permitidos desde la base de datos.
    $allowed_emojis_str = get_option('er_allowed_emojis', '😀,😂,❤️,👍');
    $allowed_emojis = explode(',', $allowed_emojis_str);
    // Limpia los espacios en blanco de la lista de emojis permitidos.
    $allowed_emojis = array_map('trim', $allowed_emojis);

    // Valida que el emoji recibido esté en la lista de permitidos y que el post_id sea válido.
    if ($post_id > 0 && !empty($raw_emoji) && in_array($raw_emoji, $allowed_emojis)) {
        
        // 3. INTERACCIÓN CON LA BASE DE DATOS
        // Prepara la consulta SQL para insertar una nueva reacción o actualizar una existente.
        // 'ON DUPLICATE KEY UPDATE' es atómico, lo que previene condiciones de carrera.
        $result = $wpdb->query($wpdb->prepare(
            "INSERT INTO $table_name (post_id, emoji, reaction_count) VALUES (%d, %s, 1) ON DUPLICATE KEY UPDATE reaction_count = reaction_count + 1",
            $post_id, $raw_emoji
        ));

        // Comprueba si la consulta a la base de datos falló.
        if ($result === false) {
            wp_send_json_error('Error en la consulta a la base de datos.');
            return;
        }

        // Obtiene el nuevo recuento actualizado desde la base de datos.
        $new_count = $wpdb->get_var($wpdb->prepare(
            "SELECT reaction_count FROM $table_name WHERE post_id = %d AND emoji = %s",
            $post_id, $raw_emoji
        ));

        // 4. RESPUESTA
        // Envía una respuesta JSON exitosa con el nuevo recuento.
        wp_send_json_success(['new_count' => (int)$new_count]);

    } else {
        // Si los datos no son válidos, envía una respuesta de error.
        wp_send_json_error('Datos no válidos o emoji no permitido.');
    }
}
// Engancha la función tanto para usuarios logueados como para visitantes.
add_action('wp_ajax_er_react', 'er_react_callback');
add_action('wp_ajax_nopriv_er_react', 'er_react_callback');
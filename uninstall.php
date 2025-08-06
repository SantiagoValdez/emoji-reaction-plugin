<?php
/**
 * Fichero de Desinstalación para Emoji Reactions
 *
 * Este fichero se ejecuta cuando un usuario hace clic en "Borrar" en la página de plugins de WordPress.
 * Su propósito es limpiar completamente la base de datos de cualquier dato creado por el plugin.
 */

// Si este fichero no es llamado por WordPress, abortar.
// Esto previene que el fichero sea ejecutado directamente y borre datos por accidente.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// 1. Borrar la opción de los emojis permitidos de la tabla 'wp_options'.
delete_option('er_allowed_emojis');

// 2. Borrar la tabla personalizada de la base de datos.
// Se necesita acceso global al objeto de base de datos de WordPress.
global $wpdb;
// Se construye el nombre completo de la tabla, incluyendo el prefijo de WordPress.
$table_name = $wpdb->prefix . 'emoji_reactions';
// Se ejecuta una consulta SQL directa para borrar la tabla si existe.
$wpdb->query("DROP TABLE IF EXISTS $table_name");

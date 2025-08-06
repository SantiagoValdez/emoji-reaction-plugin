=== Emoji Reactions ===
Contributors: santi-gemini
Tags: reactions, emoji, engagement, posts, ajax
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows visitors to react to your posts with a configurable set of emojis. Reaction counts are stored and updated in real-time via AJAX.

== Description ==

Emoji Reactions provides a simple and modern way to increase user engagement on your website. Instead of just a simple like button, you can offer a range of emotional responses. The plugin is lightweight, secure, and easy to configure.

Key Features:

*   **Configurable Emojis**: Define your own set of emojis from the settings page.
*   **AJAX Powered**: Users can react without reloading the page.
*   **Clean Uninstall**: The plugin removes all its data (options and custom table) upon deletion.
*   **Easy Setup**: Just activate and configure. The reaction buttons will appear automatically on your posts.

== Installation ==

1.  Upload the `emoji-plugin` folder to the `/wp-content/plugins/` directory on your server.
2.  Activate the plugin through the 'Plugins' menu in your WordPress dashboard.
3.  Navigate to **Settings > Emoji Reactions** to configure the list of allowed emojis (e.g., `👍,❤️,😂,😮,😢`).
4.  That's it! The reaction buttons will now appear at the bottom of your single posts.

== Changelog ==

= 1.2.0 =
*   **FIX**: Solved a critical bug where reactions were not being saved due to incorrect data sanitization.
*   **ENHANCEMENT**: Added extensive code comments to all plugin files for better readability and maintenance.
*   **DOCS**: Updated documentation and added `.gitignore` and `README.md`.

= 1.1.0 =
*   **REFACTOR**: Complete overhaul from a shortcode plugin to a full-featured reaction system.
*   **FEATURE**: Added a custom database table to store reaction counts.
*   **FEATURE**: Created a settings page to configure allowed emojis.
*   **FEATURE**: Implemented AJAX for real-time reactions.

= 1.0.0 =
*   Initial release (as an emoji shortcode plugin).
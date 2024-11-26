<?php
/**
 * Plugin Name: Events Plugin
 * Description: An event management plugin.
 * Version: 1.0
 * Author: Tuba Saif
 * Text Domain: events-plugin
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Autoload classes
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Define constants
define( 'EVENTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load core plugin functionalities
if ( class_exists( 'EventsPlugin\Core' ) ) {
    EventsPlugin\Core::init();
}

// Register uninstallation hook
register_uninstall_hook( __FILE__, 'events_plugin_uninstall' );

/**
 * Cleanup on plugin uninstall.
 */
function events_plugin_uninstall() {
    // Delete plugin options or data if necessary.
    delete_option( 'events_plugin_settings' );
}

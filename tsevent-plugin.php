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
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Autoload Core class
use TSEventPlugin\classes\Core;
function run_event() {
    $event = new Core();
    $event->init();   
}
run_event();

// Autoload Rest_API class
use TSEventPlugin\classes\Rest_API;
function run_eventrest() {
    $eventrest = new Rest_API();
    $eventrest->init(); 
}
run_eventrest();

// Define constants
define( 'EVENTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
// Uncomment the following line if you need to include the Shortcode class
//include(EVENTS_PLUGIN_URL . 'includes/classes/Shortcode.php');



/**
 * Cleanup on plugin uninstall.
 */
function events_plugin_uninstall() {
    // Delete plugin options or data if necessary.
    delete_option( 'events_plugin_settings' );
}

// Register uninstallation hook
register_uninstall_hook( __FILE__, 'events_plugin_uninstall' );

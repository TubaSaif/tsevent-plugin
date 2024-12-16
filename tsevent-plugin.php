<?php
/**
 * Plugin Name: Events Plugin
 * Description: An event management plugin.
 * Version: 1.0
 * Author: Tuba Saif
 * Text Domain: events-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// Autoload classes
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Autoload classes
use TSEventPlugin\classes\Core;

if ( ! class_exists( 'TSEVENT_Class' ) ) {
    class TS_Event_Manager {

        public function __construct() {
            $this->define_constants();
            $this->run_event();
            $this->register_uninstall();
        }

        // Run the event manager
        public function run_event() {
            $event = new Core();
            $event->init();   
        }

        // Define constants
        public function define_constants() {
            define( 'EVENTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            define( 'EVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        }

        // Cleanup on plugin uninstall
        public static function events_plugin_uninstall() {
            delete_option( 'events_plugin_settings' );
        }

        // Register uninstallation hook
        public function register_uninstall() {
            register_uninstall_hook( __FILE__, ['TS_Event_Manager', 'events_plugin_uninstall'] );
        }
    }
}

// Initialize the plugin
$ts_event_manager = new TS_Event_Manager();

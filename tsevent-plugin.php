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


    // Use the Core class
    //use TSEventPlugin\Core;
    use TSEventPlugin\classes\Core;
    //(new Core())->init();
        function run_event() {
            $event = new Core();
            $event->init();    echo "main File loaded!vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv<br>";
            echo "Current file: " . basename(__FILE__) . "<br>";
        }
        run_event();



    // Define constants
    define( 'EVENTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    define( 'EVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    /**
     * Cleanup on plugin uninstall.
     */
    function events_plugin_uninstall() {
        // Delete plugin options or data if necessary.
        delete_option( 'events_plugin_settings' );
        
    }
    // Register uninstallation hook
    register_uninstall_hook( __FILE__, 'events_plugin_uninstall' );

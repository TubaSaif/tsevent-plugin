<?php
namespace TSEventPlugin\classes;

use TSEventPlugin\classes\Shortcodes;
use TSEventPlugin\classes\Elementor_Widget;
use TSEventPlugin\classes\Rest_API;
use TSEventPlugin\classes\TS_Taxonomies;

if(!class_exists('Core')){
class Core {
    
    // Initialize the plugin and set up necessary hooks
    public function init() {
        $this->register_hooks();
        $this->load_external_files();
        $this->run_shortcode();
        $this->run_eventrest();
        TS_Taxonomies::init();
    }

    // Register all the hooks and actions
    private function register_hooks() {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
    }

    // Load additional external files if they exist
    private function load_external_files() {
        $file_path = plugin_dir_path( __FILE__ ) . 'adminsetting.php';
        if ( file_exists( $file_path ) ) {
            include( $file_path );
        } else {
            error_log( 'Custom file not found: ' . $file_path );
        }

        // Load the template file if it exists
        include plugin_dir_path( __DIR__ ) . 'templates/page-view.php';
    }

    // Enqueue plugin styles and scripts
    public static function enqueue_scripts() {
        wp_enqueue_style( 'events-plugin-style', EVENTS_PLUGIN_URL . 'assets/src/css/public.css', [], '1.0' );
        
    }

    // Run the shortcode for event
    private function run_shortcode() {
        $shortcode = new Shortcodes();
        $shortcode->init();
    }

    // Run the event REST API functionality
    private function run_eventrest() {
        $eventrest = new Rest_API();
        $eventrest->init();
    }

    // Load and render a template file
    public static function events_plugin_get_template( $template, $data = [] ) {
        $template_path = plugin_dir_path( __DIR__ ) . '../templates/' . $template;
    
        if ( file_exists( $template_path ) ) {
            extract( $data );
            include $template_path;
        } else {
            error_log( "Template file not found: " . $template_path );
        }
    }

    // Query events from the database
    public static function get_events( $args = [] ) {
        $defaults = [
            'post_type' => 'event',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        $query_args = wp_parse_args( $args, $defaults );
        $query = new \WP_Query( $query_args );

        return $query->posts;
    }
}
}

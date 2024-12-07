<?php
namespace TSEventPlugin\classes;

use TSEventPlugin\classes\Shortcodes;
use TSEventPlugin\classes\Elementor_Widget;

class Core {
    public function init() {
        add_action( 'init', [ __CLASS__, 'register_event_post_type' ] );
        add_action( 'init', [ __CLASS__, 'register_event_taxonomies' ] );
        //add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        function run_shortcode() {
            $shortcode = new Shortcodes();
            $shortcode->init();
        }
        run_shortcode();
        $file_path= plugin_dir_path(__FILE__) . 'adminsetting.php';
        if (file_exists($file_path)) {
            include($file_path);
            error_log('Custom file found: ' . $file_path);
        } else {
            error_log('Custom file not found: ' . $file_path);
        }
    }
    //register post type-event
    public static function register_event_post_type() {
        register_post_type( 'event', [
            'labels'      => [
                'name'          => __( 'Events', 'events-plugin' ),
                'singular_name' => __( 'Event', 'events-plugin' ),
            ],
            'public'      => true,
            'has_archive' => true,
            'supports'    => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
            'rewrite'     => [ 'slug' => 'events' ],
        ]);
    }
    //Register taxonomies
    public static function register_event_taxonomies() {
        register_taxonomy( 'event_category', 'event', [
            'labels'       => [ 'name' => __( 'Categories', 'events-plugin' ) ],
            'hierarchical' => true,
            'public'       => true,
        ]);

        register_taxonomy( 'event_tag', 'event', [
            'labels' => [ 'name' => __( 'Tags', 'events-plugin' ) ],
            'public' => true,
        ]);
    }
    public static function events_plugin_get_template($template, $data = []) {
        // Locate the template file in your plugin's templates folder
        $template_path = plugin_dir_path(__DIR__) . '../templates/' . $template;
    
        if (file_exists($template_path)) {
            // Extract data to variables for use in the template
            extract($data);
    
            // Include the template file
            include $template_path;
        } else {
            // Log or handle missing template file
            error_log("Template file not found: " . $template_path);
            echo "Template file not found: " . esc_html($template_path);
        }
    }    
    //Querying database for CPT
    public static function get_events($args = []) {
        global $wpdb;

        $defaults = [
            'post_type' => 'ts_event',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];
        $query_args = wp_parse_args($args, $defaults);

        $query = new \WP_Query($query_args);
        return $query->posts;
    }
    public static function enqueue_scripts() {
        wp_enqueue_style( 'events-plugin-style', EVENTS_PLUGIN_URL . 'assets/dist/main.css', [], '1.0' );
        wp_enqueue_script( 'events-plugin-script', EVENTS_PLUGIN_URL . 'assets/dist/main.bundle.js', [ 'jquery' ], '1.0', true );
    }

}

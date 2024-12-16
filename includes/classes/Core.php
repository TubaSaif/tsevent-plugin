<?php
namespace TSEventPlugin\classes;

use TSEventPlugin\classes\Shortcodes;
use TSEventPlugin\classes\Elementor_Widget;
use TSEventPlugin\classes\Rest_API;

class Core {
    
    // Initialize the plugin and set up necessary hooks
    public function init() {
        $this->register_hooks();
        $this->load_external_files();
        $this->run_shortcode();
        $this->run_eventrest();
    }

    // Register all the hooks and actions
    private function register_hooks() {
        add_action( 'init', [ __CLASS__, 'register_event_post_type' ] );
        add_action( 'init', [ __CLASS__, 'register_event_taxonomies' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'add_meta_boxes', [ __CLASS__, 'add_event_meta_boxes' ] );
        add_action( 'save_post', [ __CLASS__, 'save_event_date_meta' ] );
    }

    // Load additional external files if they exist
    private function load_external_files() {
        $file_path = plugin_dir_path( __FILE__ ) . 'adminsetting.php';
        if ( file_exists( $file_path ) ) {
            include( $file_path );
            error_log( 'Custom file found: ' . $file_path );
        } else {
            error_log( 'Custom file not found: ' . $file_path );
        }

        // Load the template file if it exists
        include plugin_dir_path( __DIR__ ) . 'templates/page-view.php';
    }

    // Register the custom post type 'event'
    public static function register_event_post_type() {
        register_post_type( 'event', [
            'labels' => [
                'name'          => __( 'Events', 'events-plugin' ),
                'singular_name' => __( 'Event', 'events-plugin' ),
            ],
            'public'      => true,
            'has_archive' => true,
            'supports'    => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
            'rewrite'     => [ 'slug' => 'events' ],
        ] );
    }

    // Add meta box to event post type
    public static function add_event_meta_boxes() {
        add_meta_box(
            'event_date_meta', 
            __( 'Event Date', 'events-plugin' ), 
            [ __CLASS__, 'render_event_date_meta_box' ], 
            'event', 
            'side', 
            'high'
        );
    }

    // Render event date meta box
    public static function render_event_date_meta_box( $post ) {
        $event_date = get_post_meta( $post->ID, '_event_date', true );
        wp_nonce_field( 'save_event_date_meta', 'event_date_nonce' );
        
        echo '<label for="event_date">' . __( 'Event Date:', 'events-plugin' ) . '</label>';
        echo '<input type="date" id="event_date" name="event_date" value="' . esc_attr( $event_date ) . '" style="width: 100%;">';
    }

    // Save the event date meta data when the post is saved
    public static function save_event_date_meta( $post_id ) {
        if ( ! isset( $_POST['event_date_nonce'] ) || ! wp_verify_nonce( $_POST['event_date_nonce'], 'save_event_date_meta' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['event_date'] ) ) {
            $event_date = sanitize_text_field( $_POST['event_date'] );
            update_post_meta( $post_id, '_event_date', $event_date );
        }
    }

    // Register event taxonomies
    public static function register_event_taxonomies() {
        // Event Categories (Hierarchical)
        register_taxonomy( 'event_category', 'event', [
            'labels'       => [ 'name' => __( 'Categories', 'events-plugin' ) ],
            'hierarchical' => true,
            'public'       => true,
        ] );

        // Event Tags (Non-hierarchical)
        register_taxonomy( 'event_tag', 'event', [
            'labels' => [ 'name' => __( 'Tags', 'events-plugin' ) ],
            'public' => true,
        ] );
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
            echo "Template file not found: " . esc_html( $template_path );
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

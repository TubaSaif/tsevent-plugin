<?php
namespace TSEventPlugin\classes;

use TSEventPlugin\classes\Shortcodes;
use TSEventPlugin\classes\Elementor_Widget;

class Core {
    public function init() {
        add_action( 'init', [ __CLASS__, 'register_event_post_type' ] );
        add_action( 'init', [ __CLASS__, 'register_event_taxonomies' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        // Hook into WordPress to register the custom post type and meta box
        add_action('init', [__CLASS__, 'register_event_post_type']);  // Register the custom post type on 'init' action
        add_action('add_meta_boxes', [__CLASS__, 'add_event_meta_boxes']);  // Add meta boxes on 'add_meta_boxes' action
        add_action('save_post', [__CLASS__, 'save_event_date_meta']);  // Save meta data when the post is saved
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
        include plugin_dir_path(__DIR__) . 'templates/page-view.php' ;
    }
    // Function to register the 'event' custom post type
    public static function register_event_post_type() {
        register_post_type('event', [
            'labels' => [
                // Set the plural and singular names for the post type
                'name'          => __('Events', 'events-plugin'),
                'singular_name' => __('Event', 'events-plugin'),
            ],
            'public'      => true,  // Make the post type public so it can be accessed on the frontend
            'has_archive' => true,  // Enable archive page for the event post type
            'supports'    => ['title', 'editor', 'thumbnail', 'custom-fields'], // Specify which WordPress features the post type supports
            'rewrite'     => ['slug' => 'events'],  // Set custom URL slug for events
        ]);
    }

    // Function to add a meta box for the 'event' post type
    public static function add_event_meta_boxes() {
        add_meta_box(
            'event_date_meta',  // ID for the meta box
            __('Event Date', 'events-plugin'),  // Title of the meta box
            [__CLASS__, 'render_event_date_meta_box'],  // Callback function to render the content of the meta box
            'event',  // Specify the post type to add the meta box to
            'side',  // Position the meta box in the sidebar
            'high'  // Priority of the meta box (high means it appears first)
        );
    }

    // Function to render the content inside the event date meta box
    public static function render_event_date_meta_box($post) {
        // Retrieve the current value of the event date for this post
        $event_date = get_post_meta($post->ID, '_event_date', true);

        // Add a nonce field for security purposes
        wp_nonce_field('save_event_date_meta', 'event_date_nonce');

        // Render the HTML for the input field
        echo '<label for="event_date">' . __('Event Date:', 'events-plugin') . '</label>';  // Label for the input field
        echo '<input type="date" id="event_date" name="event_date" value="' . esc_attr($event_date) . '" style="width: 100%;">';  // Date input field with the saved value
    }

    // Function to save the event date meta data when the post is saved
    public static function save_event_date_meta($post_id) {
        // Verify that the nonce is set and valid to prevent CSRF attacks
        if (!isset($_POST['event_date_nonce']) || !wp_verify_nonce($_POST['event_date_nonce'], 'save_event_date_meta')) {
            return;
        }

        // Check if WordPress is doing an autosave; if yes, don't save the data
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Ensure the current user has permission to edit this post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save the event date if the field is set in the form
        if (isset($_POST['event_date'])) {
            // Sanitize the event date to ensure it's safe for storage
            $event_date = sanitize_text_field($_POST['event_date']);
            // Update the post meta with the sanitized event date value
            update_post_meta($post_id, '_event_date', $event_date);
        }
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
            'post_type' => 'event',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];
        $query_args = wp_parse_args($args, $defaults);

        $query = new \WP_Query($query_args);
        return $query->posts;
    }
    public static function enqueue_scripts() {
       wp_enqueue_style( 'events-plugin-style', EVENTS_PLUGIN_URL . 'assets/src/css/public.css', [], '1.0' );
       // wp_enqueue_script( 'events-plugin-script', EVENTS_PLUGIN_URL . 'assets/dist/main.bundle.js', [ 'jquery' ], '1.0', true );
       // wp_enqueue_script( 'events-plugin-script', 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js');
    }

}

<?php
namespace TSEventPlugin\classes;

if ( ! class_exists( 'TS_Taxonomies' ) ) {

    class TS_Taxonomies {
        // Initialize the taxonomies (to be called during plugin init)
        public static function init() {
            add_action( 'init', [ __CLASS__, 'register_event_post_type' ] );
            add_action( 'init', [ __CLASS__, 'register_event_taxonomies' ] );
            add_action( 'add_meta_boxes', [ __CLASS__, 'add_event_meta_boxes' ] );
            add_action( 'save_post_event', [ __CLASS__, 'save_event_date_meta' ] );

        }

        // Register event custom post type
        public static function register_event_post_type() {
            register_post_type( 'event', [
                'labels' => [
                    'name'          => __( 'Events', 'events-plugin' ),
                    'singular_name' => __( 'Event', 'events-plugin' ),
                ],
                'public'      => true,
                'menu_icon' => 'dashicons-calendar',
                'has_archive' => true,
                'supports'    => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
                'rewrite'     => [ 'slug' => 'events' ],
            ] );
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

    }
}

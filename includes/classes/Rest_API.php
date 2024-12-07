<?php
namespace TSEventPlugin\classes;

class Rest_API {
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'my_enqueue_scripts' ] ); // Corrected function handler
       
    }
    public static function register_routes() {
        register_rest_route( 'events-plugin/v1', '/search', [
            'methods'  => 'GET',
            'callback' => [ __CLASS__, 'search_events' ],
        ]);
    }

    public static function search_events( $request ) {
        
        $query_args = [
            'post_type' => 'event', // Replace 'event' with your custom post type slug
            's'         => $request->get_param( 'keyword' ), // Basic keyword search
        ];

        $query = new \WP_Query( $query_args );
        $events = [];

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $events[] = [
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
                ];
            }
            wp_reset_postdata();
        }

        return rest_ensure_response( $events );
    }
    public static function my_enqueue_scripts() {
        // Enqueue the main script for the plugin
        wp_enqueue_script( 'events-plugin-script', EVENTS_PLUGIN_URL . 'assets/src/js/restsearch.js', ['jquery'], '1.0', true );
        
        // Localize the script with data
        wp_localize_script(
            'events-plugin-script', // Use the same handle as the script
            'myScriptData', // JS object name
            [
                'ajax_url' => admin_url('admin-ajax.php'), // AJAX URL for admin-ajax
                'site_url' => site_url(), // Example additional data
                'rest_url' => esc_url( rest_url( 'events-plugin/v1/search' ) ), // REST API URL
            ]
        );
    }
}

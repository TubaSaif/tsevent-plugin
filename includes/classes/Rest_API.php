<?php
namespace TSEventPlugin\classes;

class Rest_API {
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'my_enqueue_scripts' ] );    
    }
    public static function register_routes() {
        register_rest_route( 'events-plugin/v1', '/search', [
            'methods'  => 'GET',
            'callback' => [ __CLASS__, 'search_events' ],
        ]);
    }

    public static function search_events( $request ) {
        $keyword = $request->get_param( 'keyword' );
        $date = $request->get_param( 'date' );
    
        $query_args = [
            'post_type' => 'event',
            's'         => $keyword,
            'meta_query' => [],
        ];
    
        if ( $date ) {
            $query_args['meta_query'][] = [
                'key'     => '_event_date',
                'value'   => $date,
                'compare' => '=',
                'type'    => 'DATE',
            ];
        }
    
        $query = new \WP_Query( $query_args );
        $events = [];
    
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $event_date = get_post_meta( get_the_ID(), '_event_date', true );
                $events[] = [
                    'title' => get_the_title(),
                    'url'   => get_permalink(),
                    'start' => $event_date,
                ];
            }
            wp_reset_postdata();
        }
    
        if ( empty( $events ) ) {
            // Add a specific message to the response
            return rest_ensure_response([
                'message' => $date ? "No events found on $date." : 'No events found.',
            ]);
        }
    
        return rest_ensure_response( $events );
    }
    
    public static function my_enqueue_scripts() {
        
        wp_enqueue_script( 'jquery' );

        wp_enqueue_script( 'events-plugin-script', EVENTS_PLUGIN_URL . 'assets/src/js/restsearch.js', ['jquery'], '1.0', true );
        error_log( 'Script URL: ' . EVENTS_PLUGIN_URL . 'assets/src/js/restsearch.js' );
        wp_localize_script(
            'events-plugin-script', // handler
            'myScriptData', // JS object name
            [
                'ajax_url' => admin_url('admin-ajax.php'), 
                'site_url' => site_url(), 
                'rest_url' => esc_url( rest_url( 'events-plugin/v1/search' ) ), 
                'view_all_url'=> 'http://localhost/themecheck/events', // URL for the "View All" button
            ]
        );
    }
}

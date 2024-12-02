<?php
namespace EventsPlugin;

class Rest_API {
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
    }

    public static function register_routes() {
        register_rest_route( 'events-plugin/v1', '/search', [
            'methods'  => 'GET',
            'callback' => [ __CLASS__, 'search_events' ],
        ]);
    }

    public static function search_events( $request ) {
        $query_args = [
            'post_type'  => 'event',
            's'          => $request->get_param( 'keyword' ),
            'meta_query' => [
                [
                    'key'     => 'event_date',
                    'value'   => $request->get_param( 'date' ),
                    'compare' => 'LIKE',
                ],
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => $request->get_param( 'category' ),
                ],
            ],
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
}

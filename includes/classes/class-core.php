<?php
namespace EventsPlugin;

class Core {
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_event_post_type' ] );
        add_action( 'init', [ __CLASS__, 'register_event_taxonomies' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
    }

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

    public static function enqueue_scripts() {
        wp_enqueue_style( 'events-plugin-style', EVENTS_PLUGIN_URL . 'assets/dist/main.css', [], '1.0' );
        wp_enqueue_script( 'events-plugin-script', EVENTS_PLUGIN_URL . 'assets/dist/main.bundle.js', [ 'jquery' ], '1.0', true );
    }
}

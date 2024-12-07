<?php

namespace TSEventPlugin\classes;
use TSEventPlugin\classes\Core;

class Shortcodes {
    public static function init() {
        add_shortcode('tsevent_display', [__CLASS__, 'render_events']);
        //add_action('wp_enqueue_scripts', [ __CLASS__, 'my_enqueue_scripts']);
    }
    // public static function my_enqueue_scripts() {
    //     wp_enqueue_script( 'events-plugin-script', EVENTS_PLUGIN_URL . 'assets/src/js/restsearch.js', ['jquery'], '1.0', true );
        
    // }
    public static function render_events($atts) {
        $atts = shortcode_atts(
            [
                'view' => get_option('events_plugin_view', 'calendar'),
                'theme_color' => get_option('events_plugin_theme_color', '#ffffff'),
            ],
            $atts,
            'tsevent_display'
        );
        ?>
        <form id="event-search-form">
            <input type="text" id="event-search-input" placeholder="Search Events" />
            <button type="submit">Search</button>
        </form>
        <div id="event-search-results"></div>
        <?php
        // Further PHP logic goes here
       
        

       // error_log('View: ' . get_option('events_plugin_view'));
       // error_log('Theme Color: ' . get_option('events_plugin_theme_color'));
        $events = Core::get_events(); // General query logic here
        $template = ($atts['view'] === 'calendar') ? 'calendar-view.php' : 'list-view.php';
       // error_log('View: ' .$events );
       // error_log('View: ' .$template);
        ob_start();
        Core::events_plugin_get_template($template, [
            'events' => $events,
            'theme_color' => $atts['theme_color'],
        ]);
        return ob_get_clean();
    }
}

Shortcodes::init();

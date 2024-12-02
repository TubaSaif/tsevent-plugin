<?php

namespace TSEventPlugin\classes;
use TSEventPlugin\classes\Core;

class Shortcodes {
    public static function init() {
        add_shortcode('tsevent_display', [__CLASS__, 'render_events']);
        //echo "shortcode File loaded!<br>";
        //echo "Current file: " . basename(__FILE__) . "<br>";
    }
    // public static function render_events() {
    //     ob_start();
    //     echo "This is a test output from the shortcode.";
    //     return ob_get_clean(); // Return the content for WordPress to include
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
        error_log('View: ' . get_option('events_plugin_view'));
        error_log('Theme Color: ' . get_option('events_plugin_theme_color'));
        $events = Core::get_events(); // General query logic here
        $template = ($atts['view'] === 'calendar') ? 'calendar-view.php' : 'list-view.php';

        ob_start();
        Core::events_plugin_get_template($template, [
            'events' => $events,
            'theme_color' => $atts['theme_color'],
        ]);
        return ob_get_clean();
    }
}

Shortcodes::init();

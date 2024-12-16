<?php

namespace TSEventPlugin\classes;
use TSEventPlugin\classes\Core;

class Shortcodes {
    public static function init() {
        add_shortcode('tsevent_search', [__CLASS__, 'render_search_form']);
        add_shortcode('tsevent_view', [__CLASS__, 'render_events']);
    }

    // Render the event search form
    public static function render_search_form($atts) {
        ob_start();
        ?>
        <div id="search-container">
            <form id="event-search-form">
                <input type="text" id="event-search-input" placeholder="Search Events" />
                <!-- Date picker input field -->
                <input type="date" id="event-date-input" placeholder="Select Event Date" /> 
                <button type="submit">Search</button>
            </form>
            <div id="dropdown-result"></div> 
        </div>
        <!-- <div id="event-search-results"></div>  -->
        <?php
        return ob_get_clean();
    }

    // Render the event list or calendar view
    public static function render_events($atts) {
        $atts = shortcode_atts(
            [
                'view' => get_option('events_plugin_view', 'calendar'),
                'theme_color' => get_option('events_plugin_theme_color', '#ffffff'),
            ],
            $atts,
            'tsevent_view'
        );

        // Template selection based on the 'view' attribute
        $template = ($atts['view'] === 'calendar') ? 'calendar-view.php' : 'list-view.php';
        
        ob_start();
        Core::events_plugin_get_template($template, [
            'theme_color' => $atts['theme_color'],
        ]);
        return ob_get_clean();
    }
}

Shortcodes::init();

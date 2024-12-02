<?php
namespace EventsPlugin;

class Integrations {
    public static function init() {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_google_maps' ] );
        add_shortcode( 'event_map', [ __CLASS__, 'render_event_map' ] );
    }

    public static function enqueue_google_maps() {
        wp_enqueue_script(
            'google-maps-api',
            'https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_API_KEY',
            [],
            null,
            true
        );
    }

    public static function render_event_map( $atts ) {
        $atts = shortcode_atts(
            [ 'location' => 'New York, NY' ],
            $atts,
            'event_map'
        );

        $location = esc_attr( $atts['location'] );

        ob_start();
        ?>
        <div id="event-map" style="height: 400px;"></div>
        <script>
            function initMap() {
                const location = { lat: 40.7128, lng: -74.0060 }; // Default to New York
                const map = new google.maps.Map(document.getElementById('event-map'), {
                    zoom: 10,
                    center: location,
                });
                new google.maps.Marker({ position: location, map: map });
            }
            google.maps.event.addDomListener(window, 'load', initMap);
        </script>
        <?php
        return ob_get_clean();
    }
}

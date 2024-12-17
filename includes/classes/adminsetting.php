<?php
// Add a settings menu page with a priority of 10 (default is 10)
add_action('admin_menu', function () {
    add_menu_page(
        'Events Plugin Settings',
        'Events Plugin',
        'manage_options',
        'events-plugin-settings',
        'event_plugin_setting_page',
        'dashicons-feedback',  // Icon
        26                     // Priority/Position
    );
}, 5);

// Render settings page
function event_plugin_setting_page() {
    ?>
    <div class="wrap">
        <h1 class="events-plugin-title">Events Plugin Settings</h1>
        
        <!-- Shortcode Notice -->
        <div class="notice notice-info">
            <p><strong>Shortcodes:</strong> Use the following shortcodes to display events on your site:</p>
            <ul>
                <li><code>[tsevent_search]</code> - Display a search form for events.</li>
                <li><code>[tsevent_view]</code> - Display the events in the selected view (calendar or list).</li>
            </ul>
            <p><em>Simply add these shortcodes to any page or post where you want the events to appear.</em></p>
        </div>

        <!-- Settings Form -->
        <form method="post" action="options.php" class="events-plugin-form">
            <?php
            settings_fields('events_plugin_settings');
            do_settings_sections('events-plugin-settings');
            submit_button('Save Settings', 'primary', 'submit', true);
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', function () {
    register_setting('events_plugin_settings', 'events_plugin_view');
    register_setting('events_plugin_settings', 'events_plugin_theme_color');

    add_settings_section(
        'events_plugin_main',
        'Main Settings',
        null,
        'events-plugin-settings'
    );

    add_settings_field(
        'events_plugin_view',
        'Event View',
        function () {
            $value = get_option('events_plugin_view', 'calendar');
            ?>
            <select name="events_plugin_view" class="events-plugin-select">
                <option value="calendar" <?php selected($value, 'calendar'); ?>>Calendar</option>
                <option value="list" <?php selected($value, 'list'); ?>>List</option>
            </select>
            <?php
        },
        'events-plugin-settings',
        'events_plugin_main'
    );

    add_settings_field(
        'events_plugin_theme_color',
        'Theme Color',
        function () {
            $value = get_option('events_plugin_theme_color', '#ffffff');
            ?>
            <input type="color" name="events_plugin_theme_color" value="<?php echo esc_attr($value); ?>" class="events-plugin-color-picker" />
            <?php
        },
        'events-plugin-settings',
        'events_plugin_main'
    );
}, 10);

// Enqueue External CSS
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('events-plugin-styles', EVENTS_PLUGIN_URL . 'assets/src/css/admin.css', array(), '1.0.0');
});
?>

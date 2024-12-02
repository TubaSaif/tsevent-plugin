<?php
// Add a settings menu page with a priority of 10 (default is 10)
add_action('admin_menu', function () {
    error_log('Adding Events Plugin Settings page to the menu');
    add_options_page(
        'Events Plugin Settings',
        'Events Plugin',
        'manage_options',
        'events-plugin-settings',
        function () {
            ?>
            <div class="wrap">
                <h1>Events Plugin Settings</h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('events_plugin_settings');
                    do_settings_sections('events-plugin-settings');
                    submit_button();
                    ?>
                </form>
            </div>
            <?php
        }
    );
}, 5);

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
        'Default View',
        function () {
            $value = get_option('events_plugin_view', 'calendar');
            ?>
            <select name="events_plugin_view">
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
            <input type="color" name="events_plugin_theme_color" value="<?php echo esc_attr($value); ?>" />
            <?php
        },
        'events-plugin-settings',
        'events_plugin_main'
    );
}, 10);


?>
<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define theme color if not set
$theme_color = $theme_color ?? '#ffffff';

// Query events
$events_query = new WP_Query([
    'post_type'      => 'event', // Ensure you registered the 'event' post type
    'posts_per_page' => 10,
    'paged'          => max(1, get_query_var('paged')),
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);

// Debugging
if (!$events_query->have_posts()) {
    error_log('No events found.');
    error_log('SQL Query: ' . $events_query->request);
} else {
    error_log('Found events: ' . $events_query->post_count);
}

?>

<div style="background-color: <?php echo esc_attr($theme_color); ?>">
    <h1>Event Template List</h1>
    <div id="event-search-results">
    <?php if ($events_query->have_posts()) : ?>
        <?php while ($events_query->have_posts()) : $events_query->the_post(); ?>
            <p><?php echo esc_html(get_the_title()); ?></p>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No events found.</p>
    <?php endif; ?>
    </div>
    <?php wp_reset_postdata(); ?>
</div>

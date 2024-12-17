<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define theme color if not set
$theme_color = $theme_color ?? '#ffffff';

// Query events
$events_query = new WP_Query([
    'post_type'      => 'event',
    'posts_per_page' => -1, // Fetch all events
    'meta_key'       => '_event_date', // Assuming '_event_date' is the meta key for the event date
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);

// Collect events for the list view
$events = [];
if ($events_query->have_posts()) {
    while ($events_query->have_posts()) {
        $events_query->the_post();
        $event_date = get_post_meta(get_the_ID(), '_event_date', true); // Get event date meta field
        if ($event_date) {
            $events[] = [
                'title'     => get_the_title(),
                'date'      => $event_date, // Event date
                'url'       => get_permalink(), // Link to the event
                'excerpt'   => get_the_excerpt(), // Event excerpt
                'image'     => get_the_post_thumbnail_url(), // Event image
            ];
        }
    }
}
wp_reset_postdata();
?>

<div style="background-color: <?php echo esc_attr($theme_color); ?>">
    <h1>Event List View</h1>
    
    <!-- Custom Search Bar -->
    <input type="text" id="event-search" placeholder="Search events..." onkeyup="filterEvents()" />

    <div id="event-list">
    <?php foreach ($events as $event) : ?>
        <div class="event-item" data-date="<?php echo esc_attr($event['date']); ?>">
            <?php if ($event['image']) : ?>
                <div class="event-image-wrapper">
                    <img src="<?php echo esc_url($event['image']); ?>" alt="<?php echo esc_attr($event['title']); ?>" class="event-image" />
                </div>
            <?php endif; ?>

            <div class="event-item-content">
                <h2 class="event-title">
                    <a href="<?php echo esc_url($event['url']); ?>" class="event-title-link">
                        <?php echo esc_html($event['title']); ?>
                    </a>
                </h2>
                <p class="event-date">
                    <strong>Date:</strong> <?php echo esc_html($event['date']); ?>
                </p>
                <p class="event-excerpt">
                    <?php echo esc_html($event['excerpt']); ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>

<script>
    // Function to filter events based on the search input
    function filterEvents() {
        var input = document.getElementById('event-search').value.toLowerCase();
        var events = document.querySelectorAll('.event-item');

        events.forEach(function(event) {
            var title = event.querySelector('h2').innerText.toLowerCase();
            var date = event.getAttribute('data-date').toLowerCase();

            if (title.includes(input) || date.includes(input)) {
                event.style.display = '';
            } else {
                event.style.display = 'none';
            }
        });
    }
</script>



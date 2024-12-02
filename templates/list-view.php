<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$events = new WP_Query([
    'post_type' => 'event',
    'posts_per_page' => 10,
    'paged' => max( 1, get_query_var( 'paged' ) ),
    'meta_key' => '_event_date',
    'orderby' => 'meta_value',
    'order' => 'ASC',
]);
?>

<div class="list-view">
    <h2>Event List</h2>
    <?php if ( $events->have_posts() ): ?>
        <ul class="event-list">
            <?php while ( $events->have_posts() ): $events->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <p><strong>Date:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_event_date', true ) ); ?></p>
                    <p><strong>Location:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_event_location', true ) ); ?></p>
                </li>
            <?php endwhile; ?>
        </ul>

        <div class="pagination">
            <?php
            echo paginate_links([
                'total' => $events->max_num_pages,
            ]);
            ?>
        </div>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</div>

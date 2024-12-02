<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

<div class="single-event-container">
    <h1><?php the_title(); ?></h1>
    <p><strong>Date:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_event_date', true ) ); ?></p>
    <p><strong>Location:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_event_location', true ) ); ?></p>
    <div class="event-description">
        <?php the_content(); ?>
    </div>
    <div class="event-meta">
        <p><strong>Category:</strong> <?php echo get_the_term_list( get_the_ID(), 'event_category', '', ', ' ); ?></p>
        <p><strong>Tags:</strong> <?php echo get_the_term_list( get_the_ID(), 'event_tag', '', ', ' ); ?></p>
    </div>
    <div class="event-map">
        <?php echo do_shortcode( '[event_map location="' . esc_attr( get_post_meta( get_the_ID(), '_event_location', true ) ) . '"]' ); ?>
    </div>
</div>

<?php get_footer(); ?>

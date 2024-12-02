<?php
// if ( ! defined( 'ABSPATH' ) ) {
//     exit; // Exit if accessed directly.
// }

// $events = new WP_Query([
//     'post_type' => 'event',
//     'posts_per_page' => -1,
//     'meta_key' => '_event_date',
//     'orderby' => 'meta_value',
//     'order' => 'ASC',
// ]);

// $calendar = [];
// while ( $events->have_posts() ) {
//     $events->the_post();
//     $date = get_post_meta( get_the_ID(), '_event_date', true );
//     if ( ! isset( $calendar[ $date ] ) ) {
//         $calendar[ $date ] = [];
//     }
//     $calendar[ $date ][] = [
//         'title' => get_the_title(),
//         'link'  => get_the_permalink(),
//     ];
// }
// wp_reset_postdata();

?>
<div style="background-color: <?php echo esc_attr($theme_color); ?>">
    <h1>Event Template</h1>
    <?php foreach ($events as $event) : ?>
        <p><?php echo esc_html($event->post_title); ?></p>
    <?php endforeach; ?>
</div>

<!-- <div class="calendar-view">
    <h2>Event Calendar</h2>
    <table class="event-calendar">
        <thead>
            <tr>
                <th>Date</th>
                <th>Events</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $calendar as $date => $events ): ?>
                <tr>
                    <td><?php echo esc_html( $date ); ?></td>
                    <td>
                        <ul>
                            <?php foreach ( $events as $event ): ?>
                                <li><a href="<?php echo esc_url( $event['link'] ); ?>"><?php echo esc_html( $event['title'] ); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> -->

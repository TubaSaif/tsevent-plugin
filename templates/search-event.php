<?php
//  Template Name: page-view
?>

<div class="events-list">
    <h1>All Events</h1>
    <?php
    $query = new WP_Query([
        'post_type' => 'event', 
        'posts_per_page' => -1, // Show all events
        
    ]);

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) 
            { $query->the_post();
                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                ?>
                <div class="event-item">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php echo $event_date; ?></p>
                </div>
                <?php
            }
    }
    wp_reset_postdata();
    
    ?>
</div>



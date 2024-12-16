<?php
    // if (!defined('ABSPATH')) {
    //     exit; // Exit if accessed directly.
    // }

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

    // Collect events for FullCalendar.js
    $events = [];
    if ($events_query->have_posts()) {
        while ($events_query->have_posts()) {
            $events_query->the_post();
            $event_date = get_post_meta(get_the_ID(), '_event_date', true); // Get event date meta field
            if ($event_date) {
                $events[] = [
                    'title' => get_the_title(),
                    'start' => $event_date, // Start date for the event
                    'url'   => get_permalink(), // Link to the event
                ];
            }
        }
    }
    wp_reset_postdata();
    ?>

    <div style="background-color: <?php echo esc_attr($theme_color); ?>">
        <h1>Event Calendar View</h1>
        <div id="event-search-results"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('event-search-results');
            
            // Initialize FullCalendar
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listMonth', // Default to listMonth view
                selectable: true, // Enable selection
                select: function (info) { // Handle the selection event
                alert('Selected range: ' + info.startStr + ' to ' + info.endStr);
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'listMonth,listYear,dayGridMonth,timeGridWeek,myCustomView'
                },
                views: {
                    listMonth: { buttonText: 'Month' },
                    listYear: { buttonText: 'Year' },
                    dayGridMonth: { buttonText: 'Month View' },
                    timeGridWeek: { buttonText: 'Week View' },
                    myCustomView: {
                        type: 'listYear',
                        duration: { year: 7 }, // Custom 7-day view
                        buttonText: 'Custom Week'
                    }   
                },
                events: <?php echo json_encode($events); ?>, // Pass events from PHP
                dateClick: function(info) {
                    // When a date is clicked, we update the view to show the events for the selected month
                    var selectedMonth = info.dateStr; // Get the clicked month
                    calendar.gotoDate(selectedMonth); // Go to the clicked month
                    calendar.changeView('listMonth'); // Switch to listMonth view to display events
                }
            });

            calendar.render();
        });
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

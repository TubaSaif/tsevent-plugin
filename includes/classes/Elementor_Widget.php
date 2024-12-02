<?php
namespace EventsPlugin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Elementor_Widget extends Widget_Base {

    public function get_name() {
        return 'events_list_widget';
    }

    public function get_title() {
        return __( 'Events List', 'events-plugin' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'events-plugin' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'category',
            [
                'label'       => __( 'Category', 'events-plugin' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __( 'Enter a category slug', 'events-plugin' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $category = $settings['category'];

        $query_args = [
            'post_type'  => 'event',
            'tax_query'  => [
                [
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ],
            ],
        ];

        $query = new \WP_Query( $query_args );

        if ( $query->have_posts() ) {
            echo '<ul class="events-list">';
            while ( $query->have_posts() ) {
                $query->the_post();
                echo '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo __( 'No events found.', 'events-plugin' );
        }
    }
}

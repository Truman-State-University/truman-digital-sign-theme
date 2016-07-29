<?php

/**
 * Adds Truman_Sign_Clock_Widget widget.
 */
class Truman_Sign_Clock_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'truman_sign_clock_widget',
            __('Clock Widget', 'truman-digital-sign-theme'),
            array('description' => __('Shows a clock with time and date', 'truman-digital-sign-theme'),)
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo '<div id="time"></div>';
        echo '<div id="date"></div>';
        echo sprintf(
            '<script type="text/javascript" src="%s/js/clock.js" /></script>',
            get_template_directory_uri()
         );
        echo $args['after_widget'];
    }

} // class Truman_Sign_Clock_Widget
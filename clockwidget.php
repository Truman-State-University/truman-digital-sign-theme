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
        $my_theme = wp_get_theme();
        echo $args['before_widget'];
        echo '<div id="time"></div>';
        echo '<div id="date"></div>';
        echo sprintf(
             '<script type="text/javascript">
                var useServerTime = %s;
             </script>
            <script type="text/javascript" src="%s/js/clock.js?version=%s" /></script>
            ',
            $instance['useServerTime'] ? 1 : 0,
            get_template_directory_uri(),
            $my_theme->get( 'Version' )

         );
        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        $useServerTime = $instance['useServerTime'];
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'useServerTime' ) ); ?>">Use Server Time</label>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'useServerTime' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'useServerTime' ) ); ?>" value="1" <?php if ($useServerTime) { echo " checked=\"checked\""; }?>>
        </p>
    <?php     }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['useServerTime'] = ( ! empty( $new_instance['useServerTime'] ) ) ? $new_instance['useServerTime'] : '';

        return $instance;
    }

} // class Truman_Sign_Clock_Widget
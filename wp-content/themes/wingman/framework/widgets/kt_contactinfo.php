<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT_ContactInfo widget class
 *
 * @since 1.0
 */
class Widget_KT_ContactInfo extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_kt_contactinfo', 'description' => '' );
        parent::__construct('kt_contactinfo', __('KT: Contact info', 'wingman'), $widget_ops);
        $this->alt_option_name = 'widget_kt_contactinfo';

    }


    public function widget($args, $instance) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        $output = '';
        $output .= '<div class="contact-info-wrapper">';
        $output .= '<ul class="contact-info-ul">';
        $output .= ($instance['address']) ? '<li><i class="fa fa-map-marker"></i>'.$instance['address'].'</li>' : '';
        $output .= ($instance['phone']) ? '<li><i class="fa fa-phone"></i>'.$instance['phone'].'</li>' : '';
        $output .= ($instance['email']) ? '<li><i class="fa fa-envelope"></i>'.$instance['email'].'</li>' : '';
        $output .= '</ul>';
        $output .= '</div>';

        echo $output;

        echo $args['after_widget'];

    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['address'] = strip_tags($new_instance['address']);
        $instance['phone'] = strip_tags($new_instance['phone']);
        $instance['email'] = strip_tags($new_instance['email']);


        return $instance;
    }

    public function form( $instance ) {

        $defaults = array( 'title' => __('Contact info', 'wingman'), 'address' => '', 'phone' => '', 'email' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags($instance['title']);

        ?>

        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wingman' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e( 'Address:', 'wingman' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" type="text" value="<?php echo esc_attr($instance['address']); ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e( 'Phone:', 'wingman' ); ?></label>
            <input  id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $instance['phone']); ?>" class="widefat" /></p>

        <p><label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Email:', 'wingman' ); ?></label>
            <input  id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo esc_attr($instance['email']); ?>" class="widefat" /></p>

        <?php
    }
}

/**
 * Register KT_ContactInfo widget
 *
 *
 */

register_widget('Widget_KT_ContactInfo');

<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT_Goolge widget class
 *
 * @since 1.0
 */
class Widget_KT_Goolge extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_kt_google', 'description' => __( "Embed Google+ Badge.", THEME_LANG) );
        parent::__construct('widget_kt_google', __('KT: Google+ Badge', THEME_LANG), $widget_ops);
        $this->alt_option_name = 'widget_kt_google';

        add_action('wp_footer', array($this, 'footer'));

    }

    function footer() {
        ?>
        <script type="text/javascript">
            (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/platform.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();</script>
    <?php
    }


    public function widget($args, $instance) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        $href = esc_url($instance['href']);
        if($href){

            $cover = isset( $instance['cover'] ) ? (bool) $instance['cover'] : true;
            $tagline = isset( $instance['tagline'] ) ? (bool) $instance['tagline'] : true;


            $layout =  ( in_array( $instance['layout'], array( 'portrait', 'landscape' ) ) ) ? $instance['layout'] : 'portrait';
            $color =  ( in_array( $instance['color'], array( 'light', 'dark' ) ) ) ? $instance['color'] : 'light';

            echo $args['before_widget'];
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            ?>
            <div class="g-page" data-href="<?php echo esc_attr($href); ?>" data-width="262" data-theme="<?php echo esc_attr($color) ?>" data-layout="<?php echo esc_attr($layout) ?>" data-rel="publisher" data-showtagline="<?php echo $tagline ? 'true' : 'false' ?>" data-showcoverphoto="<?php echo $cover ? 'true' : 'false' ?>"></div>
            <?php
            echo $args['after_widget'];
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['href'] = esc_url( $new_instance['href'] );

        $instance['cover'] = isset( $new_instance['layout'] ) ? (bool) $new_instance['cover'] : false;
        $instance['tagline'] = isset( $new_instance['tagline'] ) ? (bool) $new_instance['tagline'] : false;

        if ( in_array( $new_instance['layout'], array( 'portrait', 'landscape' ) ) ) {
            $instance['layout'] = $new_instance['layout'];
        } else {
            $instance['layout'] = 'portrait';
        }
        if ( in_array( $new_instance['color'], array( 'light', 'dark' ) ) ) {
            $instance['color'] = $new_instance['layout'];
        } else {
            $instance['color'] = 'light';
        }

        return $instance;

    }

    public function flush_widget_cache() {
        wp_cache_delete('widget_kt_google', 'widget');
    }

    public function form( $instance ) {

        $defaults = array( 'title' => __( 'Google Plus' , THEME_LANG), 'href' => '', 'layout' => 'portrait', 'color' => 'light', 'cover' => true, 'tagline' => true);
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = strip_tags($instance['title']);

        ?>

        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e( 'The URL of the Google plus Page:', THEME_LANG ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" type="text" value="<?php echo $instance['href']; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:',THEME_LANG); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                <option <?php selected( $instance['layout'], 'portrait' ); ?> value="portrait"><?php _e('Portrait',THEME_LANG); ?></option>
                <option <?php selected( $instance['layout'], 'landscape' ); ?> value="landscape"><?php _e('Landscape',THEME_LANG); ?></option>
            </select>
        </p>

        <p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color theme:',THEME_LANG); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>">
                <option <?php selected( $instance['color'], 'light' ); ?> value="light"><?php _e('Light',THEME_LANG); ?></option>
                <option <?php selected( $instance['color'], 'dark' ); ?> value="dark"><?php _e('Dark',THEME_LANG); ?></option>
            </select>
        </p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['cover'] ); ?> id="<?php echo $this->get_field_id( 'cover' ); ?>" name="<?php echo $this->get_field_name( 'cover' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'cover' ); ?>"><?php _e( 'Cover Photo', THEME_LANG ); ?></label>
            <br/><small><?php _e('Only work with portrait layout', THEME_LANG); ?></small></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['tagline'] ); ?> id="<?php echo $this->get_field_id( 'tagline' ); ?>" name="<?php echo $this->get_field_name( 'tagline' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'tagline' ); ?>"><?php _e( 'Tagline', THEME_LANG ); ?></label>
            <br/><small><?php _e('Only work with portrait layout', THEME_LANG); ?></small></p>

    <?php
    }
}

/**
 * Register KT_Goolge widget
 *
 *
 */

register_widget('Widget_KT_Goolge');

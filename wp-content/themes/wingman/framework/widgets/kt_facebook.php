<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT_Facebook widget class
 *
 * @since 1.0
 */
class Widget_KT_Facebook extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_kt_facebook', 'description' => __( "Embed facebook Like page.", KT_THEME_LANG) );
        parent::__construct('kt_facebook', __('KT: Page Plugin facebook', KT_THEME_LANG), $widget_ops);
        $this->alt_option_name = 'widget_kt_facebook';
    }


    public function widget($args, $instance) {

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $href = esc_url($instance['href']);
        if($href){
            $height = ( ! empty( $instance['height'] ) ) ? absint( $instance['height'] ) : 500;
            if ( ! $height )
                $height = 500;

            $small_header = isset( $instance['small_header'] ) ? (bool) $instance['small_header'] : false;
            $adapt_container_width = isset( $instance['adapt_container_width'] ) ? (bool) $instance['adapt_container_width'] : true;
            $hide_cover = isset( $instance['hide_cover'] ) ? (bool) $instance['hide_cover'] : false;
            $show_posts = isset( $instance['show_posts'] ) ? (bool) $instance['show_posts'] : false;
            $show_facepile = isset( $instance['show_facepile'] ) ? (bool) $instance['show_facepile'] : true;

        ?>
            <?php echo $args['before_widget']; ?>
            <?php
                if ( $title ) {
                    echo $args['before_title'] . $title . $args['after_title'];
                }
            ?>
            <div class="fb-page" data-href="<?php echo esc_attr($href) ?>" data-height="<?php echo $height; ?>" data-small-header="<?php echo $small_header ? 'true' : 'false' ?>" data-adapt-container-width="<?php echo $adapt_container_width ? 'true' : 'false'; ?>" data-hide-cover="<?php echo $hide_cover ? 'true' : 'false'; ?>" data-show-facepile="<?php echo $show_facepile ? 'true' : 'false'; ?>" data-show-posts="<?php echo $show_posts ? 'true' : 'false'; ?>"><div class="fb-xfbml-parse-ignore"><blockquote cite="<?php echo $href ?>"><a href="<?php echo $href ?>">Facebook</a></blockquote></div></div>
            <?php $appID = kt_option('facebook_app', '417674911655656'); ?>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/<?php echo get_locale(); ?>/sdk.js#xfbml=1&version=v2.3&appId=<?php echo $appID; ?>";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
            <?php echo $args['after_widget']; ?>
        <?php
        }

    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['href'] = strip_tags($new_instance['href']);

        $instance['height'] = (int) $new_instance['height'];

        $instance['small_header'] = isset( $new_instance['small_header'] ) ? (bool) $new_instance['small_header'] : false;
        $instance['adapt_container_width'] = isset( $new_instance['adapt_container_width'] ) ? (bool) $new_instance['adapt_container_width'] : false;
        $instance['hide_cover'] = isset( $new_instance['hide_cover'] ) ? (bool) $new_instance['hide_cover'] : false;
        $instance['show_posts'] = isset( $new_instance['show_posts'] ) ? (bool) $new_instance['show_posts'] : false;
        $instance['show_facepile'] = isset( $new_instance['show_facepile'] ) ? (bool) $new_instance['show_facepile'] : false;

        return $instance;
    }

    public function form( $instance ) {

        $defaults = array( 'title' => __( 'Facebook' , KT_THEME_LANG), 'height' => 500, 'small_header' => false, 'adapt_container_width' => true, 'hide_cover' => false, 'show_facepile' => true, 'show_posts' => false, 'href' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = strip_tags($instance['title']);

        ?>

        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e( 'The URL of the Facebook Page:', KT_THEME_LANG ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" type="text" value="<?php echo $instance['href']; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height', KT_THEME_LANG ); ?></label>
            <input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo $instance['height']; ?>" class="widefat" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['small_header'] ); ?> id="<?php echo $this->get_field_id( 'small_header' ); ?>" name="<?php echo $this->get_field_name( 'small_header' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'small_header' ); ?>"><?php _e( 'Use Small Header', KT_THEME_LANG ); ?></label>
        <br/><small><?php _e('Use the small header instead', KT_THEME_LANG); ?></small></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['adapt_container_width'] ); ?> id="<?php echo $this->get_field_id( 'adapt_container_width' ); ?>" name="<?php echo $this->get_field_name( 'adapt_container_width' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'adapt_container_width' ); ?>"><?php _e( 'Adapt to plugin container width', KT_THEME_LANG ); ?></label>
            <br/><small><?php _e('Try to fit inside the container width', KT_THEME_LANG); ?></small></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['hide_cover'] ); ?> id="<?php echo $this->get_field_id( 'hide_cover' ); ?>" name="<?php echo $this->get_field_name( 'hide_cover' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'hide_cover' ); ?>"><?php _e( 'Hide Cover Photo', KT_THEME_LANG ); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['show_facepile'] ); ?> id="<?php echo $this->get_field_id( 'show_facepile' ); ?>" name="<?php echo $this->get_field_name( 'show_facepile' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_facepile' ); ?>"><?php _e( 'Show Friend\'s Faces', KT_THEME_LANG ); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['show_posts'] ); ?> id="<?php echo $this->get_field_id( 'show_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_posts' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_posts' ); ?>"><?php _e( 'Show Page Posts', KT_THEME_LANG ); ?></label></p>

        <p class="description"><?php _e('For use widget, You need config Facebook App ID in Theme options', KT_THEME_LANG); ?></p>

    <?php
    }
}

/**
 * Register KT_Facebook widget
 *
 *
 */

register_widget('Widget_KT_Facebook');

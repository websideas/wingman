<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT image widget class
 *
 * @since 1.0
 */
class WP_Widget_KT_Image extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_kt_image', 'description' => __( 'Image for widget.', 'wingman' ) );
		parent::__construct('kt_image', __('KT: image', 'wingman' ), $widget_ops);
	}

	public function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );


        $attachment = get_thumbnail_attachment($instance['attachment'], $instance['size']);

        if($attachment){
    		echo $args['before_widget'];

            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }


            $image =  "<img src='".$attachment['url']."' alt='".esc_attr($attachment['alt'])."' title='".esc_attr($attachment['title'])."'/>";
            if($instance['link']){
                $image = sprintf(
                    '<a href="%s" target="%s">%s</a>',
                    esc_attr($instance['link']),
                    esc_attr($instance['target']),
                    $image
                );
            }

            printf(
                '<div class="kt-image-content text-%s">%s</div>',
                $instance['align'],
                $image
            );

    		echo $args['after_widget'];
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

		$instance['link'] = strip_tags($new_instance['link']);
        $instance['target'] = $new_instance['target'];
        $instance['size'] = $new_instance['size'];
        $instance['align'] = $new_instance['align'];
        $instance['attachment'] = intval($new_instance['attachment']);
        
		return $instance;
	}

	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Image', 'wingman'), 'target' => '_self', 'link' => '', 'attachment' => '', 'size' => '', 'animation' => '', 'align' => 'center') );
        $title = strip_tags($instance['title']);

		$link = esc_attr( $instance['link'] );
        $attachment = esc_attr( $instance['attachment'] );
        $preview = false;
        $img_preview = "";
        if($instance['attachment']){
            $file = get_thumbnail_attachment($instance['attachment'], 'full');
            $preview = true;
            $img_preview = $file['url'];
        }
		
	?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wingman' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <div class="wrapper_kt_image_upload">
            <p style="text-align: center;">
                <input type="button" style="width: 100%; padding: 10px; height: auto;" class="button kt_image_upload" value="<?php esc_attr_e('Select your image', 'wingman') ?>" />
                <input class="widefat kt_image_attachment" id="<?php echo $this->get_field_id('attachment'); ?>" name="<?php echo $this->get_field_name('attachment'); ?>" type="hidden" value="<?php echo esc_attr($attachment); ?>" />
            </p>
            <p class="kt_image_preview" style="<?php if($preview){ echo "display: block;";} ?>">
                <img src="<?php echo esc_url($img_preview); ?>" alt="" class="kt_image_preview_img" />
            </p>
        </div>
        <p style="clear: both;">
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:', 'wingman'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" />
        </p>
        <p>
			<label for="<?php echo $this->get_field_id('target'); ?>"><?php _e( 'Target:', 'wingman'); ?></label>
			<select name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>" class="widefat">
				<option value="_self"<?php selected( $instance['target'], '_self' ); ?>><?php _e('Stay in Window', 'wingman'); ?></option>
				<option value="_blank"<?php selected( $instance['target'], '_blank' ); ?>><?php _e('Open New Window', 'wingman'); ?></option>
			</select>
		</p>
        <p>
            <?php 
                $sizes = kt_get_image_sizes();
                $sizes['full'] = array(); 
            ?>
			<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e( 'Image size:', 'wingman' ); ?></label>
			<select name="<?php echo $this->get_field_name('size'); ?>" id="<?php echo $this->get_field_id('size'); ?>" class="widefat">
                <?php foreach($sizes as $key => $size){ ?>
                    <?php
                        $option_text = array();
                        $option_text[] = ucfirst($key);
                        if(isset($size['width'])){
                            $option_text[] = '('.$size['width'].' x '.$size['height'].')';
                        }
                        if(isset($size['crop']) && $size['crop']){
                            $option_text[] = __('Crop', 'wingman');
                        }
                    ?>
    				<option value="<?php echo $key; ?>"<?php selected( $instance['size'], $key ); ?>>
                        <?php echo implode(' - ', $option_text) ?>
                    </option>
                <?php } ?>
			</select>
		</p>
        <p>
            <label for="<?php echo $this->get_field_id('align'); ?>"><?php _e( 'Align:', 'wingman'); ?></label>
            <select name="<?php echo $this->get_field_name('align'); ?>" id="<?php echo $this->get_field_id('align'); ?>" class="widefat">
                <option value="center"<?php selected( $instance['align'], 'center' ); ?>><?php _e('Center', 'wingman'); ?></option>
                <option value="left"<?php selected( $instance['align'], 'left' ); ?>><?php _e('Left', 'wingman'); ?></option>
                <option value="right"<?php selected( $instance['align'], 'right' ); ?>><?php _e('Right', 'wingman'); ?></option>
            </select>
        </p>
<?php
	}

}


register_widget( 'WP_Widget_KT_Image' );
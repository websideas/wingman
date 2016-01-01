<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT Ads widget class
 *
 * @since 1.0
 */
class WP_Widget_KT_Ads extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_kt_ads', 'description' => __( 'Ads for widget.', KT_THEME_LANG ) );
		parent::__construct('kt_ads', __('KT: Ads 125x125', KT_THEME_LANG ), $widget_ops);
	}

	public function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        
        if(isset($instance['attachment1'])){ $attachment1 = get_thumbnail_attachment($instance['attachment1'], 'small'); }
        if(isset($instance['attachment2'])){ $attachment2 = get_thumbnail_attachment($instance['attachment2'], 'small'); }
        if(isset($instance['attachment3'])){ $attachment3 = get_thumbnail_attachment($instance['attachment3'], 'small'); }
        if(isset($instance['attachment4'])){ $attachment4 = get_thumbnail_attachment($instance['attachment4'], 'small'); }
        
		echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo "<div class='kt-ads-content clearfix'>";

        if(isset($attachment1)){
            if($instance['link1']){
                echo "<a href='".esc_attr($instance['link1'])."' target='".esc_attr($instance['target'])."'>";
            }
            echo "<img src='".$attachment1['url']."' alt='".esc_attr($attachment1['alt'])."' title='".esc_attr($attachment1['title'])."'/>";
            if($instance['link1']){
                echo "</a>";
            }
        }
        if(isset($attachment2)){
            if($instance['link2']){
                echo "<a href='".esc_attr($instance['link2'])."' target='".esc_attr($instance['target'])."'>";
            }
            echo "<img src='".$attachment2['url']."' alt='".esc_attr($attachment2['alt'])."' title='".esc_attr($attachment2['title'])."'/>";
            if($instance['link2']){
                echo "</a>";
            }
        }
        if(isset($attachment3)){
            if($instance['link3']){
                echo "<a href='".esc_attr($instance['link3'])."' target='".esc_attr($instance['target'])."'>";
            }
            echo "<img src='".$attachment3['url']."' alt='".esc_attr($attachment3['alt'])."' title='".esc_attr($attachment3['title'])."'/>";
            if($instance['link3']){
                echo "</a>";
            }
        }
        if(isset($attachment4)){
            if($instance['link4']){
                echo "<a href='".esc_attr($instance['link4'])."' target='".esc_attr($instance['target'])."'>";
            }
            echo "<img src='".$attachment4['url']."' alt='".esc_attr($attachment4['alt'])."' title='".esc_attr($attachment4['title'])."'/>";
            if($instance['link4']){
                echo "</a>";
            }
        }
        
        echo "</div>";
		echo $args['after_widget'];
	}
	

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        $instance['target'] = $new_instance['target'];
        $instance['attachment1'] = intval($new_instance['attachment1']);
        $instance['link1'] = strip_tags($new_instance['link1']);
        $instance['attachment2'] = intval($new_instance['attachment2']);
        $instance['link2'] = strip_tags($new_instance['link2']);
        $instance['attachment3'] = intval($new_instance['attachment3']);
        $instance['link3'] = strip_tags($new_instance['link3']);
        $instance['attachment4'] = intval($new_instance['attachment4']);
        $instance['link4'] = strip_tags($new_instance['link4']);
        
		return $instance;
	}

	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Advertise', KT_THEME_LANG), 'target' => '_self', 'link1' => '', 'attachment1' => '', 'link2' => '', 'attachment2' => '','link3' => '', 'attachment3' => '','link4' => '', 'attachment4' => '') );
        $title = strip_tags($instance['title']);
        
        $preview1 = $preview2 = $preview3 = $preview4 = false;
        $img_preview1 = $img_preview2 = $img_preview3 = $img_preview4 = "";
        
		$link1 = esc_attr( $instance['link1'] );
        $attachment1 = esc_attr( $instance['attachment1'] );
        if($instance['attachment1']){
            $file1 = get_thumbnail_attachment($instance['attachment1'], 'small');
            $preview1 = true;
            $img_preview1 = $file1['url'];
        }
		
        $link2 = esc_attr( $instance['link2'] );
        $attachment2 = esc_attr( $instance['attachment2'] );
        if($instance['attachment2']){
            $file2 = get_thumbnail_attachment($instance['attachment2'], 'small');
            $preview2 = true;
            $img_preview2 = $file2['url'];
        }
        
        $link3 = esc_attr( $instance['link3'] );
        $attachment3 = esc_attr( $instance['attachment3'] );
        if($instance['attachment3']){
            $file3 = get_thumbnail_attachment($instance['attachment3'], 'small');
            $preview3 = true;
            $img_preview3 = $file3['url'];
        }
        
        $link4 = esc_attr( $instance['link4'] );
        $attachment4 = esc_attr( $instance['attachment4'] );
        if($instance['attachment4']){
            $file4 = get_thumbnail_attachment($instance['attachment4'], 'small');
            $preview4 = true;
            $img_preview4 = $file4['url'];
        }
	?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <h4><?php _e('Image 1',KT_THEME_LANG); ?></h4>
        <div class="wrapper_kt_image_upload">
            <p style="text-align: center;">
                <input type="button" style="width: 100%; padding: 10px; height: auto;" class="button kt_image_upload" value="<?php esc_attr_e('Select your image 1', KT_THEME_LANG) ?>" />
                <input class="widefat kt_image_attachment" id="<?php echo $this->get_field_id('attachment1'); ?>" name="<?php echo $this->get_field_name('attachment1'); ?>" type="hidden" value="<?php echo esc_attr($attachment1); ?>" />
            </p>
            <p class="kt_image_preview" style="<?php if($preview1){ echo "display: block;";} ?>">
                <img src="<?php echo esc_url($img_preview1); ?>" alt="" class="kt_image_preview_img" />
            </p>
        </div>
        <p style="clear: both;">
            <label for="<?php echo $this->get_field_id('link1'); ?>"><?php _e('Link Ads 1:', KT_THEME_LANG); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('link1'); ?>" name="<?php echo $this->get_field_name('link1'); ?>" type="text" value="<?php echo esc_attr($link1); ?>" />
        </p>
        
        <h4><?php _e('Image 2',KT_THEME_LANG); ?></h4>
        <div class="wrapper_kt_image_upload">
            <p style="text-align: center;">
                <input type="button" style="width: 100%; padding: 10px; height: auto;" class="button kt_image_upload" value="<?php esc_attr_e('Select your image 2', KT_THEME_LANG) ?>" />
                <input class="widefat kt_image_attachment" id="<?php echo $this->get_field_id('attachment2'); ?>" name="<?php echo $this->get_field_name('attachment2'); ?>" type="hidden" value="<?php echo esc_attr($attachment2); ?>" />
            </p>
            <p class="kt_image_preview" style="<?php if($preview2){ echo "display: block;";} ?>">
                <img src="<?php echo esc_url($img_preview2); ?>" alt="" class="kt_image_preview_img" />
            </p>
        </div>
        <p style="clear: both;">
            <label for="<?php echo $this->get_field_id('link2'); ?>"><?php _e('Link Ads 2:', KT_THEME_LANG); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('link2'); ?>" name="<?php echo $this->get_field_name('link2'); ?>" type="text" value="<?php echo esc_attr($link2); ?>" />
        </p>
        
        <h4><?php _e('Image 3',KT_THEME_LANG); ?></h4>
        <div class="wrapper_kt_image_upload">
            <p style="text-align: center;">
                <input type="button" style="width: 100%; padding: 10px; height: auto;" class="button kt_image_upload" value="<?php esc_attr_e('Select your image 3', KT_THEME_LANG) ?>" />
                <input class="widefat kt_image_attachment" id="<?php echo $this->get_field_id('attachment3'); ?>" name="<?php echo $this->get_field_name('attachment3'); ?>" type="hidden" value="<?php echo esc_attr($attachment3); ?>" />
            </p>
            <p class="kt_image_preview" style="<?php if($preview3){ echo "display: block;";} ?>">
                <img src="<?php echo esc_url($img_preview3); ?>" alt="" class="kt_image_preview_img" />
            </p>
        </div>
        <p style="clear: both;">
            <label for="<?php echo $this->get_field_id('link3'); ?>"><?php _e('Link Ads 3:', KT_THEME_LANG); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('link3'); ?>" name="<?php echo $this->get_field_name('link3'); ?>" type="text" value="<?php echo esc_attr($link3); ?>" />
        </p>
        
        <h4><?php _e('Image 4',KT_THEME_LANG); ?></h4>
        <div class="wrapper_kt_image_upload">
            <p style="text-align: center;">
                <input type="button" style="width: 100%; padding: 10px; height: auto;" class="button kt_image_upload" value="<?php esc_attr_e('Select your image 4', KT_THEME_LANG) ?>" />
                <input class="widefat kt_image_attachment" id="<?php echo $this->get_field_id('attachment4'); ?>" name="<?php echo $this->get_field_name('attachment4'); ?>" type="hidden" value="<?php echo esc_attr($attachment4); ?>" />
            </p>
            <p class="kt_image_preview" style="<?php if($preview4){ echo "display: block;";} ?>">
                <img src="<?php echo esc_url($img_preview4); ?>" alt="" class="kt_image_preview_img" />
            </p>
        </div>
        <p style="clear: both;">
            <label for="<?php echo $this->get_field_id('link4'); ?>"><?php _e('Link Ads 4:', KT_THEME_LANG); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('link4'); ?>" name="<?php echo $this->get_field_name('link4'); ?>" type="text" value="<?php echo esc_attr($link4); ?>" />
        </p>
        <hr />
        <p>
			<label for="<?php echo $this->get_field_id('target'); ?>"><?php _e( 'Target:', KT_THEME_LANG); ?></label>
			<select name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>" class="widefat">
				<option value="_self"<?php selected( $instance['target'], '_self' ); ?>><?php _e('Stay in Window', KT_THEME_LANG); ?></option>
				<option value="_blank"<?php selected( $instance['target'], '_blank' ); ?>><?php _e('Open New Window', KT_THEME_LANG); ?></option>
			</select>
		</p>
<?php
	}

}


register_widget( 'WP_Widget_KT_Ads' );
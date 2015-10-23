<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_KT_Gallery_Grid extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'image_gallery' => '',
            'image_size' => 'full',
            'layout' => '',
            'margin_image' => 10,
            'gallery_popup' => 'true',
            'image_size_popup' => 'full',
            
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);
        
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wrapper-gallery-grid ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        if( $image_gallery ){ $image_gallery = explode( ',', $image_gallery ); }else{ $image_gallery = array(); }
        if( $gallery_popup == 'true' ){ $popup = ' popup-gallery'; }else{ $popup = ''; }
        $lightbox_image = $output = '';
        if( count($image_gallery) > 0 ){
            $output .= '<div class="'.esc_attr( $elementClass ).'">';
                $output .= '<div class="gallery-grid'.$popup.'" data-popup="'.esc_attr( $gallery_popup ).'" data-layout="'.esc_attr($layout).'" data-margin="'.esc_attr($margin_image).'">';
                    foreach ( $image_gallery as $attach_id ) {
                    	if ( $attach_id > 0 ) {
                    		$image = wp_get_attachment_image_src( $attach_id, $image_size );
                            if( $gallery_popup == 'true' ){
                                $image_popup = wp_get_attachment_image_src( $attach_id, $image_size_popup );
                                $lightbox_image = 'data-highres="'.$image_popup[0].'"';
                            }
                    	}
                        $output .= '<img src="'.$image[0].'" alt="" '.$lightbox_image.' />';
                    }
                $output .= '</div>';
            $output .= '</div>';
        }
        
        return $output;
    }
    
    public function singleParamHtmlHolder( $param, $value ) {
		$output = '';
		// Compatibility fixes
		$old_names = array( 'yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange' );
		$new_names = array( 'alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning' );
		$value = str_ireplace( $old_names, $new_names, $value );
		//$value = __($value, "js_composer");
		//
		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type = isset( $param['type'] ) ? $param['type'] : '';
		$class = isset( $param['class'] ) ? $param['class'] : '';

		if ( isset( $param['holder'] ) == true && $param['holder'] !== 'hidden' ) {
			$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
		}
		if ( $param_name == 'image_gallery' ) {
			$images_ids = empty( $value ) ? array() : explode( ',', trim( $value ) );
			$output .= '<ul class="attachment-thumbnails' . ( empty( $images_ids ) ? ' image-exists' : '' ) . '" data-name="' . $param_name . '">';
			foreach ( $images_ids as $image ) {
				$img = wpb_getImageBySize( array( 'attach_id' => (int)$image, 'thumb_size' => 'thumbnail' ) );
				$output .= ( $img ? '<li>' . $img['thumbnail'] . '</li>' : '<li><img width="150" height="150" test="' . $image . '" src="' . vc_asset_url( 'vc/blank.gif' ) . '" class="attachment-thumbnail" alt="" title="" /></li>' );
			}
			$output .= '</ul>';
			$output .= '<a href="#" class="column_edit_trigger' . ( ! empty( $images_ids ) ? ' image-exists' : '' ) . '">' . __( 'Add images', 'js_composer' ) . '</a>';

		}
		return $output;
	}
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "KT Gallery Grid", THEME_LANG),
    "base" => "kt_gallery_grid",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "", THEME_LANG),
    "params" => array(
        //Image
        array(
			'type' => 'attach_images',
			'heading' => __( 'Image Gallery', THEME_LANG ),
			'param_name' => 'image_gallery',
			'description' => __( 'Select image from media library.', 'js_composer' ),
		),
        array(
            "type" => "kt_image_sizes",
            "heading" => __( "Select image sizes", THEME_LANG ),
            "param_name" => "image_size",
            'std' => 'full'
        ),
        array(
            "type" => "textfield",
            "heading" => __("Layout", THEME_LANG),
            "param_name" => "layout",
            "description" => __( "Manually set a string of numbers to specify the number of images each row contains. Default: null generates a stacked layout of one image per row. Example: 131, 1212", THEME_LANG ),
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Margin", THEME_LANG),
            "param_name" => "margin_image",
            "value" => 10,
            "min" => 0,
            "max" => 50,
            "suffix" => "px",
            "description" => __( "Set the pixel width between the columns and rows. Default: 10px", THEME_LANG ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Gallery Popup', THEME_LANG ),
            'param_name' => 'gallery_popup',
            'value' => 'true',
            "description" => __("Use or don't use popup gallery.", THEME_LANG),
        ),
        array(
            "type" => "kt_image_sizes",
            "heading" => __( "Select image sizes popup gallery", THEME_LANG ),
            "param_name" => "image_size_popup",
            "std" => "full",
            "dependency" => array("element" => "gallery_popup","value" => array('true'),'not_empty' => true,),
        ),
        
        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        //Design options
        array(
            'type' => 'css_editor',
            'heading' => __( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => __( 'Design options', 'js_composer' )
        ),

    ),
));
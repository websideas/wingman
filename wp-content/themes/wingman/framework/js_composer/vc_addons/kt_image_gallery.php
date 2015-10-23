<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_KT_Image_Gallery extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'images' => '',
            'image_size' => '',
            'margin_thumbnail' => 5,
            
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);
        
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt_image_gallery fotorama ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        if( $images ){ $images = explode( ',', $images ); }else{ $images = array(); }
        
        $output = '';
        
        if( count($images) > 0 ){
            $output .= '<div class="'.esc_attr( $elementClass ).'" data-nav="thumbs" data-thumbmargin ="'.$margin_thumbnail.'">';
                foreach( $images as $attach_id ){
                    $image = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => $image_size ) );
                    $output .= $image['thumbnail'];
                }
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
		if ( $param_name == 'images' ) {
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
    "name" => __( "KT Image Gallery", THEME_LANG),
    "base" => "kt_image_gallery",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "", THEME_LANG),
    "params" => array(
        //Image
        array(
			'type' => 'attach_images',
			'heading' => __( 'Image Gallery', THEME_LANG ),
			'param_name' => 'images',
			'description' => __( 'Select image from media library.', 'js_composer' ),
            
		),
        array(
            "type" => "kt_image_sizes",
            "heading" => __( "Select image sizes", THEME_LANG ),
            "param_name" => "image_size",
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Thumbnail Margin", THEME_LANG),
            "param_name" => "margin_thumbnail",
            "value" => 5,
            "min" => 0,
            "max" => 50,
            "suffix" => "px",
            "description" => __( "Decide the margins between the images.", THEME_LANG ),
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
<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Product_Category_Banner extends WPBakeryShortCode {
    
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'product_category' => '',
            'image_size' => 'full',
            'position' => 'position-center',

            'css' => '',
            'css_animation' => '',
            'el_class' => '',
        ), $atts );

        extract($atts);
        $output = '';
        
        $elementClass = array(
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'position' => $position,
        );

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        if( $product_category && function_exists( 'get_woocommerce_term_meta' )){
            $term = get_term( $product_category, 'product_cat' );
            $link = get_term_link( $term->slug, 'product_cat' );

            $thumbnail_id = get_woocommerce_term_meta( $product_category, 'thumbnail_id', true );
            $image = wp_get_attachment_image( $thumbnail_id, $image_size, false, array( 'class' => 'vc_single_image-img img-responsive attachment-'.$image_size, 'alt' => $term->name ) );
            
            if( !$image ){ $image = '<img class="vc_img-placeholder vc_single_image-img img-responsive" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />'; }
            
            $output .= '<div class="banner '.esc_attr( $elementClass ).'">'.$image;
                $output .= '<div class="banner-content"><div class="content_banner"><a class="btn btn-light btn-block btn-animation" href="'.esc_attr( $link ).'"><span>'.$term->name.'('.$term->count.') <i class="fa fa-long-arrow-right"></i></span></a></div></div>';
            $output .= '</div>';
        }else{
            $output .= '<p>'.esc_html__( 'No product category','wingman' ).'</p>';
        }
    	return $output;
    }

}

vc_map( array(
    "name" => esc_html__( "Product Category Banner", 'wingman'),
    "base" => "product_category_banner",
    "category" => esc_html__('by Theme', 'wingman' ),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'product_cat',
            'heading' => esc_html__( 'Product Category', 'wingman' ),
            'param_name' => 'product_category',
            'placeholder' => esc_html__( 'Select your Product Category', 'wingman' ),
            'multiple' => false,
            "admin_label" => true,
        ),
        array(
            "type" => "kt_image_sizes",
            "heading" => esc_html__( "Select image sizes", 'wingman' ),
            "param_name" => "image_size",
            "std" => 'full',
            "admin_label" => true,
        ),
        
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Content Position', 'wingman' ),
            'param_name' => 'position',
            'value' => array(
                esc_html__( 'Top', 'wingman' ) => 'position-top',
                esc_html__( 'Center', 'wingman' ) => 'position-center',
                esc_html__( 'Bottom', 'wingman' ) => 'position-bottom',
            ),
            'std' => 'position-center',
            'description' => esc_html__( 'Position of content.', 'wingman' ),
            "admin_label" => true,
        ),

        array(
        	'type' => 'dropdown',
        	'heading' => esc_html__( 'CSS Animation', 'js_composer' ),
        	'param_name' => 'css_animation',
        	'value' => array(
        		esc_html__( 'No', 'js_composer' ) => '',
        		esc_html__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
        		esc_html__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
        		esc_html__( 'Left to right', 'js_composer' ) => 'left-to-right',
        		esc_html__( 'Right to left', 'js_composer' ) => 'right-to-left',
        		esc_html__( 'Appear from center', 'js_composer' ) => "appear"
        	),
            "admin_label" => true,
        	'description' => esc_html__( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        array(
			'type' => 'css_editor',
			'heading' => esc_html__( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => esc_html__( 'Design options', 'js_composer' )
		),
    ),
));
<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Blockquote extends WPBakeryShortCode {
    var $excerpt_length;
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'author' => '',
            'style' => '',
            'reverse' => 'false',
            'css' => '',
            'css_animation' => '',
            'el_class' => '',
        ), $atts );

        extract($atts);
        $output = '';

        $reverse = apply_filters('sanitize_boolean', $reverse);

        $elementClass = array(
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'reverse' => ( $reverse) ? 'blockquote-reverse' : '',
            'style' => $style
        );

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        $output .= '<blockquote class="'.esc_attr( $elementClass ).'"><div class="blockquote-content">';
            $output .= '<p>'. do_shortcode($content).'</p>';
            if( $author ){
                $output .= '<footer>'.$author.'</footer>';
            }
        $output .= '</div></blockquote>';
        
    	return $output;
    }
}

vc_map( array(
    "name" => __( "Blockquote", THEME_LANG),
    "base" => "blockquote",
    "category" => __('by Theme', THEME_LANG ),
    "wrapper_class" => "clearfix",
    "params" => array(

        array(
            "type" => "textarea_html",
            "heading" => __("Content", THEME_LANG),
            "param_name" => "content",
            "description" => __("", THEME_LANG),
            'holder' => 'div',
        ),
        array(
            "type" => "textfield",
            'heading' => __( 'Author', 'js_composer' ),
            'param_name' => 'author',
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Style', THEME_LANG ),
            'param_name' => 'style',
            'value' => array(
                __( 'Normal', THEME_LANG ) => '',
                __( 'Classic', THEME_LANG ) => 'classic',
                __( 'Simple', THEME_LANG ) => 'simple',
            ),
            'description' => __( 'Position of content.', THEME_LANG ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Reverse Blockquote', THEME_LANG ),
            'param_name' => 'reverse',
            'value' => 'false',
            "description" => __("", THEME_LANG),
        ),
        
        array(
        	'type' => 'dropdown',
        	'heading' => __( 'CSS Animation', 'js_composer' ),
        	'param_name' => 'css_animation',
        	'admin_label' => true,
        	'value' => array(
        		__( 'No', 'js_composer' ) => '',
        		__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
        		__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
        		__( 'Left to right', 'js_composer' ) => 'left-to-right',
        		__( 'Right to left', 'js_composer' ) => 'right-to-left',
        		__( 'Appear from center', 'js_composer' ) => "appear"
        	),
        	'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
        ),
        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        array(
			'type' => 'css_editor',
			'heading' => __( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => __( 'Design options', 'js_composer' )
		),
    ),
));
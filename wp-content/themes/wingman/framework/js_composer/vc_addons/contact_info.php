<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Contact_Info extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract( shortcode_atts( array(
            'title' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'el_class' => '',
            'css' => ''
        ), $atts ) );
        
        $el_class = $this->getExtraClass($el_class);
        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'contact-info-wrapper ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
        
        $output = '';
        $output .= '<div class="'.esc_attr( $css_class ).'">';
            $output .= ($title) ? '<h3 class="title_block">'.$title.'</h3>' : '';
            $output .= '<ul class="contact-info-ul">';
            	$output .= ($address) ? '<li><i class="fa fa-map-marker"></i>'.$address.'</li>' : '';
                $output .= ($phone) ? '<li><i class="fa fa-phone"></i>'.$phone.'</li>' : '';
                $output .= ($email) ? '<li><i class="fa fa-envelope"></i>'.$email.'</li>' : '';
            $output .= '</ul>';
        $output .= '</div>';
        
    	return $output;
    }
}

vc_map( array(
    "name" => esc_html__( "Contact info", 'wingman'),
    "base" => "contact_info",
    "category" => esc_html__('by Theme', 'wingman' ),
    "description" => esc_html__( "Contact info", 'wingman'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Title", 'wingman' ),
            "param_name" => "title",
            "description" => '',
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Address", 'wingman' ),
            "param_name" => "address",
            "description" => "",
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Phone", 'wingman' ),
            "param_name" => "phone",
            "description" => "",
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Email", 'wingman' ),
            "param_name" => "email",
            "description" => "",
            "admin_label" => true,
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
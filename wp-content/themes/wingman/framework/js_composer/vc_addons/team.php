<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_Team extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        extract(shortcode_atts(array(
            'team' => '',
            'el_class' => '',
        ), $atts));

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'team ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );


        

        return $output;
    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Team", THEME_LANG),
    "base" => "team",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "", THEME_LANG),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => __( 'Name', 'js_composer' ),
            'param_name' => 'name',
            "admin_label" => true,
        ),

        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
    ),
));
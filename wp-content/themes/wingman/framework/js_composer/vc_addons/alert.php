<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_KT_Alert extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        extract(shortcode_atts(array(
            'title' => '',
            "type" => 'normal',
            "close" => 'false',
            'style' => 'classic',
            'el_class' => '',
        ), $atts));

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'alert ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'type' => 'alert-'.$type,
            'style' => 'alert-style-'.$style
        );
        if($close == 'true'){
            $elementClass['dismissible'] = 'alert-dismissible fade in';
        }
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );


        $output = '';
        $output .= '<div class="'.esc_attr( $elementClass ).'" role="alert">';
        if($close == 'true'){
            $output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>';
        }

        if($title){
            $output .= '<h3 class="alert_title">'.$title.'</h3>';
        }
        $output .= $content;

        $output .= '</div><!-- .alert -->';

        return $output;
    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Alert", THEME_LANG),
    "base" => "kt_alert",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "Alert", THEME_LANG),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => __( 'Title', 'js_composer' ),
            'param_name' => 'title',
            "admin_label" => true,
        ),
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => __( 'Text', 'js_composer' ),
            'param_name' => 'content',
            'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' )
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Type",THEME_LANG),
            "param_name" => "type",
            "value" => array(
                __('Normal', THEME_LANG) => 'normal',
                __('Success', THEME_LANG) => 'success',
                __('Info', THEME_LANG) => 'info',
                __('Warning', THEME_LANG) => 'warning',
                __('Danger', THEME_LANG) => 'danger',
            ),
            "description" => __("",THEME_LANG),
            "admin_label" => true,
        ),



        array(
            "type" => "dropdown",
            "heading" => __("Style",THEME_LANG),
            "param_name" => "style",
            "value" => array(
                __('Classic', THEME_LANG) => 'classic',
                __('Modern', THEME_LANG) => 'modern',
            ),
            "admin_label" => true,
            "description" => __("",THEME_LANG)
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Close button', THEME_LANG ),
            'param_name' => 'close',
            'value' => 'false',
            "description" => __("Close button in alert", THEME_LANG)
        ),
        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
    ),
));
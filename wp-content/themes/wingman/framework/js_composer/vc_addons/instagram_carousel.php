<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Instagram_Carousel extends WPBakeryShortCode {
    private $client_id;
    private $access_token;
    private $username;
    private $userid;
    
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'number' => 10,
            
            'margin' => 0,
            'autoheight' => 'true',
            'autoplay' => 'false',
            'mousedrag' => 'true',
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'desktop' => 4,
            'tablet' => 2,
            'mobile' => 1,

            'navigation' => 'true',
            'navigation_always_on' => 'false',
            'navigation_position' => 'center',
            'navigation_style' => 'circle_border',
            'navigation_border_width' => '1',
            'navigation_border_color' => '',
            'navigation_background' => '',
            'navigation_color' => '',
            'navigation_icon' => 'fa fa-angle-left|fa fa-angle-right',

            'pagination' => 'false',
            'pagination_color' => '',
            'pagination_icon' => 'circle-o',
            'pagination_position' => 'outside',

            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts);

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'instagram-carousel-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );

        $output = $instagram_html = '';
        
        $this->client_id = get_option( 'kt_instagram_client_id' );
        $this->access_token = get_option('kt_instagram_access');
        $this->username = get_option('kt_instagram_username');
        $this->userid = get_option('kt_instagram_userid');
        
        if($this->access_token && $this->username ){
            
            require_once ( FW_CLASS . 'instagram-api.php' );
            
            $kt_instagram = new KT_Instagram();
            $data = $kt_instagram->getUserMedia( array('count' => $number ));

            if(!empty($data)){
                $carousel_ouput = kt_render_carousel(apply_filters( 'kt_render_args', $atts));
                
                $instagram_html .= $kt_instagram->showInstagramCarousel($data);
            }else{
                $instagram_html .= '<strong>'.__( 'Empty username or access token', THEME_LANG ).'</strong>';
            }

            
        }else{
            $instagram_html .= '<strong>'.__( 'Please fill all widget settings!', THEME_LANG ).'</strong>';
        }
        $output .= str_replace('%carousel_html%', $instagram_html, $carousel_ouput);
        
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}

$navigation_icon = apply_filters('navigation_icon', array(
    '<span><i class="fa fa-angle-left"></i><i class="fa fa-angle-right"></i></span>' => 'fa fa-angle-left|fa fa-angle-right',
    '<span><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-right"></i></span>' => 'fa fa-chevron-left|fa fa-chevron-right',
    '<span><i class="fa fa-angle-double-left"></i><i class="fa fa-angle-double-right"></i></span>' => 'fa fa-angle-double-left|fa fa-angle-double-right',
    '<span><i class="fa fa-arrow-left"></i><i class="fa fa-arrow-right"></i></span>' => 'fa fa-arrow-left|fa fa-arrow-right',
    '<span><i class="fa fa-chevron-circle-left"></i><i class="fa fa-chevron-circle-right"></i></span>' =>'fa fa-chevron-circle-left|fa fa-chevron-circle-right',
    '<span><i class="fa fa-arrow-circle-o-left"></i><i class="fa fa-arrow-circle-o-right"></i></span>' => 'fa fa-arrow-circle-o-left|fa fa-arrow-circle-o-right',
));


$pagination_icon = apply_filters('pagination_icon', array(
    '<i class="fa fa-circle-o"></i>' => 'circle-o',
    '<i class="fa fa-circle"></i>' => 'circle',
    '<i class="fa fa-circle-thin"></i>' => 'circle-thin',
    '<i class="fa fa-dot-circle-o"></i>' => 'dot-circle-o',
    '<i class="fa fa-square-o"></i>' => 'square-o',
    '<i class="fa fa-square"></i>' => 'square',
    '<i class="fa fa-stop"></i>' => 'stop',
));

vc_map( array(
    "name" => __( "Instagram Carousel", THEME_LANG),
    "base" => "instagram_carousel",
    "category" => __('by Theme', THEME_LANG ),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __( "Number of image to show:", THEME_LANG ),
            "param_name" => "number",
            'admin_label' => true,
            'std' => 10
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
        // Carousel
        array(
            "type" => "kt_number",
            "heading" => __("Margin", THEME_LANG),
            "param_name" => "margin",
            "value" => "0",
            "suffix" => __("px", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG ),
            'description' => __( 'margin-right on item.', THEME_LANG ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Auto Height', THEME_LANG ),
            'param_name' => 'autoheight',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => __("Enable auto height.", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Mouse Drag', THEME_LANG ),
            'param_name' => 'mousedrag',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => __("Mouse drag enabled.", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'AutoPlay', THEME_LANG ),
            'param_name' => 'autoplay',
            'value' => 'false',
            "description" => __("Enable auto play.", THEME_LANG),
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "heading" => __("AutoPlay Speed", THEME_LANG),
            "param_name" => "autoplayspeed",
            "value" => "5000",
            "suffix" => __("milliseconds", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "autoplay","value" => array('true')),
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Slide Speed", THEME_LANG),
            "param_name" => "slidespeed",
            "value" => "200",
            "suffix" => __("milliseconds", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_heading",
            "heading" => __("Items to Show?", THEME_LANG),
            "param_name" => "items_show",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "heading" => __("On Desktop", THEME_LANG),
            "param_name" => "desktop",
            "value" => "4",
            "min" => "1",
            "max" => "25",
            "step" => "1",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "heading" => __("On Tablet", THEME_LANG),
            "param_name" => "tablet",
            "value" => "2",
            "min" => "1",
            "max" => "25",
            "step" => "1",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "heading" => __("On Mobile", THEME_LANG),
            "param_name" => "mobile",
            "value" => "1",
            "min" => "1",
            "max" => "25",
            "step" => "1",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_heading",
            "heading" => __("Navigation settings", THEME_LANG),
            "param_name" => "navigation_settings",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Navigation', THEME_LANG ),
            'param_name' => 'navigation',
            'value' => 'true',
            "description" => __("Show navigation in carousel", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation position', THEME_LANG ),
            'param_name' => 'navigation_position',
            'group' => __( 'Carousel', THEME_LANG ),
            'std' => 'center',
            'value' => array(
                __( 'Center outside', THEME_LANG) => 'center_outside',
                __( 'Center inside', THEME_LANG) => 'center',
                __( 'Top Right', THEME_LANG) => 'top_right',
                __( 'Bottom', THEME_LANG) => 'bottom',
            ),
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Always Show Navigation', THEME_LANG ),
            'param_name' => 'navigation_always_on',
            'value' => 'false',
            "description" => __("Always show the navigation.", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "navigation_position","value" => array('center', 'center_outside', 'top_right')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation style', 'js_composer' ),
            'param_name' => 'navigation_style',
            'group' => __( 'Carousel', THEME_LANG ),
            'value' => array(
                __( 'Normal', THEME_LANG ) => '',
                __( 'Circle Background', THEME_LANG ) => 'circle',
                __( 'Square Background', THEME_LANG ) => 'square',
                __( 'Round Background', THEME_LANG ) => 'round',
                __( 'Circle Border', THEME_LANG ) => 'circle_border',
                __( 'Square Border', THEME_LANG ) => 'square_border',
                __( 'Round Border', THEME_LANG ) => 'round_border',
            ),
            'std' => 'circle_border',
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Navigation Background', THEME_LANG ),
            'param_name' => 'navigation_background',
            'description' => __( 'Select background for navigation.', THEME_LANG ),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "navigation_style","value" => array('circle', 'square', 'round')),
        ),
        array(
            'type' => 'kt_number',
            'heading' => __( 'Border width', THEME_LANG ),
            'param_name' => 'navigation_border_width',
            "value" => "1",
            "min" => "1",
            "max" => "10",
            "suffix" => __("px", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "navigation_style","value" => array('circle_border', 'square_border', 'round_border')),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Border color', THEME_LANG ),
            'param_name' => 'navigation_border_color',
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "navigation_style","value" => array('circle_border', 'square_border', 'round_border')),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Navigation color', THEME_LANG ),
            'param_name' => 'navigation_color',
            'description' => __( 'Select color for navigation.', 'js_composer' ),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'kt_radio',
            'heading' => __( 'Navigation Icon', 'js_composer' ),
            'param_name' => 'navigation_icon',
            'class_input' => "radio-wrapper-group",
            'value' => $navigation_icon,
            'description' => __( 'Select your style for navigation.', THEME_LANG ),
            "dependency" => array("element" => "navigation","value" => array('true')),
            'group' => __( 'Carousel', THEME_LANG )
        ),

        array(
            "type" => "kt_heading",
            "heading" => __("Pagination settings", THEME_LANG),
            "param_name" => "pagination_settings",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Pagination', THEME_LANG ),
            'param_name' => 'pagination',
            'value' => 'false',
            "description" => __("Show pagination in carousel", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Pagination color', 'js_composer' ),
            'param_name' => 'pagination_color',
            'description' => __( 'Select color for pagination.', 'js_composer' ),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "pagination","value" => array('true')),
        ),
        array(
            'type' => 'kt_radio',
            'heading' => __( 'Pagination Icon', 'js_composer' ),
            'param_name' => 'pagination_icon',
            'class_input' => "radio-wrapper",
            'value' => $pagination_icon,
            'description' => __( 'Select your style for pagination.', THEME_LANG ),
            "dependency" => array("element" => "pagination","value" => array('true')),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Pagination position', THEME_LANG ),
            'param_name' => 'pagination_position',
            'group' => __( 'Carousel', THEME_LANG ),
            'value' => array(
                __( 'Outside', THEME_LANG) => 'outside',
                __( 'Inside', THEME_LANG) => 'inside',
            ),
            'description' => '',
            "dependency" => array("element" => "pagination","value" => array('true')),
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


<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Skill extends WPBakeryShortCodesContainer {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(

            'style' => 'before',
            'options' => '',
            'height' => 6,
            'boxbgcolor' => '#e3e3e3',

            'padding_box' => '',
            'padding_left' => '',
            'padding_right' => '',

            'border_style' => '',
            'border_color' => '',
            'border_size' => '',
            'border_radius' => '',

            'el_class' => '',
            'css' => '',
        ), $atts );

        extract( $atts );

        global $skill_style, $skill_boxbgcolor, $skill_padding_box, $skill_options, $skill_height, $skill_padding_left, $skill_padding_right, $skill_border_style, $skill_border_color, $skill_border_size, $skill_border_radius;

        $skill_style = $style;
        $skill_boxbgcolor = $boxbgcolor;
        $skill_padding_box = $padding_box;
        $skill_options = $options;
        $skill_padding_left = $padding_left;
        $skill_padding_right = $padding_right;
        $skill_height = $height;
        $skill_border_style = $border_style;
        $skill_border_color = $border_color;
        $skill_border_size = $border_size;
        $skill_border_radius = $border_radius;


        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-skill-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div class="'.esc_attr( $elementClass ).'">'.do_shortcode($content).'</div>';
    }
}

class WPBakeryShortCode_Skill_Item extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => __( 'Title', 'js_composer' ),
            'percent' => 88,

            'use_theme_fonts' => '',
            'font_container' => '',
            'google_fonts' => '',

            'bgcolor' => 'accent',
            'custombgcolor' => '',
            'gradient_start' => '',
            'gradient_end' => '',




            'el_class' => '',
            'css' => '',
        ), $atts );



        global $skill_style, $skill_boxbgcolor, $skill_padding_box, $skill_options, $skill_height, $skill_padding_left, $skill_padding_right, $skill_border_style, $skill_border_color, $skill_border_size, $skill_border_radius;



        extract( $atts );

        $output = '';
        $style_skill_arr = $style_bar_arr = $style_text_arr = array();

        $bar_options = '';
        $skill_options_arr = explode( ",", $skill_options );
        if ( in_array( "animated", $skill_options_arr ) ) {
            $bar_options .= " animated";
        }
        if ( in_array( "striped", $skill_options_arr ) ) {
            $bar_options .= " striped";
        }

        if($skill_border_style){
            $style_skill_arr[] = "border: ".$skill_border_size.'px '.$skill_border_style.' '.$skill_border_color;
        }
        if($skill_border_radius && $skill_border_style){
            $style_skill_arr[] = $style_bar_arr[] = 'border-radius: '.$skill_border_radius.'px';
            $style_skill_arr[] = $style_bar_arr[] = '-webkit-border-radius: '.$skill_border_radius.'px';
            $style_skill_arr[] = $style_bar_arr[] = '-moz-border-radius: '.$skill_border_radius.'px';
        }
        if($skill_padding_box && $skill_border_style){
            $style_skill_arr[] = 'padding: '.$skill_padding_box.'px';
        }
        if($skill_boxbgcolor){
            $style_skill_arr[] = 'background-color: '.$skill_boxbgcolor;
        }

        $style_skill = '';
        if ( ! empty( $style_skill_arr ) ) {
            $style_skill = 'style="' . esc_attr( implode( ';', $style_skill_arr ) ) . '"';
        }

        if($bgcolor == 'custom'){
            $style_bar_arr[] = 'background-color: '.$custombgcolor;
        }elseif($bgcolor == 'gradient'){
            $style_bar_arr[] = "background: {$gradient_start}";
            $style_bar_arr[] = "background: -moz-linear-gradient(left,  {$gradient_start} 0%, {$gradient_end} 100%)";
            $style_bar_arr[] = "background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,{$gradient_start}), color-stop(100%,{$gradient_end}))";
            $style_bar_arr[] = "background: -webkit-linear-gradient(left,  {$gradient_start} 0%,{$gradient_end} 100%)";
            $style_bar_arr[] = "background: -o-linear-gradient(left,  {$gradient_start} 0%,{$gradient_end} 100%)";
            $style_bar_arr[] = "background: -ms-linear-gradient(left,  {$gradient_start} 0%,{$gradient_end} 100%)";
            $style_bar_arr[] = "background: linear-gradient(to right,  {$gradient_start} 0%,{$gradient_end} 100%)";
            $style_bar_arr[] = "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$gradient_start}', endColorstr='{$gradient_end}',GradientType=1 )";
        }
        //$style_bar_arr[] = 'width: '.$percent.'%';
        if($skill_style =='before' || $skill_style =='after'){
            $style_bar_arr[] = 'height: '.$skill_height.'px';
        }

        $style_bar = '';
        if ( ! empty( $style_bar_arr ) ) {
            $style_bar = 'style="' . esc_attr( implode( ';', $style_bar_arr ) ) . '"';
        }



        extract( $this->getAttributes( $atts ) );



        unset($font_container_data['values']['text_align']);

        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );


        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        $subsets = '';
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        }
        if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }


        if ( ! empty( $styles ) ) {
            $style_text_arr = array_merge($style_text_arr, $styles);
        }


        if($skill_style =='inner'){
            $style_text_arr[] = 'line-height: '.$skill_height.'px';

            if($skill_padding_left ){
                $style_text_arr[] = 'padding-left: '.$skill_padding_left.'px';
            }
            if($skill_padding_right){
                $style_text_arr[] = 'padding-right: '.$skill_padding_right.'px';
            }

        }

        $style_text = '';
        if ( ! empty( $style_text_arr ) ) {
            $style_text = 'style="' . esc_attr( implode( ';', $style_text_arr ) ) . '"';
        }

        $text = "<h5 class='kt-skill-text' {$style_text}>{$title} <span>$percent%</span></h5>";
        $bar = "<div class='kt-skill-bar ".esc_attr($bar_options)."' {$style_bar} data-percent='{$percent}'><span></span></div>";
        //$bar .= "<div class='kt-skill-bar-stripe' data-percent='{$percent}'></div>";

        if($skill_style == 'inner'){
            $output .= "<div class='kt-skill-bg kt-skill-bg-".$bgcolor."' ".$style_skill."><div class='kt-skill-content'>".$text.$bar."</div></div>";
        }elseif($skill_style == 'before'){
            $output .= "$text<div class='kt-skill-bg kt-skill-bg-{$bgcolor}' {$style_skill}>$bar</div>";
        }else{
            $output .= "<div class='kt-skill-bg kt-skill-bg-{$bgcolor}' {$style_skill}>$bar</div>$text";
        }

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-skill-item-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'style' => 'kt-style-'.$skill_style,
            'bgcolor' => 'kt-bgcolor-'.$bgcolor
        );

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div class="'.esc_attr( $elementClass ).'"><div class="kt-skill-content">'.$output.'</div></div>';
    }
}


vc_map( array(
    "name" => __("Skill", THEME_LANG),
    "base" => "skill",
    "category" => __('by Theme', THEME_LANG ),
    "as_parent" => array('only' => 'skill_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
    "content_element" => true,
    "show_settings_on_create" => false,
    "params" => array(

        array(
            'type' => 'dropdown',
            'heading' => __( 'Style', 'js_composer' ),
            'param_name' => 'style',
            'value' => array(
                __( 'Title before skill', 'js_composer' ) => 'before',
                __( 'Title after skill', 'js_composer' ) => 'after',
                __( 'Title inner skill', 'js_composer' ) => 'inner',
            ),
            'description' => __( 'Select skill background color.', 'js_composer' ),
            'admin_label' => true,
        ),


        array(
            'type' => 'checkbox',
            'heading' => __( 'Options', 'js_composer' ),
            'param_name' => 'options',
            'value' => array(
                __( 'Add Stripes?', 'js_composer' ) => 'striped',
                __( 'Add animation? Will be visible with striped bars.', 'js_composer' ) => 'animated'
            ),
        ),

        array(
            "type" => "kt_heading",
            "heading" => __("Box style", "js_composer"),
            "param_name" => "box_style",
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Background color', 'js_composer' ),
            'param_name' => 'boxbgcolor',
            'description' => __( 'Select background color for box.', 'js_composer' ),
            "value" => '#e3e3e3',
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __("Border Style", 'js_composer'),
            "param_name" => "border_style",
            "value" => array(
                "None"=> "",
                "Solid"=> "solid",
                "Dashed" => "dashed",
                "Dotted" => "dotted",
                "Double" => "double",
                "Inset" => "inset",
                "Outset" => "outset",
            ),
            "description" => "",
        ),
        array(
            "type" => "colorpicker",
            "class" => "",
            "heading" => __("Border Color", 'js_composer'),
            "param_name" => "border_color",
            "value" => "",
            "description" => "",
            "dependency" => array("element" => "border_style", "not_empty" => true),
        ),

        array(
            "type" => "kt_number",
            "class" => "",
            "heading" => __("Border Width", 'js_composer'),
            "param_name" => "border_size",
            "value" => 1,
            "min" => 1,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            "dependency" => array("element" => "border_style", "not_empty" => true),
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "heading" => __("Border Radius",'js_composer'),
            "param_name" => "border_radius",
            "value" => 3,
            "min" => 0,
            "max" => 500,
            "suffix" => "px",
            "description" => "",
            "dependency" => array("element" => "border_style", "not_empty" => true),
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "heading" => __("Padding", 'js_composer'),
            "param_name" => "padding_box",
            "value" => 2,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "Don't use for Title inner box",
            "dependency" => array("element" => "border_style", "not_empty" => true),
        ),

        array(
            "type" => "kt_heading",
            "heading" => __("Skill style", "js_composer"),
            "param_name" => "skill_style",
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "heading" => __("Bar Height", 'js_composer'),
            "param_name" => "height",
            "value" => 6,
            "suffix" => "px",
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Padding left",'js_composer'),
            "param_name" => "padding_left",
            "suffix" => "px",
            'value' => 0,
            'edit_field_class' => 'vc_col-sm-4 vc_column',
            'description' => '&nbsp;',
            'dependency' => array( 'element' => 'style', 'value' => array( 'inner' ) ),
        ),

        array(
            "type" => "kt_number",
            "heading" => __("Padding right",'js_composer'),
            "param_name" => "padding_right",
            "suffix" => "px",
            'value' => 0,
            'edit_field_class' => 'vc_col-sm-4 vc_column',
            'description' => '&nbsp;',
            'dependency' => array( 'element' => 'style', 'value' => array( 'inner' ) ),
        ),
        array(
            "type" => "kt_heading",
            "heading" => __("Other", THEME_LANG),
            "param_name" => "other_",
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        ),


        array(
            'type' => 'css_editor',
            'heading' => __( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => __( 'Design options', 'js_composer' )
        ),
    ),
    "js_view" => 'VcColumnView'
) );
// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Skill Item", THEME_LANG),
    "base" => "skill_item",
    "as_child" => array('only' => 'skill'),
    "content_element" => true,
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => __( 'Title', 'js_composer' ),
            'param_name' => 'title',
            'value' => __( 'Title', 'js_composer' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'hidden',
            'heading' => __( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Percent", "js_composer"),
            "param_name" => "percent",
            "admin_label" => true,
            "value" => 88,
            "min" => 1,
            "max" => 100,
            "suffix" => "%",
        ),

        /** ----------- Design options ------------------- */



        array(
            'type' => 'dropdown',
            'heading' => __( 'Background', 'js_composer' ),
            'param_name' => 'bgcolor',
            'value' => array(
                __( 'Accent', 'js_composer' ) => 'accent',
                __( 'Custom Color', 'js_composer' ) => 'custom',
                __( 'Custom Gradient', 'js_composer' ) => 'gradient',
            ),
            'description' => __( 'Select skill background color.', 'js_composer' ),
            'admin_label' => true,
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom background color', 'js_composer' ),
            'param_name' => 'custombgcolor',
            'description' => __( 'Select custom background color for skill.', 'js_composer' ),
            'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'custom' ) ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Background color start', 'js_composer' ),
            'param_name' => 'gradient_start',
            'class' => 'gradient_start',
            'description' => __( 'Select custom background color for skill.', 'js_composer' ),
            'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'gradient' ) ),
            'edit_field_class' => 'vc_col-sm-6 vc_column',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Background color end', 'js_composer' ),
            'param_name' => 'gradient_end',
            'class' => 'gradient_end',
            'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'gradient' ) ),
            'description' => __( 'Select custom background color for skill.', 'js_composer' ),
            'edit_field_class' => 'vc_col-sm-6 vc_column',
        ),

        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_size',
                    'color',

                    'tag_description' => __( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => __( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => __( 'Enter line height.', 'js_composer' ),
                    'color_description' => __( 'Select heading color.', 'js_composer' ),
                ),
            ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'js_composer' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => 'font_family:Montserrat|font_style:400%20regular%3A400%3Anormal',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => __( 'Select font family.', 'js_composer' ),
                    'font_style_description' => __( 'Select font styling.', 'js_composer' )
                )
            ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
        ),

        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer" ),
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
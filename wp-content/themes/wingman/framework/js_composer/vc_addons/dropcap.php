<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Dropcap extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => __( 'D', KT_THEME_LANG ),
            'size' => 'md',
            'font_container' => '',
            'border_radius' => '0',
            'accent_background' => 'true',
            'custom_background' => '',
            'use_theme_fonts' => '',
            'google_fonts' => '',

            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $accent_background = apply_filters('sanitize_boolean', $accent_background);
        if($accent_background){
            $custom_background = kt_option('styling_accent');
        }
        $style_title = '';

        extract( $this->getAttributes( $atts ) );
        unset($font_container_data['values']['text_align']);

        $styles = array();
        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        $subsets = '';
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        }
        if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }
        
        
        if($border_radius){
            $styles[] = 'border-radius: '.$border_radius.'px;';
        }
        if($custom_background){
            $styles[] = 'background: '.$custom_background;
        }
        if ( ! empty( $styles ) ) {
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }
        
        $elementClass = array(
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'size' => 'dropcap-'.$size,
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        return '<span class="kt_dropcap '.$elementClass.'" '.$style_title.'>'.$title.'</span>';
    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Dropcap", KT_THEME_LANG),
    "base" => "dropcap",
    "category" => __('by Theme', KT_THEME_LANG ),
    "description" => __( "", KT_THEME_LANG),
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => __( 'First Letter', 'js_composer' ),
            'param_name' => 'title',
            'value' => __( 'D', KT_THEME_LANG ),
            "admin_label" => true,
        ),
        array(
            'type' => 'hidden',
            'heading' => __( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Size', 'js_composer' ),
            'param_name' => 'size',
            'value' => getVcShared( 'sizes' ),
            'std' => 'md',
            'description' => __( 'Dropcap size.', KT_THEME_LANG ),
            "admin_label" => true,
        ),
        
        //Typography settings
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => 'color:#FFFFFF',
            'settings' => array(
                'fields' => array(
                    //'tag' => 'h2', // default value h2
                    'font_size',
                    //'line_height',
                    'color',
                    'tag_description' => __( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => __( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => __( 'Enter line height.', 'js_composer' ),
                    'color_description' => __( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => __( 'Typography', KT_THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Border Radius", KT_THEME_LANG),
            "param_name" => "border_radius",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => __( 'Typography', KT_THEME_LANG ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Use Accent Background Color', KT_THEME_LANG ),
            'param_name' => 'accent_background',
            'value' => 'true',
            'group' => __( 'Typography', KT_THEME_LANG ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom Background', 'js_composer' ),
            'param_name' => 'custom_background',
            'description' => __( 'Select Background color.', 'js_composer' ),
            'group' => __( 'Typography', KT_THEME_LANG ),
            'dependency' => array(
                'element' => 'accent_background',
                'value_not_equal_to' => array( 'true' )
            ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'js_composer' ),
            'group' => __( 'Typography', KT_THEME_LANG ),
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
            'group' => __( 'Typography', KT_THEME_LANG ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
            'description' => __( '', 'js_composer' ),
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
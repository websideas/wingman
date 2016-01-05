<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Dropcap extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => __( 'D', 'wingman' ),
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
    "name" => esc_html__( "Dropcap", 'wingman'),
    "base" => "dropcap",
    "category" => esc_html__('by Theme', 'wingman' ),
    "description" => esc_html__( "", 'wingman'),
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'First Letter', 'js_composer' ),
            'param_name' => 'title',
            'value' => esc_html__( 'D', 'wingman' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'hidden',
            'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Size', 'js_composer' ),
            'param_name' => 'size',
            'value' => getVcShared( 'sizes' ),
            'std' => 'md',
            'description' => esc_html__( 'Dropcap size.', 'wingman' ),
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
                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            "type" => "kt_number",
            "heading" => esc_html__("Border Radius", 'wingman'),
            "param_name" => "border_radius",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => esc_html__( 'Typography', 'wingman' ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Use Accent Background Color', 'wingman' ),
            'param_name' => 'accent_background',
            'value' => 'true',
            'group' => esc_html__( 'Typography', 'wingman' ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Custom Background', 'js_composer' ),
            'param_name' => 'custom_background',
            'description' => esc_html__( 'Select Background color.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'dependency' => array(
                'element' => 'accent_background',
                'value_not_equal_to' => array( 'true' )
            ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
                    'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
            'description' => esc_html__( '', 'js_composer' ),
        ),
            
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        //Design options
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => esc_html__( 'Design options', 'js_composer' )
        ),

    ),
));
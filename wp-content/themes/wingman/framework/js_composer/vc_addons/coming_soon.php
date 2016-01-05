<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Comingsoon extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'date_coming' => '2016/5/19',
            'style_coming' => 'style1',
            
            'use_theme_fonts' => '',
            'font_container' => '',
            'google_fonts' => '',

            'use_theme_fonts_value' => '',
            'font_container_value' => '',
            'google_fonts_value' => '',

            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);
        
        $rand = rand(); 
        $custom_css = $data_animate = $cl_animate = $animate_item = '';
        
        $style_title = '';
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
            $style_title .= esc_attr( implode( ';', $styles ) );
            $custom_css .= '#kt_comming_'.$rand.' .title{ '.$style_title.' }';
        }

        $style_value = '';
        $atts['font_container'] = $font_container_value;
        $atts['google_fonts'] = $google_fonts_value;
        $atts['use_theme_fonts'] = $use_theme_fonts_value;

        extract($this->getAttributes($atts));
        unset($font_container_data['values']['text_align']);

        extract($this->getStyles($el_class, $css, $google_fonts_data, $font_container_data, $atts));

        $settings = get_option('wpb_js_google_fonts_subsets');
        $subsets = '';
        if (is_array($settings) && !empty($settings)) {
            $subsets = '&subset=' . implode(',', $settings);
        }
        if (!empty($google_fonts_data) && isset($google_fonts_data['values']['font_family'])) {
            wp_enqueue_style('vc_google_fonts_' . vc_build_safe_css_class($google_fonts_data['values']['font_family']), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets);
        }

        if (!empty($styles)) {
            $style_value .= esc_attr(implode(';', $styles));
            $custom_css .= '#kt_comming_'.$rand.' .value-time{ '.$style_value.' }';
        }

        
        if($custom_css){
            $custom_css = '<div class="kt_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }
        
        if($css_animation !=''){
            $data_animate = 'data-timeeffect="200" data-animation="'.$css_animation.'"';
            $cl_animate = 'animation-effect';
            $animate_item = 'animation-effect-item';
        }
        
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wrapper-comingsoon clearfix ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'animate' => $cl_animate,
            'style' => $style_coming
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        $html = '<div class="wrap"><div class="value-time">266</div><div class="title">Days</div></div>
                 <div class="wrap"><div class="value-time">09</div><div class="title">Hours</div></div>
                 <div class="wrap"><div class="value-time">53</div><div class="title">Minutes</div></div>
                 <div class="wrap"><div class="value-time">59</div><div class="title">Seconds</div></div>';
        
        $output = '<div class="'.esc_attr( $elementClass ).'" '.$data_animate.'><div id="kt_comming_'.$rand.'" class="coming-soon '.$animate_item.'" data-date="'.$date_coming.'">'.$html.'</div>'.$custom_css.'</div>';
        
        return $output;
    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "Coming soon", 'wingman'),
    "base" => "comingsoon",
    "category" => esc_html__('by Theme', 'wingman' ),
    "description" => esc_html__( "", 'wingman'),
    "params" => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Date coming', 'js_composer' ),
            'param_name' => 'date_coming',
            'value' => '2016/5/19',
            'admin_label' => true,
            'description' => esc_html__( 'Example: 2016/5/19', 'js_composer' ),
        ),
        array(
            'type' => 'hidden',
            'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Style', 'wingman' ),
            'param_name' => 'style_coming',
            'value' => array(
                esc_html__( 'Style 1', 'js_composer' ) => 'style1',
                esc_html__( 'Style 2', 'js_composer' ) => 'style2',
                esc_html__( 'Style 3', 'js_composer' ) => 'style3',
            ),
            'std' => 'style1',
        ),
        //Typography settings
        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Title typography", 'wingman'),
            "param_name" => "title_typography",
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    //'tag' => 'h2', // default value h2
                    'font_size',
                    'line_height',
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
            "type" => "kt_heading",
            "heading" => esc_html__('Value Typography', 'wingman'),
            "param_name" => "value_typography",
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container_value',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_size',
                    'line_height',
                    'color',

                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'description' => esc_html__( '', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts_value',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts_value',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
                    'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'dependency' => array(
                'element' => 'use_theme_fonts_value',
                'value_not_equal_to' => 'yes',
            ),
            'description' => esc_html__( '', 'js_composer' ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'CSS Animation', 'js_composer' ),
            'param_name' => 'css_animation',
            'admin_label' => true,
            'value' => array(
                esc_html__( 'No', 'js_composer' ) => '',
                esc_html__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
                esc_html__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
                esc_html__( 'Left to right', 'js_composer' ) => 'left-to-right',
                esc_html__( 'Right to left', 'js_composer' ) => 'right-to-left',
                esc_html__( 'Appear from center', 'js_composer' ) => "appear"
            ),
            'description' => esc_html__( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
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
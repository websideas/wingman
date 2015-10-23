<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Piechart extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => '',
            'percent' => '',
            'label_value' => '',
            'units' => '',
            'size' => '',
            'line_width' => '5',
            'color_line' => 'accent',
            'custom_color_line' => '#f18c59',
            'bg_line' => 'grey',
            'custom_bg_line' => '#101010',
            'linecap' => 'round',
            
            'use_theme_fonts' => 'yes',
            'font_container' => '',
            'google_fonts' => '',

            'use_theme_fonts_value' => 'yes',
            'font_container_value' => '',
            'google_fonts_value' => '',
            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);
        
        $style_title = $style_value = $output = '';
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
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }

        $atts['font_container'] = $font_container_value;
        $atts['google_fonts'] = $google_fonts_value;
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
            $style_value .= 'style="' . esc_attr(implode(';', $styles)) . '"';
        }

        if( $color_line == 'custom' ){ $color_line = $custom_color_line; }
        if( $bg_line == 'custom' ){ $bg_line = $custom_bg_line; }


        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-piechart-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );

        $color_line = kt_color2Hex($color_line);
        $bg_line = kt_color2Hex($bg_line);

        $output .= '<div class="chart" data-label="'.esc_attr($label_value).'" data-percent="'.esc_attr($percent).'" data-size="'.esc_attr($size).'" data-linewidth="'.esc_attr($line_width).'" data-fgcolor="'.esc_attr($color_line).'" data-bgcolor="'.esc_attr($bg_line).'" data-linecap="'.esc_attr($linecap).'">';
            $output .= '<span class="percent" '.$style_value.'><span>0</span>'.$units.'</span>';
        $output .= '</div>';
        if($title){
            $output .= '<'.$font_container_data['values']['tag'].' class="piechart-title" '.$style_title.'>'.$title.'</'.$font_container_data['values']['tag'].'>';
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "KT Pie chart", THEME_LANG),
    "base" => "piechart",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "Animated pie chart", THEME_LANG),
    "params" => array(
        array(
            'type' => 'textfield',
            'heading' => __( 'Widget title', 'js_composer' ),
            'param_name' => 'title',
            'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
            'admin_label' => true
        ),
        array(
            'type' => 'hidden',
            'heading' => __( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            'type' => 'kt_number',
            'heading' => __( 'Value', 'js_composer' ),
            'param_name' => 'percent',
            'description' => __( 'Enter value for graph (Note: choose range from 0 to 100).', 'js_composer' ),
            "value" => 75,
            "min" => 1,
            "max" => 100,
            "suffix" => "%",
            'admin_label' => true
        ),

        array(
            'type' => 'textfield',
            'heading' => __( 'Label value', 'js_composer' ),
            'param_name' => 'label_value',
            'description' => __( 'Enter label for pie chart (Note: leaving empty will set value from "Value" field).', 'js_composer' ),
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Units', 'js_composer' ),
            'param_name' => 'units',
            'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'js_composer' )
        ),

        array(
            "type" => "kt_number",
            "heading" => __("Size", THEME_LANG),
            "param_name" => "size",
            "value" => 145,
            "suffix" => "px",
            "description" => "",
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Line Width", THEME_LANG),
            "param_name" => "line_width",
            "value" => 5,
            "min" => 1,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Color Line', 'js_composer' ),
            'param_name' => 'color_line',
            'value' => array_merge( array( __( 'Accent', 'js_composer' ) => 'accent' ), getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => __( 'Select icon color.', 'js_composer' ),
            'std' => 'accent',
            'param_holder_class' => 'vc_colored-dropdown',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom Color line', 'js_composer' ),
            'param_name' => 'custom_color_line',
            'description' => __( 'Select Line Pie Chart color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'color_line',
                'value' => 'custom',
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Background Line', 'js_composer' ),
            'param_name' => 'bg_line',
            'value' => array_merge( getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => __( 'Select icon color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'std' => 'grey'
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom Background line', 'js_composer' ),
            'param_name' => 'custom_bg_line',
            'dependency' => array(
                'element' => 'bg_line',
                'value' => 'custom',
            ),
            'description' => __( 'Select Line Pie Chart background.', 'js_composer' ),
            'value' => ''
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Line Cap', 'js_composer' ),
            'param_name' => 'linecap',
            'value' => array(
                __( 'Round', 'js_composer' ) => 'round',
                __( 'Butt', 'js_composer' ) => 'butt',
                __( 'Square', 'js_composer' ) => 'square',
            ),
            'description' => __( 'Select lineCap.', 'js_composer' ),
            "admin_label" => true,
        ),
        
        //Typography settings
        array(
            "type" => "kt_heading",
            "heading" => __("Title typography", THEME_LANG),
            "param_name" => "title_typography",
            'group' => __( 'Typography', THEME_LANG )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'tag' => 'h5', // default value h3
                    'font_size',
                    'line_height',
                    'color',
                    'tag_description' => __( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => __( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => __( 'Enter line height.', 'js_composer' ),
                    'color_description' => __( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => __( 'Typography', THEME_LANG )
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'js_composer' ),
            'group' => __( 'Typography', THEME_LANG ),
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
            'group' => __( 'Typography', THEME_LANG ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
            'description' => __( '', 'js_composer' ),
        ),



        array(
            "type" => "kt_heading",
            "heading" => __('Value Typography', THEME_LANG),
            "param_name" => "value_typography",
            'group' => __( 'Typography', THEME_LANG )
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
            'description' => __( '', 'js_composer' ),
            'group' => __( 'Typography', THEME_LANG )
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts_value',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'js_composer' ),
            'group' => __( 'Typography', 'js_composer' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts_value',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => __( 'Select font family.', 'js_composer' ),
                    'font_style_description' => __( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => __( 'Typography', THEME_LANG ),
            'dependency' => array(
                'element' => 'use_theme_fonts_value',
                'value_not_equal_to' => 'yes',
            ),
            'description' => __( '', 'js_composer' ),
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
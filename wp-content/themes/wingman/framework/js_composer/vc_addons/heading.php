<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_KT_Heading extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(

            'text' => __( 'This is custom heading element with Google Fonts', 'js_composer' ),
            'align' => 'center',
            'layout' => '1',

            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',
            'letter_spacing' => '0',


            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-heading-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'align' => 'kt-heading-align-'.$align,
            'layout' => 'layout-'.$layout,
        );

        $output = $text = $google_fonts = $font_container = $el_class = $css = $google_fonts_data = $font_container_data = $style = '';
        $styles = array();

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

        if($letter_spacing){
            $styles[] = 'letter-spacing: '.$letter_spacing.'px;';
        }
        if ( ! empty( $styles ) ) {
            $style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }

        $output_title = '<' . $font_container_data['values']['tag'] . ' class="kt-heading-title" ' . $style . ' >'.$text.'</' . $font_container_data['values']['tag'] . '>';
        $output_content = ($content) ? '<div class="kt-heading-content">'.do_shortcode($content).'</div>' : '';

        $output .= $output_title.$output_content;

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}








/* Custom Heading element
----------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'KT Heading', 'wingman' ),
    'base' => 'kt_heading',
    "category" => esc_html__('by Theme', 'wingman' ),
    'params' => array(
        array(
            'type' => 'textarea',
            'heading' => esc_html__( 'Text', 'js_composer' ),
            'param_name' => 'text',
            'admin_label' => true,
            'value' => esc_html__( 'This is custom heading element with Google Fonts', 'js_composer' ),
            'description' => esc_html__( 'Note: If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', 'js_composer' ),
        ),
        array(
            'type' => 'hidden',
            'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'wingman'),
            "param_name" => "content",
            "value" => '',
            "description" => esc_html__("", 'wingman'),
            'holder' => 'div'
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Alignment', 'js_composer' ),
            'param_name' => 'align',
            'value' => array(
                esc_html__( 'Center', 'js_composer' ) => 'center',
                esc_html__( 'Left', 'js_composer' ) => 'left',
                esc_html__( 'Right', 'js_composer' ) => "right"
            ),
            'description' => esc_html__( 'Select separator alignment.', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'layout', 'js_composer' ),
            'param_name' => 'layout',
            'value' => array(
                esc_html__( 'Layout 1', 'js_composer' ) => "1",
                esc_html__( 'Layout 2', 'js_composer' ) => "2",
            ),
            'description' => esc_html__( 'Select your layout.', 'wingman' )
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
            'type' => 'textfield',
            'heading' => esc_html__( 'Extra class name', 'js_composer' ),
            'param_name' => 'el_class',
            'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
        ),
        array(
            "type" => "kt_number",
            "heading" => esc_html__("Letter spacing", 'wingman'),
            "param_name" => "letter_spacing",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => esc_html__( 'Typography', 'wingman' ),
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'tag' => 'h2',
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
            'group' => esc_html__( 'Typography', 'wingman' ),
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
            'value' => 'font_family:Poppins|font_style:400%20regular%3A400%3Anormal',
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
        ),


        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' )
        )
    ),
) );



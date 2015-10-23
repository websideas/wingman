<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_KT_Heading extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(

            'text' => __( 'This is custom heading element with Google Fonts', 'js_composer' ),
            'align' => 'center',
            'layout' => 'between',

            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',
            'letter_spacing' => '0',


            'width' => '38px',
            'height' => 2,
            'border_style' => 'solid',
            'color_border' => '#7e7e7e',

            'type' => 'fontawesome',
            'icon_fontawesome' => '',
            'icon_openiconic' => '',
            'icon_typicons' => '',
            'icon_entypoicons' => '',
            'icon_linecons' => '',
            'icon_entypo' => '',
            'color' => '',
            'custom_color' => '',
            'background_style' => '',
            'background_color' => 'grey',
            'custom_background_color' => '',
            'size' => 'xs',

            'divider_margin_top' => 10,
            'divider_margin_bottom' => 30,



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
            'align' => 'kt-heading-align-'.$align
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
        $output_divider = do_shortcode('[kt_divider align="'.$align.'" border_style="'.$border_style.'" color_border="'.$color_border.'" type="'.$type.'" icon_fontawesome="'.$icon_fontawesome.'" icon_openiconic="'.$icon_openiconic.'" icon_typicons="'.$icon_typicons.'" icon_entypo="'.$icon_entypo.'" icon_linecons="'.$icon_linecons.'" color="'.$color.'" custom_color="'.$custom_color.'" custom_background_color="'.$custom_background_color.'"  background_style="'.$background_style.'" background_color="'.$background_color.'" width="'.$width.'" height="'.$height.'" margin_top="'.$divider_margin_top.'" margin_bottom="'.$divider_margin_bottom.'"]');
        $output_content = ($content) ? '<div class="kt-heading-content">'.$content.'</div>' : '';


        if($layout == '2'){
            $output .= $output_divider.$output_title.$output_content;
        }elseif($layout == '3'){
            $output .= $output_title.$output_content.$output_divider;
        }else{
            $output .= $output_title.$output_divider.$output_content;
        }


        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}








/* Custom Heading element
----------------------------------------------------------- */
vc_map( array(
    'name' => __( 'KT Heading', THEME_LANG ),
    'base' => 'kt_heading',
    "category" => __('by Theme', THEME_LANG ),
    'params' => array(
        array(
            'type' => 'textarea',
            'heading' => __( 'Text', 'js_composer' ),
            'param_name' => 'text',
            'admin_label' => true,
            'value' => __( 'This is custom heading element with Google Fonts', 'js_composer' ),
            'description' => __( 'Note: If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', 'js_composer' ),
        ),
        array(
            'type' => 'hidden',
            'heading' => __( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            "type" => "textarea_html",
            "heading" => __("Content", THEME_LANG),
            "param_name" => "content",
            "value" => '',
            "description" => __("", THEME_LANG),
            'holder' => 'div'
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Alignment', 'js_composer' ),
            'param_name' => 'align',
            'value' => array(
                __( 'Center', 'js_composer' ) => 'center',
                __( 'Left', 'js_composer' ) => 'left',
                __( 'Right', 'js_composer' ) => "right"
            ),
            'description' => __( 'Select separator alignment.', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'layout', 'js_composer' ),
            'param_name' => 'layout',
            'value' => array(
                __( 'Title + Divider + Description', 'js_composer' ) => "1",
                __( 'Divider + Title Divider + Description', 'js_composer' ) => '2',
                __( 'Title + Description + Divider', 'js_composer' ) => '3',
            ),
            'description' => __( 'Select separator alignment.', 'js_composer' )
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
            'type' => 'textfield',
            'heading' => __( 'Extra class name', 'js_composer' ),
            'param_name' => 'el_class',
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Letter spacing", THEME_LANG),
            "param_name" => "letter_spacing",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => __( 'Typography', THEME_LANG ),
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'tag' => 'h4',
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
            'group' => __( 'Typography', THEME_LANG ),
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
        ),

        array(
            "type" => "dropdown",
            "heading" => __("Style", THEME_LANG),
            "param_name" => "border_style",
            "value" => array(
                __("Solid", THEME_LANG)=> "solid",
                __("Solid Two line", THEME_LANG)=> "solid_two",
                __("Dashed", THEME_LANG) => "dashed",
                __("Dashed Two line", THEME_LANG) => "dashed_two"
            ),
            "description" => "",
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Border Color", THEME_LANG),
            "param_name" => "color_border",
            "value" => "#7e7e7e",
            "description" => "",
            'group' => __( 'Divider', THEME_LANG )
        ),



        array(
            "type" => "kt_heading",
            "heading" => __("Design settings", THEME_LANG),
            "param_name" => "divider_design",
            'group' => __( 'Divider', THEME_LANG )
        ),


        array(
            "type" => "textfield",
            "heading" => __("Divider width", THEME_LANG),
            "param_name" => "width",
            "value" => "38px",
            "description" => __("Please enter width of divider", THEME_LANG),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Divider height', 'js_composer' ),
            'param_name' => 'height',
            'value' => getVcShared( 'separator border widths' ),
            'description' => __( 'Select height width (pixels).', 'js_composer' ),
            'group' => __( 'Divider', THEME_LANG ),
            'std' => 2
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Margin top", THEME_LANG),
            "param_name" => "divider_margin_top",
            "value" => "10",
            "suffix" => __("px", THEME_LANG),
            "description" => '',
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Margin bottom", THEME_LANG),
            "param_name" => "divider_margin_bottom",
            "value" => "30",
            "suffix" => __("px", THEME_LANG),
            "description" => '',
            'group' => __( 'Divider', 'js_composer' )
        ),


        array(
            "type" => "kt_heading",
            "heading" => __("Icon settings", THEME_LANG),
            "param_name" => "divider_icon",
            'group' => __( 'Divider', THEME_LANG )
        ),

        array(
            'type' => 'dropdown',
            'heading' => __( 'Icon library', 'js_composer' ),
            'value' => array(
                __( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                __( 'Open Iconic', 'js_composer' ) => 'openiconic',
                __( 'Typicons', 'js_composer' ) => 'typicons',
                __( 'Entypo', 'js_composer' ) => 'entypo',
                __( 'Linecons', 'js_composer' ) => 'linecons',
            ),
            //'admin_label' => true,
            'param_name' => 'type',
            'description' => __( 'Select icon library.', 'js_composer' ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'value' => '',
            'settings' => array(
                'emptyIcon' => true,
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'fontawesome',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'value' => '',
            'settings' => array(
                'emptyIcon' => true,
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'openiconic',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'typicons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'typicons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'entypo',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'entypo',
            ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'linecons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'linecons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Icon color', 'js_composer' ),
            'param_name' => 'color',
            'value' => array_merge( array( __( 'Default', 'js_composer' ) => 'default' ), getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => __( 'Select icon color.', 'js_composer' ),
            'std' => 'blue',
            'param_holder_class' => 'vc_colored-dropdown',
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom Icon Color', 'js_composer' ),
            'param_name' => 'custom_color',
            'description' => __( 'Select custom icon color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'color',
                'value' => 'custom',
            ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Background shape', 'js_composer' ),
            'param_name' => 'background_style',
            'value' => array(
                __( 'None', 'js_composer' ) => '',
                __( 'Circle', 'js_composer' ) => 'rounded',
                __( 'Square', 'js_composer' ) => 'boxed',
                __( 'Rounded', 'js_composer' ) => 'rounded-less',
                __( 'Outline Circle', 'js_composer' ) => 'rounded-outline',
                __( 'Outline Square', 'js_composer' ) => 'boxed-outline',
                __( 'Outline Rounded', 'js_composer' ) => 'rounded-less-outline',
                __( 'Hexagonal', 'js_composer' ) => 'hexagonal',
                __( 'Diamond Square', 'js_composer' ) => 'diamond_square',

            ),
            'description' => __( 'Select background shape and style for icon.', 'js_composer' ),
            'group' => __( 'Divider', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Background Color', 'js_composer' ),
            'param_name' => 'background_color',
            'value' => array_merge( getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'std' => 'grey',
            'description' => __( 'Background Color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'dependency' => array(
                'element' => 'background_style',
                'not_empty' => true,
            ),
            'group' => __( 'Divider', THEME_LANG )
        ),

        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom background color', 'js_composer' ),
            'param_name' => 'custom_background_color',
            'description' => __( 'Select custom icon background color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'background_color',
                'value' => 'custom',
            ),
            'group' => __( 'Divider', THEME_LANG )
        ),






        array(
            'type' => 'css_editor',
            'heading' => __( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => __( 'Design Options', 'js_composer' )
        )
    ),
) );



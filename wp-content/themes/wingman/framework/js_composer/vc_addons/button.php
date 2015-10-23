<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_KT_Button extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {


        $atts = shortcode_atts( array(
            'link' => '',
            'title' => __( 'Title', 'js_composer' ),
            'bt_align' => 'center',
            'size' => 'md',

            'bt_title_color' => '',
            'bt_bg_color' => '',
            'bt_title_color_hover' => '',
            'bt_bg_color_hover' => '',

            'type' => 'fontawesome',
            'icon_fontawesome' => '',
            'icon_openiconic' => '',
            'icon_typicons' => '',
            'icon_entypoicons' => '',
            'icon_linecons' => '',
            'icon_entypo' => '',
            'color' => '',
            'custom_color' => '',
            'icon_position' => '',

            'use_theme_fonts' => '',
            'font_container' => '',
            'google_fonts' => '',
            'letter_spacing' => '0',

            'bt_border_style' => '',
            'bt_color_border' => '',
            'bt_color_border_hover' => '',
            'bt_border_size' => 1,
            'bt_radius' => 3,

            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $uniqid = 'kt-button-'.uniqid();
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-button-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'align' => 'button-align-'.$bt_align
        );
        $custom_css = '';

        if(!$title){
            $elementClass[] = 'button-no-title';
        }

        $output = $style_title = $button_link = $icon_html = $data_attr_html = '';
        if($link){
            $link = ( $link == '||' ) ? '' : $link;
            $link = vc_build_link( $link );
            $a_href = $link['url'];
            $a_title = $link['title'];
            $a_target = $link['target'];
            $button_link = array('href="'.esc_attr( $a_href ).'"', 'title="'.esc_attr( $a_title ).'"', 'target="'.esc_attr( $a_target ).'"' );


            if($bt_title_color_hover){
                $custom_css .= '#'.$uniqid.':hover{color: '.$bt_title_color_hover.'!important}';
            }
            if($bt_bg_color_hover){
                $custom_css .= '#'.$uniqid.':hover{background: '.$bt_bg_color_hover.'!important}';
            }
            if($bt_color_border_hover){
                $custom_css .= '#'.$uniqid.':hover{border-color: '.$bt_color_border_hover.'!important}';
            }


            if($custom_css){
                $custom_css = '<div class="kt_custom_css" data-css="'.$custom_css.'"></div>';
            }



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

            if($bt_title_color){
                $styles[] = 'color: '.$bt_title_color;
            }
            if($bt_bg_color){
                $styles[] = 'background: '.$bt_bg_color;
            }
            if($bt_border_style){
                $styles[] = 'border-width:'.$bt_border_size.'px';
                $styles[] = 'border-color:'.$bt_color_border;
                $styles[] = 'border-style:'.$bt_border_style;
            } else {
                $styles[] = 'border:none;';
            }
            $styles[] = 'border-radius:'.$bt_radius.'px';
            $styles[] = 'letter-spacing:'.$letter_spacing.'px';
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';


            if(
                ($type == 'fontawesome' && $icon_fontawesome) ||
                ($type == 'openiconic' && $icon_openiconic) ||
                ($type == 'typicons' && $icon_typicons) ||
                ($type == 'linecons' && $icon_linecons) ||
                ($type == 'entypo' && $icon_entypo)
            ){
                vc_icon_element_fonts_enqueue( $type );
                $iconClass = isset( ${"icon_" . $type} ) ? esc_attr( ${"icon_" . $type} ) : '';

                if($iconClass){
                    $icon_html = '<i class="button-icon '.$iconClass.'"></i>';
                }
            }
            if($icon_position == 'right' || $icon_position == 'right_animate'){
                $title = '<span>'.$title.$icon_html.'</span>';
            }elseif($icon_position == 'vertical'){
                $title = '<span>'.$title.'</span>'.$icon_html;
            }else{
                $title = '<span>'.$icon_html.$title.'</span>';
            }
            $icon_elementClass = array(
                'base' => 'kt-button',
                'extra' => 'button-icon-'.$icon_position,
                'size' => 'kt-button-'.$size
            );
            $icon_elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $icon_elementClass ) );

            $output .= '<a id="'.$uniqid.'" class="'.esc_attr( $icon_elementClass ).'" '.$data_attr_html.' '.implode(' ', $button_link).' '.$style_title.'>'.$title.'</a>';

            $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
            return '<div class="'.esc_attr( $elementClass ).'">'.$output.$custom_css.'</div>';

        }
    }
}

vc_map( array(
    "name" => __( "KT Button", THEME_LANG),
    "base" => "kt_button",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "Custom button", THEME_LANG),
    "wrapper_class" => "clearfix",
    "params" => array(

        array(
            "type" => "textfield",
            'heading' => __( 'Title', 'js_composer' ),
            'param_name' => 'title',
            'value' => __( 'Title', 'js_composer' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'vc_link',
            'heading' => __( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
            'description' => __( 'Enter button link.', 'js_composer' )
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Button Title Color", THEME_LANG),
            "param_name" => "bt_title_color",
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            "value" => "",
            "description" => "",
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Button Title Color on Hover", THEME_LANG),
            "param_name" => "bt_title_color_hover",
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            "value" => "",
            "description" => "",
        ),


        array(
            "type" => "colorpicker",
            "heading" => __("Background Color", THEME_LANG),
            "param_name" => "bt_bg_color",
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            "value" => "",
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Background Color on Hover", THEME_LANG),
            "param_name" => "bt_bg_color_hover",
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            "value" => "",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Button alignment', 'js_composer' ),
            'param_name' => 'bt_align',
            'value' => array(
                __( 'Center', 'js_composer' ) => 'center',
                __( 'Inline', 'js_composer' ) => "inline",
                __( 'Left', 'js_composer' ) => 'left',
                __( 'Right', 'js_composer' ) => "right"
            ),
            'description' => __( 'Select button alignment.', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Size', 'js_composer' ),
            'param_name' => 'size',
            'value' => getVcShared( 'sizes' ),
            'std' => 'md',
            'description' => __( 'Icon size.', 'js_composer' )
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
            'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
        ),


        array(
            'type' => 'hidden',
            'param_name' => 'css',
        ),



        // Border setting
        array(
            "type" => "dropdown",
            "heading" => __("Button Border Style", THEME_LANG),
            "param_name" => "bt_border_style",
            "value" => array(
                __("None", THEME_LANG)=> "",
                __("Solid", THEME_LANG)=> "solid",
                __("Dashed", THEME_LANG) => "dashed",
                __("Dotted", THEME_LANG) => "dotted",
                __("Double", THEME_LANG) => "double",
            ),
            "description" => "",
            "group" => __("Border", THEME_LANG)
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Border Color", THEME_LANG),
            "param_name" => "bt_color_border",
            "value" => "",
            "description" => "",
            "dependency" => array("element" => "bt_border_style", "not_empty" => true),
            "group" => __("Border", THEME_LANG)
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Border Color on Hover", THEME_LANG),
            "param_name" => "bt_color_border_hover",
            "value" => "",
            "description" => "",
            "dependency" => array("element" => "bt_border_style", "not_empty" => true),
            "group" => __("Border", THEME_LANG)
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Border Width", THEME_LANG),
            "param_name" => "bt_border_size",
            "value" => 1,
            "min" => 1,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            "dependency" => array("element" => "bt_border_style", "not_empty" => true),
            "group" => __("Border", THEME_LANG)
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Border Radius", THEME_LANG),
            "param_name" => "bt_radius",
            "value" => 3,
            "min" => 0,
            "max" => 500,
            "suffix" => "px",
            "description" => "",
            "group" => __("Border", THEME_LANG)
        ),



        //Typography settings
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    //'tag' => 'h2', // default value h2
                    'font_size',
                    'line_height',
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
            'type' => 'checkbox',
            'heading' => __( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'js_composer' ),
            'group' => __( 'Typography', THEME_LANG ),
        ),


        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => 'font_family:Abril%20Fatface%3A400|font_style:400%20regular%3A400%3Anormal',
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



        //Icon settings
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
            'param_name' => 'type',
            'description' => __( 'Select icon library.', 'js_composer' ),
            'group' => __( 'Icon', THEME_LANG )
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
            'group' => __( 'Icon', THEME_LANG )
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
            'group' => __( 'Icon', THEME_LANG )
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
            'group' => __( 'Icon', THEME_LANG )
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
            'group' => __( 'Icon', THEME_LANG )
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
            'group' => __( 'Icon', THEME_LANG )
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Icon Position", THEME_LANG),
            "param_name" => "icon_position",
            "value" => array(
                __("Icon at Left", THEME_LANG)=> "left",
                __("Icon at Right", THEME_LANG)=> "right",
                __("Icon at Left animation", THEME_LANG)=> "left_animate",
                __("Icon at Right animation", THEME_LANG)=> "right_animate",
                __("Vertical animation ", THEME_LANG)=> "vertical",
            ),
            "description" => "",
            "group" => __("Icon", THEME_LANG)
        ),


    ),
));


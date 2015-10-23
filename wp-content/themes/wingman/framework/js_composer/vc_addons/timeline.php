<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );




class WPBakeryShortCode_Timeline extends WPBakeryShortCodesContainer {
    protected function content($atts, $content = null) {
        extract( shortcode_atts( array(
            'timeline_tyle' => 'vertical',
            'horizontal_style' => 'style_1',
            'timeline_column' => 4,
            
            'font_container' => '',
            'letter_spacing' => '0',
            'font_type' => '',
            'google_fonts' => '',
            'color' => '',
            'color_hover' => '',
            'custom_color' => '',
            'background_style' => '',
            'background_color' => '',
            'background_color_hover' => '',
            'size' => 'xl',
            
            'border_width' => '',
            'border_type' => '',
            'border_custom_color' => '',

            'css_animation' => '',
            'el_class' => '',
            'css' => ''
        ), $atts ) );
        extract($atts);
        global $data_icon, $data_type, $style_title, $font_tag, $data_horizontal_style,$data_animate;
        
        $data_horizontal_style = $horizontal_style;
        
        $data_animate = $cl_animate = $custom_css = '';
        $rand = 'kt_timeline_'.rand();
        
        if( $border_width ){
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-vertical .icon-timeline::before,#'.$rand.'.kt-timeline-wrapper ul li.item-vertical .icon-timeline::after{
                border-left-width: '.$border_width.'px;
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal:first-child .icon-timeline::before,#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal:last-child .icon-timeline::before{
                border-width: '.$border_width.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .icon-timeline .vc_icon_element-inner::before,#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .icon-timeline .vc_icon_element-inner::after{
                border-bottom-width: '.$border_width.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .divider-icon{
                border-right-width: '.$border_width.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul.kt-diamond_square li.item-horizontal .icon-timeline::after{
                border-bottom-width: '.$border_width.';
            }';
        }
        if( $border_type ){
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-vertical .icon-timeline::before,#'.$rand.'.kt-timeline-wrapper ul li.item-vertical .icon-timeline::after{
                border-left-style: '.$border_type.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal:first-child .icon-timeline::before,#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal:last-child .icon-timeline::before{
                border-style: '.$border_type.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .icon-timeline .vc_icon_element-inner::before,#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .icon-timeline .vc_icon_element-inner::after{
                border-bottom-style: '.$border_type.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .divider-icon{
                border-right-style: '.$border_type.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul.kt-diamond_square li.item-horizontal .icon-timeline::after{
                border-bottom-style: '.$border_type.';
            }';
        }
        if( $border_custom_color ){
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-vertical .icon-timeline::before,#'.$rand.'.kt-timeline-wrapper ul li.item-vertical .icon-timeline::after{
                border-left-color: '.$border_custom_color.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .icon-timeline .vc_icon_element-inner::before,#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .icon-timeline .vc_icon_element-inner::after
                            ,#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal:first-child .icon-timeline::before,#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal:last-child .icon-timeline::before{
                border-color: '.$border_custom_color.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .divider-icon::after,
                            #'.$rand.'.kt-timeline-wrapper ul li.item-vertical:first-child::after,#'.$rand.'.kt-timeline-wrapper ul li.item-vertical:last-child::after{
                background: '.$border_custom_color.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul li.item-horizontal .divider-icon{
                border-right-color: '.$border_custom_color.';
            }';
            $custom_css .= '#'.$rand.'.kt-timeline-wrapper ul.kt-diamond_square li.item-horizontal .icon-timeline::after{
                border-bottom-color: '.$border_custom_color.';
            }';
        }

        $style_title = '';

        extract( $this->getAttributes( $atts ) );
        unset($font_container_data['values']['text_align']);

        $styles = array();
        if($font_type != 'google'){
            $google_fonts_data = array();
        }
        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        $subsets = '';
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        }
        if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }
        $styles[] = 'letter-spacing:'.$letter_spacing.'px';
        if ( ! empty( $styles ) ) {
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }
        $font_tag = $font_container_data['values']['tag'];

        $data_type = $timeline_tyle;
        $data_icon = 'color_hover="'.$color_hover.'" background_color_hover="'.$background_color_hover.'" color="'.$color.'" custom_color="'.$custom_color.'" background_style="'.$background_style.'" background_color="'.$background_color.'" size="'.$size.'"';

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-timeline-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );

        if(isset( $timeline_column ) || $timeline_column != ''){
            $column = 'column-'.$timeline_column;
        }
        
        if($custom_css){
            $custom_css = '<div class="kt_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }
        if($css_animation !=''){
            $data_animate = 'data-timeeffect="200" data-animation="'.$css_animation.'"';
            $cl_animate = 'animation-effect';
        }
        
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        return '<div id="'.$rand.'" class="'.esc_attr( $elementClass ).'"><ul '.$data_animate.' class="kt-timeline-'.$timeline_tyle.' '.$column.' kt-'.$background_style.' '.$data_horizontal_style.' '.$cl_animate.' clearfix">' . do_shortcode($content) . '</ul>'.$custom_css.'</div>';
    }

    /**
     * Get param value by providing key
     *
     * @param $key
     *
     * @since 4.4
     * @return array|bool
     */
    protected function getParamData( $key ) {
        return WPBMap::getParam( $this->shortcode, $this->getField( $key ) );
    }
    /**
     * Parses shortcode attributes and set defaults based on vc_map function relative to shortcode and fields names
     *
     * @param $atts
     *
     * @since 4.3
     * @return array
     */
    public function getAttributes( $atts ) {
        $text = $google_fonts = $font_container = $el_class = $css = '';
        /**
         * Get default values from VC_MAP.
         **/
        $google_fonts_field = $this->getParamData( 'google_fonts' );
        $font_container_field = $this->getParamData( 'font_container' );
        $el_class_field = $this->getParamData( 'el_class' );
        $css_field = $this->getParamData( 'css' );
        $text_field = $this->getParamData( 'text' );

        extract( shortcode_atts( array(
            'text' => $text_field && isset( $text_field['value'] ) ? $text_field['value'] : '',
            'google_fonts' => $google_fonts_field && isset( $google_fonts_field['value'] ) ? $google_fonts_field['value'] : '',
            'font_container' => $font_container_field && isset( $font_container_field['value'] ) ? $font_container_field['value'] : '',
            'el_class' => $el_class_field && isset( $el_class_field['value'] ) ? $el_class_field['value'] : '',
            'css' => $css_field && isset( $css_field['value'] ) ? $css_field['value'] : ''
        ), $atts ) );
        $el_class = $this->getExtraClass( $el_class );
        $font_container_obj = new Vc_Font_Container();
        $google_fonts_obj = new Vc_Google_Fonts();
        $font_container_field_settings = isset( $font_container_field['settings'], $font_container_field['settings']['fields'] ) ? $font_container_field['settings']['fields'] : array();
        $google_fonts_field_settings = isset( $google_fonts_field['settings'], $google_fonts_field['settings']['fields'] ) ? $google_fonts_field['settings']['fields'] : array();
        $font_container_data = $font_container_obj->_vc_font_container_parse_attributes( $font_container_field_settings, $font_container );
        $google_fonts_data = strlen( $google_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $google_fonts ) : '';

        return array(
            'text' => $text,
            'google_fonts' => $google_fonts,
            'font_container' => $font_container,
            'el_class' => $el_class,
            'css' => $css,
            'font_container_data' => $font_container_data,
            'google_fonts_data' => $google_fonts_data
        );
    }

    /**
     * Used to get field name in vc_map function for google_fonts, font_container and etc..
     *
     * @param $key
     *
     * @since 4.4
     * @return bool
     */
    protected function getField( $key ) {
        return isset( $this->fields[ $key ] ) ? $this->fields[ $key ] : false;
    }

    /**
     * Parses google_fonts_data and font_container_data to get needed css styles to markup
     *
     * @param $el_class
     * @param $css
     * @param $google_fonts_data
     * @param $font_container_data
     * @param $atts
     *
     * @since 4.3
     * @return array
     */
    public function getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) {
        $styles = array();
        if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
            foreach ( $font_container_data['values'] as $key => $value ) {
                if ( $key != 'tag' && strlen( $value ) > 0 ) {
                    if ( preg_match( '/description/', $key ) ) {
                        continue;
                    }
                    if ( $key == 'font_size' || $key == 'line_height' ) {
                        $value = preg_replace( '/\s+/', '', $value );
                    }
                    if ( $key == 'font_size' ) {
                        $pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
                        // allowed metrics: http://www.w3schools.com/cssref/css_units.asp
                        $regexr = preg_match( $pattern, $value, $matches );
                        $value = isset( $matches[1] ) ? (float) $matches[1] : (float) $value;
                        $unit = isset( $matches[2] ) ? $matches[2] : 'px';
                        $value = $value . $unit;
                    }
                    if ( strlen( $value ) > 0 ) {
                        $styles[] = str_replace( '_', '-', $key ) . ': ' . $value;
                    }
                }
            }
        }
        if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values'], $google_fonts_data['values']['font_family'], $google_fonts_data['values']['font_style'] ) ) {
            $google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
            $styles[] = "font-family:" . $google_fonts_family[0];
            $google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );
            $styles[] = "font-weight:" . $google_fonts_styles[1];
            $styles[] = "font-style:" . $google_fonts_styles[2];
        }

        /**
         * Filter 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' to change vc_custom_heading class
         *
         * @param string - filter_name
         * @param string - element_class
         * @param string - shortcode_name
         * @param array - shortcode_attributes
         *
         * @since 4.3
         */
        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_custom_heading' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

        return array(
            'css_class' => $css_class,
            'styles' => $styles
        );
    }


}

class WPBakeryShortCode_Timeline_Item extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract( shortcode_atts( array(
            'title' => '',
            'icon_type' => 'fontawesome',
            'icon_fontawesome' => 'fa fa-heart',
            'icon_openiconic' => '',
            'icon_typicons' => '',
            'icon_entypo' => '',
            'icon_linecons' => '',
            'el_class' => '',
        ), $atts ) );

        global $data_icon, $data_type, $style_title, $font_tag, $data_horizontal_style,$data_animate;
        
        $uniqid = 'kt-timeline-item-'.uniqid();

        $icon_box_icon = do_shortcode('[vc_icon el_class="icon-timeline" hover_div="'.$uniqid.'" addon="1" uniqid="'.$uniqid.'" type="'.$icon_type.'" icon_fontawesome="'.$icon_fontawesome.'" icon_openiconic="'.$icon_openiconic.'" icon_typicons="'.$icon_typicons.'" icon_entypo="'.$icon_entypo.'" icon_linecons="'.$icon_linecons.'" '.$data_icon.']');

        $output = '<li id="'.$uniqid.'" class="kt-timeline-item item-'.$data_type.' '.$el_class.'">';
        $output .= $icon_box_icon;
        if( $data_type == 'horizontal' && $data_horizontal_style == 'style_1' ) $output .= '<div class="divider-icon"></div>';
        $output .= '<div class="timeline-info">';
        if( $title ) $output .= '<'.$font_tag.' class="timeline-title" '.$style_title.'>'.$title.'</'.$font_tag.'>';
        if( $content ) $output .= do_shortcode($content);
        $output .= '</div>';
        $output .= '</li>';
        return $output;
    }
}


//Register "container" content element. It will hold all your inner (child) content elements
vc_map( array(
    "name" => __("Timeline", THEME_LANG),
    "base" => "timeline",
    "category" => __('by Theme', THEME_LANG ),
    "as_parent" => array('only' => 'timeline_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
    "content_element" => true,
    "show_settings_on_create" => true,
    "params" => array(
        array(
            'type' => 'dropdown',
            'heading' => __( 'Timeline Type', 'js_composer' ),
            'param_name' => 'timeline_tyle',
            'value' => array(
                __( 'Vertical', 'js_composer' ) => 'vertical',
                __( 'Horizontal', 'js_composer' ) => 'horizontal',

            ),
            'description' => __( 'Select Timeline Type.', 'js_composer' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Horizontal Style', 'js_composer' ),
            'param_name' => 'horizontal_style',
            'value' => array(
                __( 'Style 1', 'js_composer' ) => 'style_1',
                __( 'Style 2', 'js_composer' ) => 'style_2',

            ),
            'description' => __( 'Horizontal Style.', 'js_composer' ),
            'dependency' => array(
                'element' => 'timeline_tyle',
                'value' => 'horizontal',
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Timeline Column', 'js_composer' ),
            'param_name' => 'timeline_column',
            'value' => array(
                __( '4 Column', 'js_composer' ) => '4',
                __( '3 Column', 'js_composer' ) => '3',
                __( '2 Column', 'js_composer' ) => '2',

            ),
            'description' => __( 'Timeline Column.', 'js_composer' ),
            'dependency' => array(
                'element' => 'timeline_tyle',
                'value' => 'horizontal',
            ),
        ),
        array(
            'type' => 'kt_animate',
            'heading' => __( 'CSS Animation', 'js_composer' ),
            'param_name' => 'css_animation',
            'value' => '',
            'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Icon color', 'js_composer' ),
            'param_name' => 'color',
            'value' => array_merge( getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => __( 'Select icon color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'group' => __( 'Icon', THEME_LANG )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Icon color on Hover', 'js_composer' ),
            'param_name' => 'color_hover',
            'description' => __( 'Select icon color on hover.', 'js_composer' ),
            'group' => __( 'Icon', THEME_LANG )
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
            'group' => __( 'Icon', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Background shape', 'js_composer' ),
            'param_name' => 'background_style',
            'value' => array(
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
            'group' => __( 'Icon', THEME_LANG )
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
            'group' => __( 'Icon', THEME_LANG )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom Icon Background', 'js_composer' ),
            'param_name' => 'custom_background',
            'description' => __( 'Select Background icon color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'background_color',
                'value' => 'custom',
            ),
            'group' => __( 'Icon', THEME_LANG )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Background on Hover', 'js_composer' ),
            'param_name' => 'background_color_hover',
            'description' => __( 'Select Background icon color on hover.', 'js_composer' ),
            'group' => __( 'Icon', THEME_LANG ),
            'dependency' => array(
                'element' => 'background_style',
                'not_empty' => true,
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Size', 'js_composer' ),
            'param_name' => 'size',
            'value' => array_merge( getVcShared( 'sizes' ), array( 'Extra Large' => 'xl' ) ),
            'std' => 'md',
            'description' => __( 'Icon size.', 'js_composer' ),
            'group' => __( 'Icon', THEME_LANG )
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        ),
        
        //Title Style
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'tag' => 'h2', // default value h2
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
            'group' => __( 'Typography', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Font type', 'js_composer' ),
            'param_name' => 'font_type',
            'value' => array(
                __( 'Normal', 'js_composer' ) => '',
                __( 'Google font', 'js_composer' ) => 'google',
            ),
            'group' => __( 'Typography', 'js_composer' ),
            'description' => __( '', 'js_composer' ),
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
            'dependency' => array( 'element' => 'font_type', 'value' => array( 'google' ) ),
            'description' => __( '', 'js_composer' ),
        ),

        //border
        array(
            "type" => "kt_number",
            "heading" => __("Border Width", THEME_LANG),
            "param_name" => "border_width",
            "value" => 1,
            "min" => 1,
            "max" => 5,
            "suffix" => "px",
            "description" => "",
            'group' => __( 'Border', THEME_LANG ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Border Style', 'js_composer' ),
            'param_name' => 'border_type',
            'value' => array(
                __( 'Dashed', 'js_composer' ) => 'dashed',
                __( 'Solid', 'js_composer' ) => 'solid',
                __( 'Dotted', 'js_composer' ) => 'dotted',
                __( 'Double', 'js_composer' ) => 'double',
                __( 'Groove', 'js_composer' ) => 'groove',
                __( 'Ridge', 'js_composer' ) => 'ridge',
                __( 'Inset', 'js_composer' ) => 'inset',
                __( 'Outset', 'js_composer' ) => 'outset'
            ),
            'description' => __( 'Select type border.', 'js_composer' ),
            'group' => __( 'Border', THEME_LANG )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom Border Color', 'js_composer' ),
            'param_name' => 'border_custom_color',
            'description' => __( 'Select custom border color.', 'js_composer' ),
            'value' => '#cdcdcf',
            'group' => __( 'Border', THEME_LANG )
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



vc_map( array(
    "name" => __("Timeline item", "js_composer"),
    "base" => "timeline_item",
    "content_element" => true,
    "as_child" => array('only' => 'timeline'), // Use only|except attributes to limit parent (separate multiple values with comma)
    "params" => array(
    
        array(
            "type" => "textfield",
            "heading" => __( "Title", THEME_LANG ),
            "param_name" => "title",
            "admin_label" => true,
            "value" => 'Title'
        ),
        
        array(
          "type" => "textarea_html",
          "heading" => __("Content", THEME_LANG),
          "param_name" => "content",
          "value" => '',
          "description" => __("", THEME_LANG),
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
        	'param_name' => 'icon_type',
        	'description' => __( 'Select icon library.', 'js_composer' ),
        ),
        array(
    		'type' => 'iconpicker',
    		'heading' => __( 'Icon', 'js_composer' ),
    		'param_name' => 'icon_fontawesome',
            'value' => 'fa fa-heart',
    		'settings' => array(
    			'emptyIcon' => true, // default true, display an "EMPTY" icon?
    			'iconsPerPage' => 200, // default 100, how many icons per/page to display
    		),
    		'dependency' => array(
    			'element' => 'icon_type',
    			'value' => 'fontawesome',
    		),
    		'description' => __( 'Select icon from library.', 'js_composer' ),
    	),
    	array(
    		'type' => 'iconpicker',
    		'heading' => __( 'Icon', 'js_composer' ),
    		'param_name' => 'icon_openiconic',
    		'settings' => array(
    			'emptyIcon' => true, // default true, display an "EMPTY" icon?
    			'type' => 'openiconic',
    			'iconsPerPage' => 200, // default 100, how many icons per/page to display
    		),
    		'dependency' => array(
    			'element' => 'icon_type',
    			'value' => 'openiconic',
    		),
    		'description' => __( 'Select icon from library.', 'js_composer' ),
    	),
    	array(
    		'type' => 'iconpicker',
    		'heading' => __( 'Icon', 'js_composer' ),
    		'param_name' => 'icon_typicons',
    		'settings' => array(
    			'emptyIcon' => true, // default true, display an "EMPTY" icon?
    			'type' => 'typicons',
    			'iconsPerPage' => 200, // default 100, how many icons per/page to display
    		),
    		'dependency' => array(
        		'element' => 'icon_type',
        		'value' => 'typicons',
        	),
    		'description' => __( 'Select icon from library.', 'js_composer' ),
    	),
    	array(
    		'type' => 'iconpicker',
    		'heading' => __( 'Icon', 'js_composer' ),
    		'param_name' => 'icon_entypo',
    		'settings' => array(
    			'emptyIcon' => true, // default true, display an "EMPTY" icon?
    			'type' => 'entypo',
    			'iconsPerPage' => 300, // default 100, how many icons per/page to display
    		),
    		'dependency' => array(
    			'element' => 'icon_type',
    			'value' => 'entypo',
    		),
    	),
    	array(
    		'type' => 'iconpicker',
    		'heading' => __( 'Icon', 'js_composer' ),
    		'param_name' => 'icon_linecons',
    		'settings' => array(
    			'emptyIcon' => true, // default true, display an "EMPTY" icon?
    			'type' => 'linecons',
    			'iconsPerPage' => 200, // default 100, how many icons per/page to display
    		),
    		'dependency' => array(
    			'element' => 'icon_type',
    			'value' => 'linecons',
    		),
    		'description' => __( 'Select icon from library.', 'js_composer' ),
    	),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
) );

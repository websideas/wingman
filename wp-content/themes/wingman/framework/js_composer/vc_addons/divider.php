<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_KT_Divider extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        extract(shortcode_atts(array(
            "align" => 'center',
            'width' => '100%',
            'height' => 1,
            'border_style' => 'solid',
            'color_border' => '#e0e0e0',

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

            'margin_top' => 0,
            'margin_bottom' => 35,
            'el_class' => '',
        ), $atts));

        $height = ($height) ? $height : 1;

        $icon_html = '';

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-divider-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'clearfix' => 'clearfix',
            'style' => 'kt-divider-style-'.$border_style,
            'align' => 'kt-divider-'.$align,
        );


        $styles = array(
            'border-color' => 'border-color:'. $color_border,
        );

        if($border_style == 'solid' || $border_style == 'dashed'){
            $styles['border-top-width'] = 'border-top-width:'.$height.'px';
            $styles['border-top-style'] = 'border-top-style:'.$border_style;
        }else{
            $styles['height'] = 'height: '.$height.'px';
            $styles['border-top-width'] = 'border-top-width:1px';
            $styles['border-bottom-width'] = 'border-bottom-width:1px';
            if($border_style == 'solid_two') {
                $styles['border-top-style'] = 'border-top-style:solid';
                $styles['border-bottom-style'] = 'border-bottom-style:solid';
            }else{
                $styles['border-top-style'] = 'border-top-style:dashed';
                $styles['border-bottom-style'] = 'border-bottom-style:dashed';
            }
        }

        $divider_html = '<div class="kt-divider" style="' . esc_attr( implode( ';', $styles ) ) . '"></div>';

        if(
            ($type == 'fontawesome' && $icon_fontawesome) ||
            ($type == 'openiconic' && $icon_openiconic) ||
            ($type == 'typicons' && $icon_typicons) ||
            ($type == 'linecons' && $icon_linecons) ||
            ($type == 'entypo' && $icon_entypo)
        ){
            $elementClass[] = 'kt-divider-icon';
            if(!$background_style){
                $elementClass[] = 'kt-divider-normal';
            }elseif($background_style == 'diamond_square'){
                $elementClass[] = 'kt-divider-type-diamond';
            }else{
                $elementClass[] = 'kt-divider-box';
            }
            $icon_html = do_shortcode('[vc_icon addon="1" type="'.$type.'" icon_fontawesome="'.$icon_fontawesome.'" icon_openiconic="'.$icon_openiconic.'" icon_typicons="'.$icon_typicons.'" icon_entypo="'.$icon_entypo.'" icon_linecons="'.$icon_linecons.'" color="'.$color.'" custom_color="'.$custom_color.'" background_style="'.$background_style.'" background_color="'.$background_color.'" custom_background_color ="'.$custom_background_color.'" size="xs" align="'.$align.'"]');

            if($align != 'left' && $align != 'right'){
                $divider_html .= $divider_html;
            }
        }


        $output = '<div class="kt-divider-content" style="width:'.$width.';">'.$icon_html.$divider_html.'</div>';


        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'" style="margin-top: '.$margin_top.'px;margin-bottom:'.$margin_bottom.'px; ">'.$output.'</div>';
    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Divider", THEME_LANG),
    "base" => "kt_divider",
    "category" => __('by Theme', THEME_LANG ),
    "params" => array(
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
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Border Color", THEME_LANG),
            "param_name" => "color_border",
            "value" => "#e0e0e0",
            "description" => "",
        ),

        //Design options
        array(
            "type" => "textfield",
            "heading" => __("Divider width", THEME_LANG),
            "param_name" => "width",
            "value" => "100%",
            "description" => __("Please enter width of divider", THEME_LANG),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Divider height', 'js_composer' ),
            'param_name' => 'height',
            'value' => getVcShared( 'separator border widths' ),
            'description' => __( 'Select height width (pixels).', 'js_composer' ),
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Margin top", THEME_LANG),
            "param_name" => "margin_top",
            "value" => "0",
            "suffix" => __("px", THEME_LANG),
            "description" => '',
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Margin bottom", THEME_LANG),
            "param_name" => "margin_bottom",
            "value" => 35,
            "suffix" => __("px", THEME_LANG),
            "description" => '',
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
            'admin_label' => true,
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
            'value' => '', // default value to backend editor admin_label
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
            'value' => '', // default value to backend editor admin_label
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
            'type' => 'dropdown',
            'heading' => __( 'Icon color', 'js_composer' ),
            'param_name' => 'color',
            'value' => array_merge( array( __( 'Default', 'js_composer' ) => 'default' ), getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => __( 'Select icon color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
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
            'param_name' => 'custom_background_color',
            'description' => __( 'Select Background icon color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'background_color',
                'value' => 'custom',
            ),
            'group' => __( 'Icon', THEME_LANG )
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Extra class name', 'js_composer' ),
            'param_name' => 'el_class',
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
        ),





    ),
));
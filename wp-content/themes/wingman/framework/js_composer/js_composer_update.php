<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



add_action( 'vc_after_init', 'kt_add_option_to_vc' );
function kt_add_option_to_vc() {

    $color_arr = array('vc_btn', 'vc_icon', 'vc_tta_accordion', 'vc_tta_tabs', 'vc_tta_tour');
    foreach($color_arr as $item){
        $button_colors = WPBMap::getParam( $item, 'color' );
        $button_colors['value'][__( 'Accent color', KT_THEME_LANG )] = 'accent';
        vc_update_shortcode_param( $item, $button_colors );
    }

    $background_arr = array('vc_icon');
    foreach($background_arr as $item){
        $button_colors = WPBMap::getParam( $item, 'background_color' );
        $button_colors['value'][__( 'Accent color', KT_THEME_LANG )] = 'accent';
        vc_update_shortcode_param( $item, $button_colors );
    }

    $image_styles = WPBMap::getParam( 'vc_single_image', 'style' );
    $image_styles['value'][__( 'Border box', KT_THEME_LANG )] = 'border-box';
    $image_styles['value'][__( 'Border box Left', KT_THEME_LANG )] = 'border-left';
    $image_styles['value'][__( 'Border box Right', KT_THEME_LANG )] = 'border-right';
    $image_styles['value'][__( 'Creative Left', KT_THEME_LANG )] = 'creative-left';
    $image_styles['value'][__( 'Creative Right', KT_THEME_LANG )] = 'creative-right';
    $image_styles['value'][__( 'Creative When hover', KT_THEME_LANG )] = 'creative-hover';
    vc_update_shortcode_param( 'vc_single_image', $image_styles );


    $icon_btn = array('i_type', 'i_icon_fontawesome', 'i_icon_openiconic', 'i_icon_typicons', 'i_icon_entypo', 'i_icon_linecons', 'i_icon_pixelicons', 'css_animation', 'el_class');
    foreach($icon_btn as $item){
        vc_remove_param('vc_btn', $item);
    }

}

function kt_add_visibility_shortcode($class, $base, $atts){

    if(isset($atts['visibility'])){
        $class .= ' '.$atts['visibility'];
    }

    return $class;
}
add_filter('vc_shortcodes_css_class', 'kt_add_visibility_shortcode', 20, 3);


/*
add_filter('vc_google_fonts_get_fonts_filter', 'kt_add_fonts_vc');
function kt_add_fonts_vc($fonts_list){

    $font = (object) array(
        'font_family' => 'Cabin',
        'font_styles' => 'regular,italic,700,700italic',
        'font_types' => '400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic'
    );
    $fonts_list[] = $font;


    return $fonts_list;
}
*/


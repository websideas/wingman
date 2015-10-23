<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



add_action( 'vc_after_init', 'kt_add_option_to_vc' );
function kt_add_option_to_vc() {

    // VC_icon: Add hexagonal and Diamond.

    $background_style = WPBMap::getParam( 'vc_icon', 'background_style' );
    $background_style['value'][__( 'Diamond Square', THEME_LANG )] = 'diamond_square';
    $background_style['value'][__( 'Hexagonal', THEME_LANG )] = 'hexagonal';
    vc_update_shortcode_param( 'vc_icon', $background_style );



    $color_arr = array('vc_btn', 'vc_icon', 'vc_tta_accordion', 'vc_tta_tabs', 'vc_tta_tour');
    foreach($color_arr as $item){
        $button_colors = WPBMap::getParam( $item, 'color' );
        $button_colors['value'][__( 'Accent color', THEME_LANG )] = 'accent';
        vc_update_shortcode_param( $item, $button_colors );
    }

    $background_arr = array('vc_icon');
    foreach($background_arr as $item){
        $button_colors = WPBMap::getParam( $item, 'background_color' );
        $button_colors['value'][__( 'Accent color', THEME_LANG )] = 'accent';
        vc_update_shortcode_param( $item, $button_colors );
    }


}



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


<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Googlemap extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract( shortcode_atts( array(
            'location' => '',
            'image' => '',
            'zoom' => '17',
            'height' => '300',
            'type' => 'roadmap',
            'stype' => '',
            'scrollwheel' => '',
            'el_class' => ''
        ), $atts ) );

        if(!$location){return false;}
        
        // Load Google Maps scripts
    	wp_enqueue_script('google-maps-api');
    	wp_enqueue_script('gmap3');
        
        $img_id = preg_replace('/[^\d]/', '', $image);
        $img_thumb = '';
        if( $img_id ){
            $img_array = wp_get_attachment_image_src($img_id,'full');
            $img_thumb = $img_array[0];
        }

        $elementClass = array(
            'extra' => $this->getExtraClass( $el_class ),
            'size' => 'googlemap',
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div class=" '.$elementClass.'" data-style="'.esc_attr($stype).'" data-iconmap="'.esc_attr($img_thumb).'" data-type="'.esc_attr($type).'" data-scrollwheel="'.esc_attr($scrollwheel).'" data-location="'.esc_attr($location).'" data-zoom="'.esc_attr($zoom).'" style="height:'.$height.'px"></div>';
    }    
}

vc_map( array(
    "name" => __( "Google map", THEME_LANG),
    "base" => "googlemap",
    "category" => __('by Theme'),
    "description" => __( "", THEME_LANG),
    "params" => array(
        array(
          "type" => "textfield",
          "heading" => __("Height", THEME_LANG),
          "param_name" => "height",
          "value" => 300,
          "description" => __("Enter height of map,units :'px',Leave empty to use '300px'.", THEME_LANG)
        ),
        array(
          "type" => "textfield",
          "heading" => __("Location", THEME_LANG),
          "param_name" => "location",
          "admin_label" => true,
          "description" => __("Enter location", THEME_LANG)
        ),
        array(
            "type" => "dropdown",
        	"heading" => __("Map type",THEME_LANG),
        	"param_name" => "type",
            'std' => 'ROADMAP',
        	"value" => array(
                __('Roadmap', THEME_LANG) => 'roadmap',
                __('Satellite', THEME_LANG) => 'satellite',
                __('Hybrid', THEME_LANG) => 'hybrid',
                __('Terrain', THEME_LANG) => 'terrain',
        	), 
            "admin_label" => true,            
        	"description" => __('',THEME_LANG),
        ),

        array(
            "type" => "dropdown",
            "heading" => __("Map stype",THEME_LANG),
            "param_name" => "stype",
            'std' => '',
            "value" => array(
                __('None', THEME_LANG) => '',
                __('Simple & Light', THEME_LANG) => '1',
                __('Light Grey & Blue', THEME_LANG) => '2',
                __('Dark', THEME_LANG) => '3',
                __('Pinkish Gray', THEME_LANG) => '4',
                __('Elevation', THEME_LANG) => '5',
                __('Mostly Grayscale', THEME_LANG) => '6',
                __('Red Hat Antwerp', THEME_LANG) => '7',
            ),
            "admin_label" => true,
            "description" => __('',THEME_LANG),
        ),

        array(
            "type" => "checkbox",
        	"heading" => __("",THEME_LANG),
        	"param_name" => "scrollwheel",
        	'value' => array( __( 'Disable map zoom on mouse wheel scroll' ) => true ),
        	"description" => __('',THEME_LANG),
        ),
        array(
            "type" => "dropdown",
        	"heading" => __("Map zoom",THEME_LANG),
        	"param_name" => "zoom",
            'std' => '17',
        	"value" => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '11' => '11',
                '12' => '12',
                '13' => '13',
                '14' => '14',
                '15' => '15',
                '16' => '16',
                '17 - Default' => '17',
                '18' => '18', 
                '19' => '19'
        	),
        	"description" => __("1 is the smallest zoom level, 19 the greatest",THEME_LANG),
            "admin_label" => true,
        ),
        array(
            "type" => "attach_image",
            "heading" => __( "Image marker", "js_composer" ),
            "param_name" => "image",
            "description" => __( "Select image show", "js_composer" ),
        ),
        array(
            "type" => "textfield",
            "heading" => __( "Extra class", "js_composer" ),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
    ),
));
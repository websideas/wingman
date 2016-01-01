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
            'el_class' => '',
            'css' => '',
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
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'size' => 'wrapper-googlemap',
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        $output = '';
        $output .= '<div class=" '.$elementClass.'">';
            $output .= '<div class="googlemap" data-style="'.esc_attr($stype).'" data-iconmap="'.esc_attr($img_thumb).'" data-type="'.esc_attr($type).'" data-scrollwheel="'.esc_attr($scrollwheel).'" data-location="'.esc_attr($location).'" data-zoom="'.esc_attr($zoom).'" style="height:'.$height.'px"></div>';
            if( $content ){
                $output .= '<div class="content-map">'.do_shortcode($content).'</div>';
            }
        $output .= '</div>';
        
        return $output;
    }    
}

vc_map( array(
    "name" => __( "Google map", 'wingman'),
    "base" => "googlemap",
    "category" => __('by Theme', 'wingman' ),
    "description" => __( "", 'wingman'),
    "params" => array(
        array(
          "type" => "textfield",
          "heading" => __("Height", 'wingman'),
          "param_name" => "height",
          "value" => 300,
          "description" => __("Enter height of map,units :'px',Leave empty to use '300px'.", 'wingman')
        ),
        array(
          "type" => "textfield",
          "heading" => __("Location", 'wingman'),
          "param_name" => "location",
          "admin_label" => true,
          "description" => __("Enter location", 'wingman')
        ),
        array(
            "type" => "dropdown",
        	"heading" => __("Map type",'wingman'),
        	"param_name" => "type",
            'std' => 'ROADMAP',
        	"value" => array(
                __('Roadmap', 'wingman') => 'roadmap',
                __('Satellite', 'wingman') => 'satellite',
                __('Hybrid', 'wingman') => 'hybrid',
                __('Terrain', 'wingman') => 'terrain',
        	), 
            "admin_label" => true,            
        	"description" => __('','wingman'),
        ),

        array(
            "type" => "dropdown",
            "heading" => __("Map stype",'wingman'),
            "param_name" => "stype",
            'std' => '',
            "value" => array(
                __('None', 'wingman') => '',
                __('Simple & Light', 'wingman') => '1',
                __('Light Grey & Blue', 'wingman') => '2',
                __('Dark', 'wingman') => '3',
                __('Pinkish Gray', 'wingman') => '4',
                __('Elevation', 'wingman') => '5',
                __('Mostly Grayscale', 'wingman') => '6',
                __('Red Hat Antwerp', 'wingman') => '7',
                __('Shades of Grey', 'wingman') => '8',
            ),
            "admin_label" => true,
            "description" => __('','wingman'),
        ),

        array(
            "type" => "checkbox",
        	"heading" => __("",'wingman'),
        	"param_name" => "scrollwheel",
        	'value' => array( __( 'Disable map zoom on mouse wheel scroll', 'wingman' ) => true ),
        	"description" => __('','wingman'),
        ),
        array(
            "type" => "dropdown",
        	"heading" => __("Map zoom",'wingman'),
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
        	"description" => __("1 is the smallest zoom level, 19 the greatest",'wingman'),
            "admin_label" => true,
        ),
        array(
            "type" => "attach_image",
            "heading" => __( "Image marker", "js_composer" ),
            "param_name" => "image",
            "description" => __( "Select image show", "js_composer" ),
        ),
        array(
            "type" => "textarea_html",
            "heading" => __("Content", 'wingman'),
            "param_name" => "content",
            "description" => __("", 'wingman'),
        ),
        array(
            "type" => "textfield",
            "heading" => __( "Extra class", "js_composer" ),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        
        array(
			'type' => 'css_editor',
			'heading' => __( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => __( 'Design options', 'js_composer' )
		),
    ),
));
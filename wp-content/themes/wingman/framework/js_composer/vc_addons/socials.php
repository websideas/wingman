<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_Socials extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        extract(shortcode_atts(array(
    	   "social" => '',
    	   "size" => 'standard',
    	   "style" => 'accent',
           'custom_color' => '',
           'align' => '',
           'tooltip' =>'top',
           'el_class' => '',
           'background_style' => 'empty',
           'space_between_item' => 3,

            'css' => '',
    	), $atts));

        $output = $social_icons = '';

        $socials_arr = array(
            'facebook' => array('title' => __('Facebook', 'wingman'), 'icon' => 'fa fa-facebook', 'link' => '%s'),
            'twitter' => array('title' => __('Twitter', 'wingman'), 'icon' => 'fa fa-twitter', 'link' => 'http://www.twitter.com/%s'),
            'dribbble' => array('title' => __('Dribbble', 'wingman'), 'icon' => 'fa fa-dribbble', 'link' => 'http://www.dribbble.com/%s'),
            'vimeo' => array('title' => __('Vimeo', 'wingman'), 'icon' => 'fa fa-vimeo-square', 'link' => 'http://www.vimeo.com/%s'),
            'tumblr' => array('title' => __('Tumblr', 'wingman'), 'icon' => 'fa fa-tumblr', 'link' => 'http://%s.tumblr.com/'),
            'skype' => array('title' => __('Skype', 'wingman'), 'icon' => 'fa fa-skype', 'link' => 'skype:%s'),
            'linkedin' => array('title' => __('LinkedIn', 'wingman'), 'icon' => 'fa fa-linkedin', 'link' => '%s'),
            'googleplus' => array('title' => __('Google Plus', 'wingman'), 'icon' => 'fa fa-google-plus', 'link' => '%s'),
            'youtube' => array('title' => __('Youtube', 'wingman'), 'icon' => 'fa fa-youtube', 'link' => 'http://www.youtube.com/user/%s'),
            'pinterest' => array('title' => __('Pinterest', 'wingman'), 'icon' => 'fa fa-pinterest', 'link' => 'http://www.pinterest.com/%s'),
            'instagram' => array('title' => __('Instagram', 'wingman'), 'icon' => 'fa fa-instagram', 'link' => 'http://instagram.com/%s'),
        );

        foreach($socials_arr as $k => &$v){
            $val = kt_option($k);
            $v['val'] = ($val) ? $val : '';
        }

        $tooltiphtml = '';

        if($tooltip) {
            $tooltiphtml .= ' data-toggle="tooltip" data-placement="'.esc_attr($tooltip).'" ';
        }

        $margin = ($space_between_item > 0) ? 'style="margin:0 '.$space_between_item.'px;"' : '';
        
        if($social){
            $social_type = explode(',', $social);
            foreach ($social_type as $id) {
                $val = $socials_arr[$id];
                $social_text = '<i class="'.esc_attr($val['icon']).'"></i>';
                $social_icons .= '<li '.$margin.'><a class="'.esc_attr($id).'" title="'.esc_attr($val['title']).'" '.$tooltiphtml.' href="'.esc_url(str_replace('%s', $val['val'], $val['link'])).'" target="_blank">'.$social_text.'</a></li>'."\n";
            }
        }else{
            foreach($socials_arr as $key => $val){
                $social_text = '<i class="'.esc_attr($val['icon']).'"></i>';
                $social_icons .= '<li '.$margin.'><a class="'.esc_attr($key).'"  '.$tooltiphtml.' title="'.esc_attr($val['title']).'" href="'.esc_url(str_replace('%s', $val['val'], $val['link'])).'" target="_blank">'.$social_text.'</a></li>'."\n";
            }
        }

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'socials-icon-wrapper', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'style' => 'social-style-'.$style,
            'size' => 'social-icons-'.$size,
            'shape' => 'social-background-'.$background_style,
            'clearfix' => 'clearfix'
        );
        if($background_style == 'empty'){
            $elementClass[] = 'social-background-empty';
        }elseif ( strpos( $background_style, 'outline' ) !== false ) {
            $elementClass[] = 'social-background-outline';
        }else{
            $elementClass[] = 'social-background-fill';
        }
        
        $custom_css = '';
        $rand = 'kt_social_'.rand();
        if( $style == 'custom' ){
            $custom_css .= '#'.$rand.'.social-style-custom.social-background-fill a{
                color:#fff!important;
                background:'.$custom_color.'!important;
            }';
            $custom_css .= '#'.$rand.'.social-style-custom.social-background-outline a{
                color:'.$custom_color.'!important;
                border-color:'.$custom_color.'!important;
                background: none !important;
            }';
            $custom_css .= '#'.$rand.'.social-style-custom.social-background-empty a{
                color:'.$custom_color.'!important;
                background:none!important;
                border:!important;
            }';
        }
        if($custom_css){
            $custom_css = '<div class="kt_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }

        if($align){
            $elementClass['align'] = 'social-icons-'.$align;
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

    	$output .= '<div id="'.$rand.'" class="'.esc_attr( $elementClass ).'"><ul class="social-nav clearfix">';
    	$output .= $social_icons;
    	$output .= '</ul>'.$custom_css.'</div>';
     
    	return $output;
    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Social", 'wingman'),
    "base" => "socials",
    "category" => __('by Theme', 'wingman' ),
    "description" => __( "Social", 'wingman'),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "kt_socials",
            "class" => "",
            "heading" => __("Choose social", 'wingman'),
            "param_name" => "social",
            "value" => '',
            "description" => __("Empty for select all, Drop and sortable social", 'wingman'),
            "admin_label" => true,
        ),
        array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style",'wingman'),
			"param_name" => "style",
			"value" => array(
                __('Accent', 'wingman') => 'accent',
                __('Dark', 'wingman') => 'dark',
                __('Light', 'wingman') => 'light',
                __('Color', 'wingman') => 'color',
                __('Custom Color', 'wingman') => 'custom',
			),
			"description" => __("",'wingman'),
            "admin_label" => true,
		),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Custom Color', 'js_composer' ),
            'param_name' => 'custom_color',
            'description' => __( 'Select color socials.', 'js_composer' ),
            'dependency' => array(
                'element' => 'style',
                'value' => 'custom',
            ),
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
                __( 'Diamond Square', 'js_composer' ) => 'diamond-square',
                __( 'Outline Circle', 'js_composer' ) => 'rounded-outline',
                __( 'Outline Square', 'js_composer' ) => 'boxed-outline',
                __( 'Outline Rounded', 'js_composer' ) => 'rounded-less-outline',
                __( 'Outline Diamond Square', 'js_composer' ) => 'diamond-square-outline',
            ),
            'description' => __( 'Select background shape and style for social.', 'wingman' ),
            "admin_label" => true,
        ),

        array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Size",'wingman'),
			"param_name" => "size",
			"value" => array(
                __('Standard', 'wingman') => 'standard',
                __('Small', 'wingman') => 'small',
			),
			"description" => __("",'wingman'),
            "admin_label" => true,
		),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __("Tooltip",'wingman'),
            "param_name" => "tooltip",
            "value" => array(
                __('None', 'wingman') => '',
                __('Top', 'wingman') => 'top',
                __('Right', 'wingman') => 'right',
                __('Bottom', 'wingman') => 'bottom',
                __('Left', 'wingman') => 'left',
            ),
            'std' => 'top',
            "description" => __("Select the tooltip position",'wingman'),
            "admin_label" => true,
        ),

        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __("Align",'wingman'),
            "param_name" => "align",
            "value" => array(
                __('None', 'wingman') => '',
                __('Center', 'wingman') => 'center',
                __('Left', 'wingman') => 'left',
                __('Right', 'wingman') => 'right'
            ),
            "description" => __("",'wingman')
        ),
        
        array(
            "type" => "kt_number",
            "heading" => __("Space Between item", 'wingman'),
            "param_name" => "space_between_item",
            "value" => 3,
            "min" => 0,
            "max" => 50,
            "suffix" => "px",
            "description" => "",
        ),
        
        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer" ),
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
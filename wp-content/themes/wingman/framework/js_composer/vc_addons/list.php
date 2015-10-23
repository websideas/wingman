<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_List extends WPBakeryShortCodesContainer {
	protected function content($atts, $content = null) {

		extract( shortcode_atts( array(
			'css_animation' => '',
			'icon_type' => 'entypo',
			'icon_fontawesome' => '',
			'icon_openiconic' => '',
			'icon_typicons' => '',
			'icon_entypo' => 'entypo-icon entypo-icon-minus',
			'icon_linecons' => '',
			'icon_color' => '',
			'el_class' => '',
			'css' => ''
		), $atts ) );

		global $icon_show, $icon_color_show, $data_animate;

		$icon_show = $data_animate = $cl_animate = $icon_color_show = '';
		if($icon_type){
			$icon_value = isset( ${"icon_" . $icon_type} ) ? esc_attr( ${"icon_" . $icon_type} ) : '';
			if($icon_value){
				vc_icon_element_fonts_enqueue( $icon_type );
				$icon_color_show = ($icon_color) ? ' style="color: '.esc_attr($icon_color).';"' : '';
				$icon_show = '<span class="vc_icon_element-icon '.esc_attr($icon_value).'" '.$icon_color_show.'></span> ';
			}
		}

		$elementClass = array(
			'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt-list-fancy ', $this->settings['base'], $atts ),
			'extra' => $this->getExtraClass( $el_class ),
			'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
		);

		if($css_animation !=''){
			$data_animate = 'data-timeeffect="20" data-animation="'.$css_animation.'"';
			$elementClass['animation'] = ' animation-effect';
		}
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

		return '<ul '.$data_animate.' class="'.esc_attr( $elementClass ).'">'. do_shortcode($content) . '</ul>';

	}
}

class WPBakeryShortCode_List_Item extends WPBakeryShortCode {
	protected function content($atts, $content = null) {
		extract( shortcode_atts( array(
			'custom_icon' => 'false',
			'icon_type' => 'fontawesome',
			'icon_fontawesome' => '',
			'icon_openiconic' => '',
			'icon_typicons' => '',
			'icon_entypo' => '',
			'icon_linecons' => '',
			'icon_color' => '',
            'content_here' => __("Put your content here", THEME_LANG),
			'el_class' => '',
		), $atts ) );
		$icon_li = '';

		global $icon_show, $icon_color_show;

		if($icon_type && $custom_icon == 'true'){
			$icon = 'icon_'.$icon_type;
			$icon_value = $$icon;
			if($icon_value){
				vc_icon_element_fonts_enqueue( $icon_type );
				$icon_color = $icon_color ? ' style="color: '.esc_attr($icon_color).';"' : $icon_color_show;
				$icon_li = '<span class="vc_icon_element-icon '.esc_attr($icon_value).'" '.$icon_color.'></span> ';
			}
		}

		if(!$icon_li) $icon_li = $icon_show;

		return '<li class="kt-list-item '.$el_class.'">' . $icon_li . $content . '</li>';

	}
}



//Register "container" content element. It will hold all your inner (child) content elements
vc_map( array(
    "name" => __("List", THEME_LANG),
    "base" => "list",
    "category" => __('by Theme', THEME_LANG ),
    "as_parent" => array('only' => 'list_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
    "content_element" => true,
    "show_settings_on_create" => false,
    "params" => array(
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
			'std' => 'entypo',
        	'description' => __( 'Select icon library.', 'js_composer' ),
        ),
        array(
    		'type' => 'iconpicker',
    		'heading' => __( 'Icon', 'js_composer' ),
    		'param_name' => 'icon_fontawesome',
            'value' => 'fa fa-minus',
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
			'std' => 'entypo-icon entypo-icon-minus'
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
            'type' => 'colorpicker',
            'heading' => __( 'Icon color', 'js_composer' ),
            'param_name' => 'icon_color',
            'value' => '',
            'description' => __( 'Select backgound color for your testimonial', 'js_composer' ),
        ),
        array(
            'type' => 'kt_animate',
            'heading' => __( 'Css Animation', 'js_composer' ),
            'param_name' => 'css_animation',
            'value' => '',
            'description' => __( 'Animation.', 'js_composer' ),
        ),




        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        ),
        array(
            'type' => 'css_editor',
            'heading' => __( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => __( 'Design options', 'js_composer' )
        ),
    ),
    "js_view" => 'VcColumnView',
	'default_content' => '[list_item]'.__("Put your content here", THEME_LANG).'[/list_item][list_item]'.__("Put your content here", THEME_LANG).'[/list_item][list_item]'.__("Put your content here", THEME_LANG).'[/list_item]',
) );



vc_map( array(
    "name" => __("List item", "js_composer"),
    "base" => "list_item",
    "content_element" => true,
    "as_child" => array('only' => 'list'), // Use only|except attributes to limit parent (separate multiple values with comma)
    "params" => array(
        array(
          "type" => "textarea",
          "heading" => __("Content", THEME_LANG),
          "param_name" => "content",
          "value" => __("Put your content here", THEME_LANG),
          "description" => __("", THEME_LANG),
          "holder" => "div",
        ),

        array(
            'type' => 'kt_switch',
            'heading' => __( 'Custom icon', THEME_LANG ),
            'param_name' => 'custom_icon',
            'value' => 'false',
            "description" => __("Close button in alert", THEME_LANG),
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
            "dependency" => array("element" => "custom_icon","value" => array('true')),
        ),
        array(
    		'type' => 'iconpicker',
    		'heading' => __( 'Icon', 'js_composer' ),
    		'param_name' => 'icon_fontawesome',
            'value' => '',
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
            'type' => 'colorpicker',
            'heading' => __( 'Icon color', 'js_composer' ),
            'param_name' => 'icon_color',
            'value' => '',
            'description' => __( 'Select backgound color for your testimonial', 'js_composer' ),
            "dependency" => array("element" => "custom_icon","value" => array('true')),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
) );

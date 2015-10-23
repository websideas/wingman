<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_KT_Callout extends WPBakeryShortCode {
    protected $template_vars = array();

    protected function content($atts, $content = null) {
        $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
        $this->buildTemplate( $atts, $content );

        $output = '';

        $callout_content = $this->getTemplateVariable( 'heading1' );
        if($content){
            $callout_content .= '<div class="kt-callout-content">'.$this->getTemplateVariable( 'content' ).'</div>';
        }


        $callout_button = $this->getTemplateVariable( 'actions' );
        if($callout_button && $atts['layout'] != 3){
            $callout_button = '<div class="callout-action display-cell">'.$callout_button.'</div>';
        }


        if($atts['layout'] == 2){
            $output .= sprintf(
                '<div class="callout-content display-table">%s<div class="callout-text display-cell">%s</div></div>',
                $callout_button,
                $callout_content
            );
        }elseif($atts['layout'] == 3){
            $output .= sprintf(
                '<div class="kt-callout-inner">%s</div>%s',
                $callout_content,
                $callout_button
            );
        }else{
            $output .= sprintf(
                '<div class="callout-content display-table"><div class="callout-text display-cell">%s</div>%s</div>',
                $callout_content,
                $callout_button
            );
        }


        return '<div class="'.esc_attr( implode( ' ', $this->getTemplateVariable( 'css-class' ) ) ).'">'.$output.'</div>';

    }

    public function buildTemplate( $atts, $content ) {

        $output = array();

        $main_wrapper_classes = array( 'kt-callout-wrapper', 'kt-callout-layout-'.$atts['layout'] );

        if ( ! empty( $atts['css_animation'] ) ) {
            $main_wrapper_classes[] = $this->getCSSAnimation( $atts['css_animation'] );
        }

        if ( ! empty( $atts['css'] ) ) {
            $main_wrapper_classes[] = vc_shortcode_custom_css_class( $atts['css'] );
        }

        if ( ! empty( $atts['el_class'] ) ) {
            $main_wrapper_classes[] = $atts['el_class'];
        }

        if ( ! empty( $atts['skin'] ) ) {
            $main_wrapper_classes[] = 'kt-callout-'.$atts['skin'];
        }


        if ( ! empty( $atts['add_button'] ) ) {
            $output[ 'actions' ] = $this->getButton( $atts );
        }


        $output['content'] = wpb_js_remove_wpautop( $content, true );
        $output['heading1'] = $this->getHeading( 'h3', $atts );
        $output['css-class'] = $main_wrapper_classes;
        $this->template_vars = $output;


    }
    public function getHeading( $tag, $atts ) {

        if ( isset( $atts[ $tag ] ) && trim( $atts[ $tag ] ) !== '' ) {

            $custom_heading = visual_composer()->getShortCode( 'vc_custom_heading' );
            $data = vc_map_integrate_parse_atts( $this->shortcode, 'vc_custom_heading', $atts, $tag . '_' );
            $data['el_class'] = 'kt-callout-title';
            $data['font_container'] = implode( '|', array_filter( array(
                'tag:' . $tag,
                $data['font_container']
            ) ) );
            $data['text'] = $atts[ $tag ]; // provide text to shortcode

            return $custom_heading->render( array_filter( $data ) );

        }

        return '';
    }

    public function getButton( $atts ) {
        $data = vc_map_integrate_parse_atts( $this->shortcode, 'vc_btn', $atts, 'btn_' );
        if ( $data ) {
            $btn = visual_composer()->getShortCode( 'vc_btn' );
            if ( is_object( $btn ) ) {
                return $btn->render( array_filter( $data ) );
            }
        }

        return "";
    }

    public function getTemplateVariable( $string ) {
        if ( is_array( $this->template_vars ) && isset( $this->template_vars[ $string ] ) ) {

            return $this->template_vars[ $string ];
        }

        return "";
    }



}



$h3_custom_heading = vc_map_integrate_shortcode( 'vc_custom_heading', 'h3_', __( 'Heading', 'js_composer' ),
    array( 'exclude' => array( 'source', 'link', 'text', 'css',  'el_class' ) )
);

// This is needed to remove custom heading _tag and _align options.
if ( is_array( $h3_custom_heading ) && ! empty( $h3_custom_heading ) ) {
    foreach ( $h3_custom_heading as $key => $param ) {

        if ( is_array( $param ) && isset( $param['type'] ) && $param['type'] === 'font_container' ) {
            $h3_custom_heading[ $key ]['value'] = '';
            if ( isset( $param['settings'] ) && is_array( $param['settings'] ) && isset( $param['settings']['fields'] ) ) {
                $sub_key = array_search( 'tag', $param['settings']['fields'] );
                if ( false !== $sub_key ) {
                    unset( $h3_custom_heading[ $key ]['settings']['fields'][ $sub_key ] );
                } else if ( isset( $param['settings']['fields']['tag'] ) ) {
                    unset( $h3_custom_heading[ $key ]['settings']['fields']['tag'] );
                }
                $sub_key = array_search( 'text_align', $param['settings']['fields'] );
                if ( false !== $sub_key ) {
                    unset( $h3_custom_heading[ $key ]['settings']['fields'][ $sub_key ] );
                } else if ( isset( $param['settings']['fields']['text_align'] ) ) {
                    unset( $h3_custom_heading[ $key ]['settings']['fields']['text_align'] );
                }
            }
        }elseif ( is_array( $param ) && isset( $param['type'] ) && $param['param_name'] === 'h3_use_theme_fonts' ) {
            $h3_custom_heading[ $key ]['std'] = 'yes';
        }

    }
}


$params = array_merge(
    array(
        array(
            'type' => 'textfield',
            'heading' => __( 'Heading', 'js_composer' ),
            'admin_label' => true,
            'param_name' => 'h3',
            'value' => __( 'Hey! I am first heading line feel free to change me', 'js_composer' ),
            'description' => __( 'Enter text for heading line.', 'js_composer' ),
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
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Layout', 'js_composer' ),
            'param_name' => 'layout',
            'value' => array(
                __( 'Side - Text + Button', 'js_composer' ) => '1',
                __( 'Side - Button + Text', 'js_composer' ) => '2',
                __( 'Center - Text + Button', 'js_composer' ) => '3',
            ),
            'admin_label' => true,
            'description' => '',
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Add button', 'js_composer' ) . '?',
            'description' => __( 'Add button for call to action.', 'js_composer' ),
            'param_name' => 'add_button',
            'value' => array(
                __( 'No', 'js_composer' ) => '',
                __( 'Yes', 'js_composer' ) => 'Yes',
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Skin', THEME_LANG ),
            'param_name' => 'skin',
            'value' => array(
                __( 'Default', THEME_LANG ) => '',
                __( 'Light', THEME_LANG ) => 'light',
            ),
            'description' => __( 'Select your layout.', THEME_LANG ),
            "admin_label" => true,
        ),

        array(
            "type" => "kt_heading",
            "heading" => __("Others setting", THEME_LANG),
            "param_name" => "callout_other_setting",
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
    ),

    //Typography settings
    $h3_custom_heading,

    vc_map_integrate_shortcode( 'vc_btn', 'btn_', __( 'Button', 'js_composer' ),
        array(
            'exclude' => array( 'css', 'align', 'button_block', 'css_animation', 'el_class' )
        ),
        array(
            'element' => 'add_button',
            'not_empty' => true,
        )
    ),
    array(
        array(
            'type' => 'css_editor',
            'heading' => __( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => __( 'Design Options', 'js_composer' )
        )
    )
);



vc_map( array(
    "name" => __( "KT Call to Action", THEME_LANG),
    "base" => "kt_callout",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "Catch visitors attention with CTA block", THEME_LANG),
    "wrapper_class" => "clearfix",
    "params" => $params,
));


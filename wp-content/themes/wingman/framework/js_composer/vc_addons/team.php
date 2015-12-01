<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_Team extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        extract(shortcode_atts(array(
            'name' => '',
            'image' => '',
            'image_size' => 'full',
            'agency' => '',

            'facebook_link' => '',
            'twitter_link' => '',
            'dribbble_link' => '',
            'linkedin_link' => '',

            'el_class' => '',
        ), $atts));

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'team ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        $img_id = preg_replace( '/[^\d]/', '', $image );
        $img = wpb_getImageBySize( array(
            'attach_id' => $img_id,
            'thumb_size' => $image_size,
            'class' => 'vc_single_image-img img-responsive'
        ) );
        if ( $img == null ) {
            $img['thumbnail'] = '<img class="vc_img-placeholder vc_single_image-img" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
        }

        $output = '';

        $socials = array(
            '<i class="fa fa-facebook"></i>' => $facebook_link, 
            '<i class="fa fa-twitter"></i>' => $twitter_link,
            '<i class="fa fa-dribbble"></i>' => $dribbble_link,
            '<i class="fa fa-linkedin"></i>' => $linkedin_link,
        );

        if( $name ){
            $output .= '<div class="'.$elementClass.'">';
                $output .= $img['thumbnail'];
                $output .= '<div class="team-attr">';
                    $output .= '<h4 class="name">'.$name.'</h4>';
                    $output .= '<div class="agency">'.$agency.'</div>';

                    if( $facebook_link || $twitter_link || $dribbble_link || $linkedin_link ){
                        $output .= '<ul class="clearfix">';
                            foreach ($socials as $key => $value) {
                                if( $value ){
                                    $output .= '<li><a href="'.$value.'">'.$key.'</a></li>';
                                }
                            }
                        $output .= '</ul>';
                    }

                $output .= '</div>';
            $output .= '</div>';
        }
        
        return $output;
    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Team", THEME_LANG),
    "base" => "team",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "", THEME_LANG),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => __( 'Name', 'js_composer' ),
            'param_name' => 'name',
            "admin_label" => true,
        ),

        array(
            'type' => 'attach_image',
            'heading' => __( 'Image', THEME_LANG ),
            'param_name' => 'image',
            'description' => __( 'Select image from media library.', 'js_composer' ),
        ),

        array(
            "type" => "kt_image_sizes",
            "heading" => __( "Select image sizes", THEME_LANG ),
            "param_name" => "image_size",
            "std" => "full"
        ),

        array(
            "type" => "textfield",
            'heading' => __( 'Agency', 'js_composer' ),
            'param_name' => 'agency',
            "admin_label" => true,
        ),

        array(
            "type" => "textfield",
            'heading' => __( 'Facebook link', 'js_composer' ),
            'param_name' => 'facebook_link',
        ),
        array(
            "type" => "textfield",
            'heading' => __( 'Twitter link', 'js_composer' ),
            'param_name' => 'twitter_link',
        ),
        array(
            "type" => "textfield",
            'heading' => __( 'Dribbble link', 'js_composer' ),
            'param_name' => 'dribbble_link',
        ),
        array(
            "type" => "textfield",
            'heading' => __( 'LinkedIn link', 'js_composer' ),
            'param_name' => 'linkedin_link',
        ),

        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
    ),
));
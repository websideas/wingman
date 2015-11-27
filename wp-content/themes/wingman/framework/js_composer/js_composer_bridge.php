<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

//  0 - unsorted and appended to bottom Default  
//  1 - Appended to top)


vc_add_params("vc_icon", array(
    array('type' => 'hidden',  'param_name' => 'icon_class'),
    array('type' => 'hidden',  'param_name' => 'color_hover'),
    array('type' => 'hidden',  'param_name' => 'hover_div'),
    array('type' => 'hidden',  'param_name' => 'background_color_hover'),
    array('type' => 'hidden',  'param_name' => 'iconbox_image'),
    array('type' => 'hidden',  'param_name' => 'icon_type')
));

vc_add_params("vc_custom_heading", array(
    array(
        "type" => "kt_number",
        "heading" => __("Letter spacing", THEME_LANG),
        "param_name" => "letter_spacing",
        "min" => 0,
        "suffix" => "px",
        'group' => __( 'Extra', 'js_composer' )
    )
));


$tabs_arr = array(
    'use_theme_fonts' => array(
        'type' => 'checkbox',
        'heading' => __( 'Use theme default font family?', 'js_composer' ),
        'param_name' => 'use_theme_fonts',
        'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
        'description' => __( 'Use font family from the theme.', 'js_composer' ),
        'group' => __( 'Typography', THEME_LANG ),
        'std' => 'yes'
    ),
    'google_fonts' => array(
        'type' => 'google_fonts',
        'param_name' => 'google_fonts',
        'value' => 'font_family:Montserrat|font_style:400%20regular%3A400%3Anormal',
        'settings' => array(
            'fields' => array(
                'font_family_description' => __( 'Select font family.', 'js_composer' ),
                'font_style_description' => __( 'Select font styling.', 'js_composer' )
            )
        ),
        'group' => __( 'Typography', THEME_LANG ),
        'dependency' => array(
            'element' => 'use_theme_fonts',
            'value_not_equal_to' => 'yes',
        ),
        'description' => __( '', 'js_composer' ),
    )
);


vc_add_params("vc_tta_accordion", $tabs_arr);
vc_add_params("vc_tta_pageable", $tabs_arr);

$tabs_arr['use_theme_fonts']['std'] = false;
vc_add_params("vc_tta_tour", $tabs_arr);
vc_add_params("vc_tta_tabs", $tabs_arr);




$composer_addons = array(
    'dropcap.php',
    'blockquote.php',
    'divider.php',
    'list.php',
    'heading.php',
    'lightbox.php',

    'blog_posts.php',
    'blog_posts_carousel.php',


    'alert.php',
    'icon_box.php',
    'counter.php',
    'callout.php',
    'contact_info.php',
    'clients_carousel.php',
    'client_gird.php',
    'testimonial_carousel.php',
    'image_banner.php',
    'product_category_banner.php',
    //'button.php',
    'skill.php',
    'socials.php',
    //'timeline.php',
    'piechart.php',
    'coming_soon.php',
    'googlemap.php',
    
    'category_products_tab.php',
    'products_carousel.php',
);

foreach ( $composer_addons as $addon ) {
	require_once( FW_DIR . 'js_composer/vc_addons/' . $addon );
}

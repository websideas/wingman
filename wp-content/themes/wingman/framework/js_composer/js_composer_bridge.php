<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

//  0 - unsorted and appended to bottom Default  
//  1 - Appended to top)

vc_add_params("vc_row", array(
    array(
        'group' => __( 'Extra', 'js_composer' ),
        'type' => 'colorpicker',
        'heading' => __( 'Color Overlay', 'js_composer' ),
        'param_name' => 'color_overlay',
        'description' => __( 'Select your color overlay for image and video ( rgba ).', THEME_LANG ),
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Set Columns to Equal Height', THEME_LANG ),
        'param_name' => 'equal_height',
        'description' => __( 'Check here if you want column equal height.', THEME_LANG ),
        'value' => array(
            __("None", THEME_LANG) => "",
            __("Column", THEME_LANG) => "column",
            __("Element", THEME_LANG) => "element",
        ),
        'group' => __( 'Extra', 'js_composer' ),
    ),
    array(
        'type' => 'colorpicker',
        'heading' => __( 'Font Color', THEME_LANG ),
        'param_name' => 'font_color',
        'description' => __( 'Select Font Color', THEME_LANG ),
        'group' => __( 'Extra', 'js_composer' ),
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Top of the section', 'js_composer' ),
        'param_name' => 'top_section',
        'value' => array(
            __( 'None', 'js_composer' ) => '',
            __( 'Divider', THEME_LANG ) => 'divider',
        ),
        'group' => __( 'Extra', 'js_composer' ),
        'description' => __( 'Only working with background color and not paralax.', THEME_LANG ),
    ),
    array(
        'type' => 'colorpicker',
        'heading' => __( 'Divider Color', THEME_LANG ),
        'param_name' => 'top_divider_color',
        'description' => __( 'Select divider Color', THEME_LANG ),
        'group' => __( 'Extra', 'js_composer' ),
        'dependency' => array(
            'element' => 'top_section',
            'value' => array('divider'),
        ),
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Bottom of the section', 'js_composer' ),
        'param_name' => 'bottom_section',
        'value' => array(
            __( 'None', 'js_composer' ) => '',
            __( 'Divider', THEME_LANG ) => 'divider',
        ),
        'group' => __( 'Extra', 'js_composer' ),
        'description' => __( 'Only working with background color and not paralax.', THEME_LANG ),
    ),
    array(
        'type' => 'colorpicker',
        'heading' => __( 'Divider Color', THEME_LANG ),
        'param_name' => 'bottom_divider_color',
        'description' => __( 'Select divider Color', THEME_LANG ),
        'group' => __( 'Extra', 'js_composer' ),
        'dependency' => array(
            'element' => 'bottom_section',
            'value' => array('divider'),
        ),
    ),


));

vc_add_params("vc_row_inner", array(
    array(
        'type' => 'dropdown',
        'heading' => __( 'Set Columns to Equal Height', THEME_LANG ),
        'param_name' => 'equal_height',
        'description' => __( 'Check here if you want column equal height.', THEME_LANG ),
        'value' => array(
            __("None", THEME_LANG) => "",
            __("Column", THEME_LANG) => "column",
            __("Element", THEME_LANG) => "element",
        ),
        'group' => __( 'Extra', 'js_composer' ),
    ),
    array(
        'type' => 'colorpicker',
        'heading' => __( 'Font Color', THEME_LANG ),
        'param_name' => 'font_color',
        'description' => __( 'Select Font Color', THEME_LANG ),
        'group' => __( 'Extra', 'js_composer' ),
    ),
));


vc_add_params( 'vc_single_image', array(
    array(
        'type' => 'dropdown',    
        'heading' => __("Show Social", THEME_LANG),    
        'param_name' => 'show_social',
        'value' => array( 
            __("None", THEME_LANG) => "",
            __("Yes", THEME_LANG) => "yes",
        ),
        'description' => __( "Image social shar", THEME_LANG),
    )
));

vc_add_params("vc_icon", array(
    array(
        'type' => 'colorpicker',
        'heading' => __( 'Icon color on Hover', 'js_composer' ),
        'param_name' => 'color_hover',
        'description' => __( 'Select icon color on hover.', 'js_composer' ),
        'group' => __( 'Hover', 'js_composer' ),
    ),
    array(
        'type' => 'colorpicker',
        'heading' => __( 'Background on Hover', 'js_composer' ),
        'param_name' => 'background_color_hover',
        'description' => __( 'Select Background icon color on hover.', 'js_composer' ),
        'group' => __( 'Hover', 'js_composer' ),
        'dependency' => array(
            'element' => 'background_style',
            'not_empty' => true,
        ),
    ),
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
    'gallery-justified.php',
    'kt_image_gallery.php',
    'gallery-grid.php',

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
    'instagram_carousel.php',
);

foreach ( $composer_addons as $addon ) {
	require_once( FW_DIR . 'js_composer/vc_addons/' . $addon );
}

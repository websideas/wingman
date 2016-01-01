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
        "heading" => __("Letter spacing", 'wingman'),
        "param_name" => "letter_spacing",
        "min" => 0,
        "suffix" => "px",
        'group' => __( 'Extra', 'js_composer' )
    )
));



vc_add_params("vc_btn", array(
    array(
        "type" => "kt_icons",
        'heading' => __( 'Choose your icon', 'js_composer' ),
        'param_name' => 'button_icon',
        "value" => '',
        'description' => __( 'Use existing font icon or upload a custom image.', 'wingman' ),
        'dependency' => array( 'element' => 'add_icon',  'not_empty' => true ),
    ),
    vc_map_add_css_animation( true ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Extra class name', 'js_composer' ),
        'param_name' => 'el_class',
        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
    ),
    array(
        "type" => "kt_number",
        "heading" => __("Letter spacing", 'wingman'),
        "param_name" => "letter_spacing",
        "value" => 0,
        "min" => 0,
        "max" => 10,
        "suffix" => "px",
        "description" => "",
        'group' => __( 'Typography', 'wingman' ),
    ),

));







$visibilities_arr = array('vc_empty_space');
foreach($visibilities_arr as $item){
    vc_add_param($item, array(
        "type" => "dropdown",
        "heading" => __("Visibility",'wingman'),
        "param_name" => "visibility",
        "value" => array(
            __('Always Visible', 'wingman') => '',
            __('Visible on Phones', 'wingman') => 'visible-xs-block',
            __('Visible on Tablets', 'wingman') => 'visible-sm-block',
            __('Visible on Desktops', 'wingman') => 'visible-md-block',
            __('Visible on Desktops Large', 'wingman') => 'visible-lg-block',

            __('Hidden on Phones', 'wingman') => 'hidden-xs',
            __('Hidden on Tablets', 'wingman') => 'hidden-sm',
            __('Hidden on Desktops', 'wingman') => 'hidden-md',
            __('Hidden on Desktops Large', 'wingman') => 'hidden-lg',
        ),
        "description" => __("",'wingman'),
        "admin_label" => true,
    ));
}



$composer_addons = array(
    'dropcap.php',
    'blockquote.php',
    'list.php',
    'heading.php',

    'blog_posts.php',
    
    'icon_box.php',
    'contact_info.php',
    'clients_carousel.php',
    'testimonial_carousel.php',

    'skill.php',
    'socials.php',
    'team.php',
    'coming_soon.php',
    'googlemap.php',

);

if(kt_is_wc()){
    $wc_addons = array(
        'product_category_banner.php',
        'category_products_tab.php',
        'products_carousel.php',
        'products_carousel_tab.php',
    );

    $composer_addons = array_merge($composer_addons, $wc_addons);
}

foreach ( $composer_addons as $addon ) {
	require KT_FW_DIR . 'js_composer/vc_addons/' . $addon;
}

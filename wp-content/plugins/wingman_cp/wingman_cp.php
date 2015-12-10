<?php
/*
Plugin Name:  Wingman Custom Post
Plugin URI:   http://kitethemes.com/
Description:  Theme Wingman Custom Post
Version:      1.1
Author:       KiteThemes
Author URI:   http://themeforest.net/user/kite-themes

Copyright (C) 2014-2015, by Cuongdv
All rights reserved.
*/


add_action( 'init', 'register_kt_client_init' );
function register_kt_client_init(){
    $labels = array( 
        'name' => __( 'Client', 'wingman_cp'),
        'singular_name' => __( 'Client', 'wingman_cp'),
        'add_new' => __( 'Add New', 'wingman_cp'),
        'all_items' => __( 'All Clients', 'wingman_cp'),
        'add_new_item' => __( 'Add New Client', 'wingman_cp'),
        'edit_item' => __( 'Edit Client', 'wingman_cp'),
        'new_item' => __( 'New Client', 'wingman_cp'),
        'view_item' => __( 'View Client', 'wingman_cp'),
        'search_items' => __( 'Search Client', 'wingman_cp'),
        'not_found' => __( 'No Client found', 'wingman_cp'),
        'not_found_in_trash' => __( 'No Client found in Trash', 'wingman_cp'),
        'parent_item_colon' => __( 'Parent Client', 'wingman_cp'),
        'menu_name' => __( 'Clients', 'wingman_cp')
    );
    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'supports' 	=> array('title', 'thumbnail'),
    );
    register_post_type( 'kt_client', $args );
    
    register_taxonomy('client-category',array('kt_client'), array(
        "label" 						=> __("Client Categories", 'wingman_cp'),
        "singular_label" 				=> __("Client Category", 'wingman_cp'),
        'public'                        => false,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => false,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite'                       => false,
        'query_var'                     => true,
        'show_admin_column'             => true
    ));
}


add_action( 'init', 'register_kt_testimonial_init' );
function register_kt_testimonial_init(){
    $labels = array(
        'name' => __( 'Testimonial', 'wingman_cp'),
        'singular_name' => __( 'Testimonial', 'wingman_cp'),
        'add_new' => __( 'Add New', 'wingman_cp'),
        'all_items' => __( 'Testimonials', 'wingman_cp'),
        'add_new_item' => __( 'Add New testimonial', 'wingman_cp'),
        'edit_item' => __( 'Edit testimonial', 'wingman_cp'),
        'new_item' => __( 'New testimonial', 'wingman_cp'),
        'view_item' => __( 'View testimonial', 'wingman_cp'),
        'search_items' => __( 'Search testimonial', 'wingman_cp'),
        'not_found' => __( 'No testimonial found', 'wingman_cp'),
        'not_found_in_trash' => __( 'No testimonial found in Trash', 'wingman_cp'),
        'parent_item_colon' => __( 'Parent testimonial', 'wingman_cp'),
        'menu_name' => __( 'Testimonials', 'wingman_cp')
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'supports' 	=> array('title', 'editor', 'thumbnail'),
    );
    register_post_type( 'kt_testimonial', $args );
    
    register_taxonomy('testimonial-category',array('kt_testimonial'), array(
        "label" 						=> __("Testimonial Categories", 'wingman_cp'), 
        "singular_label" 				=> __("Testimonial Category", 'wingman_cp'), 
        'public'                        => false,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => false,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite'                       => false,
        'query_var'                     => true,
        'show_admin_column'             => true
    ));
}
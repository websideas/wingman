<?php
/*
Plugin Name:  Wingman Custom Post
Plugin URI:   http://kitethemes.com/
Description:  Theme Wingman Custom Post
Version:      1.1
Author:       KiteThemes
Author URI:   http://kitethemes.com/

Copyright (C) 2014-2015, by Cuongdv
All rights reserved.
*/


add_action( 'init', 'register_kt_client_init' );
function register_kt_client_init(){
    $labels = array( 
        'name' => __( 'Client', 'valorous_cp'),
        'singular_name' => __( 'Client', 'valorous_cp'),
        'add_new' => __( 'Add New', 'valorous_cp'),
        'all_items' => __( 'All Clients', 'valorous_cp'),
        'add_new_item' => __( 'Add New Client', 'valorous_cp'),
        'edit_item' => __( 'Edit Client', 'valorous_cp'),
        'new_item' => __( 'New Client', 'valorous_cp'),
        'view_item' => __( 'View Client', 'valorous_cp'),
        'search_items' => __( 'Search Client', 'valorous_cp'),
        'not_found' => __( 'No Client found', 'valorous_cp'),
        'not_found_in_trash' => __( 'No Client found in Trash', 'valorous_cp'),
        'parent_item_colon' => __( 'Parent Client', 'valorous_cp'),
        'menu_name' => __( 'Clients', 'valorous_cp')
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
        "label" 						=> __("Client Categories", 'valorous_cp'),
        "singular_label" 				=> __("Client Category", 'valorous_cp'),
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
        'name' => __( 'Testimonial', 'valorous_cp'),
        'singular_name' => __( 'Testimonial', 'valorous_cp'),
        'add_new' => __( 'Add New', 'valorous_cp'),
        'all_items' => __( 'Testimonials', 'valorous_cp'),
        'add_new_item' => __( 'Add New testimonial', 'valorous_cp'),
        'edit_item' => __( 'Edit testimonial', 'valorous_cp'),
        'new_item' => __( 'New testimonial', 'valorous_cp'),
        'view_item' => __( 'View testimonial', 'valorous_cp'),
        'search_items' => __( 'Search testimonial', 'valorous_cp'),
        'not_found' => __( 'No testimonial found', 'valorous_cp'),
        'not_found_in_trash' => __( 'No testimonial found in Trash', 'valorous_cp'),
        'parent_item_colon' => __( 'Parent testimonial', 'valorous_cp'),
        'menu_name' => __( 'Testimonials', 'valorous_cp')
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
        "label" 						=> __("Testimonial Categories", 'valorous_cp'), 
        "singular_label" 				=> __("Testimonial Category", 'valorous_cp'), 
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
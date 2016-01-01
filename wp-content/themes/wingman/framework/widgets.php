<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/** 
 * Widget content
 * 
 */

if ( function_exists('register_sidebar')) {

    function kt_register_sidebars(){

        register_sidebar( array(
            'name' => __( 'Primary Widget Area', 'wingman'),
            'id' => 'primary-widget-area',
            'description' => __( 'The primary widget area', 'wingman'),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => __( 'Shop Widget Area', 'wingman'),
            'id' => 'shop-widget-area',
            'description' => __( 'The shop widget area', 'wingman'),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => __( 'Blog Widget Area', 'wingman'),
            'id' => 'blog-widget-area',
            'description' => __( 'The blog widget area', 'wingman'),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        $count = 6;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(__( 'Sidebar %s', 'wingman'), $i) ,
                'id' => 'sidebar-column-'.$i,
                'description' => sprintf(__( 'The sidebar column %s widget area', 'wingman'), $i),
                'before_widget' => '<div class="widget-container clearfix %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }

        register_sidebar( array(
            'name' => __( 'Footer top', 'wingman'),
            'id' => 'footer-top',
            'description' => __( 'The footer top widget area', 'wingman'),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ) );

        $count = 4;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(__( 'Footer column %s', 'wingman'), $i) ,
                'id' => 'footer-column-'.$i,
                'description' => sprintf(__( 'The footer column %s widget area', 'wingman'), $i) ,
                'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }

        register_sidebar( array(
            'name' => __( 'Footer bottom', 'wingman'),
            'id' => 'footer-bottom',
            'description' => __( 'The footer bottom widget area', 'wingman'),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ) );


        $sidebars =  kt_option('custom_sidebars');
        if( !empty( $sidebars ) && is_array( $sidebars ) ){
            foreach( $sidebars as $sidebar ){
                $sidebar =  wp_parse_args($sidebar, array('title'=>'','description'=>''));
                if(  $sidebar['title'] !='' ){
                    $id = sanitize_title( $sidebar['title'] );
                    register_sidebar( array(
                        'name' => $sidebar['title'],
                        'id' => $id,
                        'description' => $sidebar['description'],
                        'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h3 class="widget-title">',
                        'after_title' => '</h3>',
                    ) );

                }
            }
        }

    }

    add_action( 'widgets_init', 'kt_register_sidebars' );

}

/**
 * This code filters the categories widget to include the post count inside the link
 */
 

add_filter('wp_list_categories', 'kt_cat_count_span');
function kt_cat_count_span($links) {

    if (strpos($links, '</a>') !== false) {
        $links = str_replace('</a> (', ' <span>(', $links);
        $links = str_replace('</a> <', ' <', $links);
        $links = str_replace(')', ')</span></a>', $links);
        $links = str_replace('</a></span>', '</a>', $links);
    }

    
    return $links;
}

/**
 * This code filters the Archive widget to include the post count inside the link
 */

add_filter('get_archives_link', 'kt_archive_count_span');
function kt_archive_count_span($links) {
    if ( strpos($links, '</a>') !== false ) {
        $links = str_replace('</a>&nbsp;(', ' <span>(', $links);
        $links = str_replace(')', ')</span></a>', $links);
    }
    return $links;
}

/**
 * Include widgets.
 *
 */

/* Widgets list */
$kt_widgets = array(
    'kt_aboutme.php',
    'kt_ads.php',
    'kt_article.php',
    'kt_contactinfo.php',
    'kt_image.php',
    'kt_socials.php'
);

foreach ( $kt_widgets as $widget ) {
	require KT_FW_WIDGETS . $widget;
}



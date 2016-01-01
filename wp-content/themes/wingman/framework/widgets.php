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
            'name' => __( 'Primary Widget Area', KT_THEME_LANG),
            'id' => 'primary-widget-area',
            'description' => __( 'The primary widget area', KT_THEME_LANG),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => __( 'Shop Widget Area', KT_THEME_LANG),
            'id' => 'shop-widget-area',
            'description' => __( 'The shop widget area', KT_THEME_LANG),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => __( 'Blog Widget Area', KT_THEME_LANG),
            'id' => 'blog-widget-area',
            'description' => __( 'The blog widget area', KT_THEME_LANG),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        $count = 6;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => __( 'Sidebar '.$i, KT_THEME_LANG),
                'id' => 'sidebar-column-'.$i,
                'description' => __( 'The sidebar column '.$i.' widget area', KT_THEME_LANG),
                'before_widget' => '<div class="widget-container clearfix %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }

        register_sidebar( array(
            'name' => __( 'Footer top', KT_THEME_LANG),
            'id' => 'footer-top',
            'description' => __( 'The footer top widget area', KT_THEME_LANG),
            'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ) );

        $count = 4;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => __( 'Footer column '.$i, KT_THEME_LANG),
                'id' => 'footer-column-'.$i,
                'description' => __( 'The footer column '.$i.' widget area', KT_THEME_LANG),
                'before_widget' => '<div id="%1$s" class="widget-container clearfix %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }

        register_sidebar( array(
            'name' => __( 'Footer bottom', KT_THEME_LANG),
            'id' => 'footer-bottom',
            'description' => __( 'The footer bottom widget area', KT_THEME_LANG),
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
	'kt_image.php',
    'kt_article.php',
    'kt_widget_tabs.php',
    'kt_ads.php',
    'kt_flickr.php',
    'kt_popular.php',
    'kt_aboutme.php',
    'kt_socials.php',
    'kt_contactinfo.php',
);

foreach ( $kt_widgets as $widget ) {
	require_once( KT_FW_WIDGETS . $widget );

}



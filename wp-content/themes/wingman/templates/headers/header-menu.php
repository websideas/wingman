<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


if ( has_nav_menu( 'primary' ) ) {
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'container' => '',
        'link_before'     => '<span>',
        'link_after'      => '</span>',
        'menu_id'         => 'main-navigation',
        'menu_class' => 'hidden-xs hidden-sm',
        'walker' => new KTMegaWalker(),
    ) );
}else{
    printf(
        '<ul id="main-navigation"><li><a href="%s">%s</a></li></ul>',
        admin_url( 'nav-menus.php'),
        __("Define your site main menu!", KT_THEME_LANG)
    );
}
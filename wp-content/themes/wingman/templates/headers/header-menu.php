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
        '<ul><li><a href="#">%s</a></li></ul>',
        __("Define your site main menu!", THEME_LANG)
    );
}
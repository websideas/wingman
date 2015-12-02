<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if ( has_nav_menu( 'primary' ) ) {
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'container' => 'nav',
        'container_class' => 'main-nav-mobile',
        'container_id' => 'main-nav-mobile',
        'menu_class' => 'menu navigation-mobile',
        'link_before'     => '<span>',
        'link_after'      => '</span>',
        'walker' => new KTMegaWalker(),
        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s<li class="menu-item menu-item-search-form">'.get_search_form( false ).'</li></ul>',
    ) );

}
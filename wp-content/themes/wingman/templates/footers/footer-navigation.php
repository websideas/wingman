<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if ( has_nav_menu( 'bottom' ) ) {
    wp_nav_menu( array( 'theme_location' => 'bottom', 'container' => 'nav', 'container_id' => 'bottom-nav' ) );
}
<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$logo = kt_get_logo();

?>
<div id="mobile-nav-holder">
    <div id="mobile-nav-content">

        <div id="mobile-nav-logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <!--<img src="<?php echo esc_url($logo['default']); ?>" class="logo-light" alt="<?php bloginfo( 'name' ); ?>" />-->
                <img src="<?php echo esc_url($logo['logo_dark']); ?>" class="logo-dark" alt="<?php bloginfo( 'name' ); ?>" />
            </a>
        </div>

        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container' => 'nav',
                'container_id' => 'main-nav-mobile',
                'menu_class' => 'menu navigation-mobile',
                'link_before'     => '<span>',
                'link_after'      => '</span>',
                'walker' => new KTMegaWalker(),
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s<li class="menu-item menu-item-search-form">'.get_search_form( false ).'</li></ul>',
            ) );

        }
        ?>

    </div>
</div>
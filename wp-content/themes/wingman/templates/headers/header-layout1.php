<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div id="header-inner" class="clearfix">

    <div class="container">
        <div class="header-branding-outer">
            <div class="site-branding">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
            </div><!-- .site-branding -->
            <?php get_template_part( 'templates/headers/header',  'tool'); ?>
            <?php get_template_part( 'templates/headers/header',  'bars'); ?>
        </div>
    </div><!-- .container -->
    <div class="nav-container apply-sticky">
        <div class="container">
            <nav id="nav" class="nav-main">

                <?php get_template_part( 'templates/headers/header',  'menu'); ?>

            </nav><!-- #main-nav -->
        </div><!-- .container -->
    </div>
</div>
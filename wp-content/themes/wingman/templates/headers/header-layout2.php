<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div class="container">
    <div id="header-inner" class="clearfix">
        <div class="site-branding">
            <?php get_template_part( 'templates/headers/header',  'branding'); ?>
        </div><!-- .site-branding -->
        <nav id="nav" class="nav-main">
            <?php get_template_part( 'templates/headers/header',  'tool'); ?>
            <?php get_template_part( 'templates/headers/header',  'menu'); ?>
            <?php get_template_part( 'templates/headers/header',  'mobile'); ?>
            <div class="button-toggle hidden-xs hidden-sm">
                <a href="#" class="main-nav-simple">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="close"></span>
                </a>
            </div>
        </nav><!-- #main-nav -->
    </div><!-- #header-inner -->
</div><!-- .container -->
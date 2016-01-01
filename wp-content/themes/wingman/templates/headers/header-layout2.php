<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$position = kt_get_header();

?>
<div id="header-outer" class="clearfix">
    <?php
    if($position == 'below'){
        /**
         * @hooked kt_slideshows_position_callback 10
         */
        do_action( 'kt_slideshows_position' );
    }
    ?>

    <div class="nav-container-inner">
        <div id="header-content" class="clearfix apply-sticky">
            <div class="header-sticky-background"></div>
            <div class="site-branding">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
            </div><!-- .site-branding -->

            <div class="header-actions">
                <?php get_template_part( 'templates/headers/header',  'bars'); ?>
                <?php get_template_part( 'templates/headers/header',  'tool'); ?>
            </div>

            <div class="nav-container">
                <div class="container">
                    <nav id="nav" class="nav-main">
                        <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                    </nav><!-- #main-nav -->
                </div><!-- .container -->
            </div><!-- .nav-container -->
        </div>
        <?php
        if($position != 'below'){
            /**
             * @hooked kt_slideshows_position_callback 10
             */
            do_action( 'kt_slideshows_position' );
        }
        ?>
    </div>
</div>
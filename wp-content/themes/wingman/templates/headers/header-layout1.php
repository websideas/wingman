<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$position = kt_get_header();

?>
<div id="header-inner" class="clearfix">

    <?php
    if($position == 'below'){
        /**
         * @hooked kt_slideshows_position_callback 10
         */
        do_action( 'kt_slideshows_position' );
    }
    ?>

    <div class="container">
        <div class="header-branding-outer">
            <div class="site-branding">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
            </div><!-- .site-branding -->
            <?php get_template_part( 'templates/headers/header',  'tool'); ?>
            <?php get_template_part( 'templates/headers/header',  'bars'); ?>
        </div>
    </div><!-- .container -->

    <div class="nav-container ">
        <div class="nav-container-inner apply-sticky">
            <div class="header-sticky-background"></div>
            <div class="container">
                <nav id="nav" class="nav-main">
                    <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                </nav><!-- #main-nav -->
            </div><!-- .container -->
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
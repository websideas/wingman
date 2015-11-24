<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$position = kt_get_header();

?>
<div id="header-outer" class="clearfix">
    <div class="nav-container-inner">
    <?php
    if($position == 'above'){
        /**
         * @hooked kt_slideshows_position_callback 10
         */
        do_action( 'kt_slideshows_position' );
    }
    ?>

    <div id="header-content" class="clearfix">
        <div class="site-branding">
            <?php get_template_part( 'templates/headers/header',  'branding'); ?>
        </div><!-- .site-branding -->

        <div class="header-actions">
            <?php get_template_part( 'templates/headers/header',  'bars'); ?>
            <?php get_template_part( 'templates/headers/header',  'tool'); ?>
        </div>

        <div class="nav-container apply-sticky">

                <div class="container">
                    <nav id="nav" class="nav-main">
                        <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                    </nav><!-- #main-nav -->
                </div><!-- .container -->


        </div>
    </div>


    <?php
    if($position != 'above'){
        /**
         * @hooked kt_slideshows_position_callback 10
         */
        do_action( 'kt_slideshows_position' );
    }
    ?>

    </div>



</div>
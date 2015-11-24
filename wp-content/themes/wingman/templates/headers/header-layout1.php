<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$position = kt_get_header();

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


    <?php
    if($position == 'above'){
        /**
         * @hooked kt_slideshows_position_callback 10
         */
        do_action( 'kt_slideshows_position' );
    }
    ?>
        <div class="nav-container apply-sticky">
            <div class="nav-container-inner">
                <div class="container">
                    <nav id="nav" class="nav-main">
                        <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                    </nav><!-- #main-nav -->
                </div><!-- .container -->
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
<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit; ?>

<div class="header-contact">              
    <?php 
        $phone = kt_option('header_phone');
        $email = kt_option('header_email');
        if($phone){
            echo sprintf(
                    '<span class="contact-phone">%s %s</span>', 
                    '<i class="fa fa-phone"></i>', 
                    do_shortcode($phone)
                );
        }
        if($email){
            echo sprintf(
                    '<span class="contact-envelope">%s %s</span>', 
                    '<i class="fa fa-envelope"></i>', 
                    do_shortcode($email)
                );
        } 
    ?>
</div>
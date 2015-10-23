<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

?>

<ul id="main-nav-tool" class="hidden-xs hidden-sm">
    <?php if ( kt_is_wc() && kt_option('header_cart', 1) ) { ?>
        <?php echo kt_woocommerce_get_cart(true); ?>
    <?php } ?>
    <?php if ( kt_option('header_search', 1) ) { ?>
        <li class="mini-search">
            <a href="#"><span class="icon-magnifier"></span></a>
        </li>
    <?php } ?>
    <?php if ( kt_option('header_language', 1) ) { ?>
        <?php kt_custom_wpml(); ?>
    <?php } ?>
</ul>


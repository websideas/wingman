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
            <a href="#"><i class="fa fa-search"></i></a>
        </li>
    <?php } ?>
    <?php do_action('theme_main_tool'); ?>
</ul>


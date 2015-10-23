<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div id="header-content-mobile" class="visible-xs-block visible-sm-block">
    <?php if ( kt_is_wc() && kt_option('header_cart', 1) ) { ?>
        <?php echo kt_woocommerce_get_cart_mobile(); ?>
    <?php } ?>
    <a href="#" class="mobile-nav-bar">
        <span class="mobile-nav-handle"><span></span></span>
    </a>
</div>
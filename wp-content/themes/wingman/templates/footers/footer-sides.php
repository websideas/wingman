<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


$footer_left = kt_option('footer_copyright_left');
$footer_right = kt_option('footer_copyright_right');

if(!$footer_left && !$footer_right) return;

?>
<div class="display-table">
    <div class="display-cell footer-left">
        <?php get_template_part( 'templates/footers/footer', $footer_left ); ?>
    </div>
    <div class="display-cell footer-right">
        <?php get_template_part( 'templates/footers/footer', $footer_right ); ?>
    </div>
</div>
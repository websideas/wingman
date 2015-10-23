<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if($copyright = kt_option('footer_copyright_text')){
    echo sprintf('<div class="footer-copyright">%s</div>', do_shortcode(kt_option('footer_copyright_text')));
}
<?php
function valorous_child_scripts() {
    wp_enqueue_style( 'wingman-child-stylesheet', get_template_directory_uri() . '/style.css' );
}
add_action('wp_enqueue_scripts', 'valorous_child_scripts');
<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$logo = kt_get_logo();


?>

<?php $tag = ( is_front_page() && is_home() ) ? 'h1' : 'p'; ?>
<<?php echo esc_attr($tag) ?> class="site-logo">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
        <img src="<?php echo esc_url($logo['default']); ?>" class="logo-light" alt="<?php bloginfo( 'name' ); ?>" />
        <img src="<?php echo esc_url($logo['logo_dark']); ?>" class="logo-dark" alt="<?php bloginfo( 'name' ); ?>" />
    </a>
</<?php echo esc_attr($tag) ?>><!-- .site-logo -->
<div id="site-title"><?php bloginfo( 'name' ); ?></div>
<div id="site-description"><?php bloginfo( 'description' ); ?></div>
<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @since 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo THEME_JS; ?>html5shiv.min.js"></script>
      <script src="<?php echo THEME_JS; ?>respond.min.js"></script>
    <![endif]-->
    <link href='https://fonts.googleapis.com/css?family=Josefin+Slab:400,700' rel='stylesheet' type='text/css' />
	<?php wp_head(); ?>
</head>
<body <?php body_class( ); ?>>
    <?php

    /**
     * @hooked
     */
    do_action( 'theme_body_top' );

    get_template_part( 'searchform',  'full');

    $position = kt_get_header();
    $header_layout = kt_get_header_layout();

    $header_scheme = 'light';
    if($position == 'transparent'){
        $header_positon = 'absolute';
        $header_scheme = kt_get_header_scheme();
    }else{
        $header_positon = 'normal';
    }

    ?>

    <div id="page_outter">
        <div id="page">
            <div class="animate-content-overlay"></div>
            <div id="wrapper-content">

                <?php
            	/**
            	 * @hooked 
            	 */
            	do_action( 'theme_before_header' ); ?>
                <div class="<?php echo esc_attr(apply_filters('theme_header_class', 'header-container header-'.$header_layout.' header-'.$header_scheme.' header-'.$header_positon.' header-'.$position, $header_layout)); ?>" data-scheme="<?php echo esc_attr($header_scheme) ?>" data-position="<?php echo esc_attr($header_positon) ?>">
                    <div class="header-background"></div>
                    <header id="header" class="<?php echo apply_filters('theme_header_content_class', 'header-content', $header_layout) ?>">
                        <div class="header-sticky-background"></div>
                        <?php get_template_part( 'templates/headers/header',  $header_layout); ?>
                    </header><!-- #header -->
                </div><!-- .header-container -->
                
                <?php
                    /**
                     * @hooked theme_before_content_add_title 10
                     *
                     */
                    do_action( 'theme_before_content' , $position);
                ?>
                <div id="content" class="<?php echo apply_filters('kt_content_class', 'site-content') ?>">

                    <?php
            		/**
            		 * @hooked
            		 */
            		do_action( 'theme_content_top' ); ?>
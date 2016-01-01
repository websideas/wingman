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
      <script src="<?php echo KT_THEME_JS; ?>html5shiv.min.js"></script>
      <script src="<?php echo KT_THEME_JS; ?>respond.min.js"></script>
    <![endif]-->

	<?php wp_head(); ?>
</head>
<body <?php body_class( ); ?>>
    <?php
    do_action( 'kt_body_top' );
    $position = kt_get_header();
    $header_layout = kt_get_header_layout();
    ?>
    <div id="page_outter">
        <div id="page">
            <div id="wrapper-content">
                <?php do_action( 'kt_before_header' ); ?>
                <?php
                    get_template_part( 'templates/headers/header',  'mobile');
                    get_template_part( 'templates/headers/header',  'mobilenav');
                ?>
                <div class="<?php echo esc_attr(apply_filters('theme_header_class', 'header-container header-'.$header_layout.' header-'.$position, $header_layout)); ?>">
                    <header id="header" class="<?php echo apply_filters('theme_header_content_class', 'header-content', $header_layout) ?>">
                        <?php get_template_part( 'templates/headers/header',  $header_layout); ?>
                    </header><!-- #header -->
                </div><!-- .header-container -->
                
                <?php do_action( 'kt_before_content' , $position);  ?>
                <div id="content" class="<?php echo apply_filters('kt_content_class', 'site-content') ?>">
                    <?php do_action( 'kt_content_top' ); ?>
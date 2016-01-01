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
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <?php do_action( 'kt_body_top' ); ?>
    <div id="content" class="<?php echo apply_filters('kt_content_class', 'site-content') ?>">
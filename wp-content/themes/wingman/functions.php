<?php
//session_start();
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

// Script version, used to add version for scripts and styles
define( 'THEME_VER', '1.0' );

define( 'THEME_OPTIONS', 'valorous_option' );
define( 'THEME_LANG', 'valorous');

define( 'THEME_DIR', trailingslashit(get_template_directory()));
define( 'THEME_URL', trailingslashit(get_template_directory_uri()));
define( 'THEME_INC', trailingslashit(THEME_DIR.'inc'));
define( 'THEME_TEMP', trailingslashit(THEME_DIR.'templates'));

define( 'THEME_ASSETS', trailingslashit( THEME_URL . 'assets' ) );
define( 'THEME_FONTS', trailingslashit( THEME_ASSETS . 'fonts' ) );
define( 'THEME_LIBS', trailingslashit( THEME_ASSETS . 'libs' ) );
define( 'THEME_JS', trailingslashit( THEME_ASSETS . 'js' ) );
define( 'THEME_CSS', trailingslashit( THEME_ASSETS . 'css' ) );
define( 'THEME_IMG', trailingslashit( THEME_ASSETS . 'images' ) );

//Include framework
require_once ( THEME_DIR .'framework/core.php');


// Get All meta box for all post type.
if ( class_exists( 'KT_Meta_Box' ) ) {
	require_once (THEME_FW_DATA . 'data-metaboxes.php');
}

<?php
//session_start();
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

// Script version, used to add version for scripts and styles
define( 'KT_THEME_VER', '1.1' );

define( 'KT_THEME_OPTIONS', 'wingman_option' );

define( 'KT_THEME_DIR', get_template_directory());
define( 'KT_THEME_URL', get_template_directory_uri());
define( 'KT_THEME_DATA', KT_THEME_URL.'/dummy-data');
define( 'KT_THEME_DATA_DIR', KT_THEME_DIR.'/dummy-data');
define( 'KT_THEME_TEMP', KT_THEME_DIR.'/templates');

define( 'KT_THEME_ASSETS', KT_THEME_URL . '/assets' );
define( 'KT_THEME_FONTS', KT_THEME_ASSETS . '/fonts' );
define( 'KT_THEME_LIBS', KT_THEME_ASSETS . '/libs' );
define( 'KT_THEME_JS', KT_THEME_ASSETS . '/js');
define( 'KT_THEME_CSS', KT_THEME_ASSETS . '/css');
define( 'KT_THEME_IMG', KT_THEME_ASSETS . '/images');

//Include framework
require KT_THEME_DIR .'/framework/core.php';

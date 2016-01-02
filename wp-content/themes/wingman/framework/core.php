<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

define( 'FW_VER', '1.0' );

define( 'KT_FW_DIR', trailingslashit(KT_THEME_DIR.'framework'));
define( 'KT_FW_URL', trailingslashit(KT_THEME_URL.'framework'));

define( 'KT_FW_EXT_DIR', trailingslashit( KT_FW_DIR . 'extensions' ) );
define( 'KT_FW_EXT_URL', trailingslashit( KT_FW_URL . 'extensions' ) );

define( 'KT_FW_PLUGINS_DIR', trailingslashit( KT_FW_DIR . 'plugins' ) );

define( 'KT_FW_EXT_CUSTOM_DIR', trailingslashit( KT_FW_DIR . 'extensions-custom' ) );
define( 'KT_FW_EXT_CUSTOM_URL', trailingslashit( KT_FW_URL . 'extensions-custom' ) );


define( 'KT_FW_WIDGETS', trailingslashit( KT_FW_DIR . 'widgets' ) );

define( 'KT_FW_ASSETS', trailingslashit( KT_FW_URL . 'assets' ) );
define( 'KT_FW_JS', trailingslashit( KT_FW_ASSETS . 'js' ) );
define( 'KT_FW_CSS', trailingslashit( KT_FW_ASSETS . 'css' ) );
define( 'KT_FW_IMG', trailingslashit( KT_FW_ASSETS . 'images' ) );
define( 'KT_FW_LIBS', trailingslashit( KT_FW_ASSETS . 'libs' ) );

define( 'KT_FW_CLASS', trailingslashit( KT_FW_DIR . 'class' ) );
define( 'KT_FW_DATA', trailingslashit( KT_FW_DIR . 'data' ) );


/**
 * All ajax functions
 *
 */
require KT_FW_DIR . 'ajax.php';


/**
 * Get all functions for frontend
 *
 */
require KT_FW_DIR . 'frontend.php';

/**
 * Get functions for framework
 *
 */
require KT_FW_DIR . 'functions.php';

/**
 * Get class helpers in framework
 *
 */
require KT_FW_DIR . 'helpers.php';

/**
 * Breadcrumbs
 *
 */
require KT_FW_DIR . 'breadcrumbs.php';


/**
 * get custom walker for wp_nav_menu
 *
 */
require KT_FW_EXT_DIR .'nav/nav_custom_walker.php';

/**
 * Include the meta-box plugin.
 *
 */

define( 'RWMB_URL', trailingslashit( KT_FW_EXT_URL . 'meta-box' ) );
define( 'RWMB_DIR', trailingslashit( KT_FW_EXT_DIR . 'meta-box' ) );

require RWMB_DIR . 'meta-box.php';

if ( class_exists( 'RW_Meta_Box' ) ) {
    
    // Add fields to metabox
    require KT_FW_EXT_CUSTOM_DIR . 'meta-box-custom.php';

    // Add plugin meta-box-show-hide
    require KT_FW_EXT_DIR . 'meta-box-show-hide/meta-box-show-hide.php';

    // Add plugin meta-box-tabs
    require KT_FW_EXT_DIR . 'meta-box-tabs/meta-box-tabs.php';

	// Add plugin meta-box-group
	require KT_FW_EXT_DIR . 'meta-box-group/meta-box-group.php';

    if (is_admin() ) {
        // Make sure there's no errors when the plugin is deactivated or during upgrade
        require KT_FW_DATA . 'data-meta-box.php';
    }
    
}


/**
 * Include the redux-framework.
 * 
 */

if(!function_exists('kt_register_custom_extension_loader')) :
	function kt_register_custom_extension_loader($ReduxFramework) {
		$path = KT_FW_EXT_DIR . '/ReduxCoreExt/';
		$folders = scandir( $path, 1 );		   
		foreach($folders as $folder) {
			if ($folder === '.' or $folder === '..' or !is_dir($path . $folder) ) {
				continue;	
			} 
			$extension_class = 'ReduxFramework_Extension_' . $folder;
			if( !class_exists( $extension_class ) ) {
				// In case you wanted override your override, hah.
				$class_file = $path . $folder . '/extension_' . $folder . '.php';
				$class_file = apply_filters( 'redux/extension/'.$ReduxFramework->args['opt_name'].'/'.$folder, $class_file );
				if( $class_file ) {
					require $class_file;
					$extension = new $extension_class( $ReduxFramework );
				}
			}
		}
	}
	// Modify {$redux_opt_name} to match your opt_name
	add_action("redux/extensions/".KT_THEME_OPTIONS."/before", 'kt_register_custom_extension_loader', 0);
endif;


add_action('init', 'kt_admin_options_init');
function  kt_admin_options_init(){
    if (file_exists( KT_FW_DATA . 'data-options.php' ) ) {
        require KT_FW_DATA . 'data-options.php';
    }
}

if (is_admin() ) {

	/**
	 * Get plugin require for theme
	 *
	 */
	require KT_FW_CLASS . 'class-tgm-plugin-activation.php';


	/**
	 * Install Plugins
     * 
	 */ 
 	require KT_FW_DATA . 'data-plugins.php';


    /**
     * Get Navigation nav
     *
     */
    require KT_FW_EXT_DIR . 'nav/nav.php';


	/**
	 * Add importer
	 *
	 */
	require KT_FW_DIR . 'importer.php';


    /**
     * Add Admin function
     *
     */
    require KT_FW_DIR . 'admin.php';


}
  
/**
 * Force Visual Composer to initialize as "built into the theme". 
 * This will hide certain tabs under the Settings->Visual Composer page
 */

add_action( 'vc_before_init', 'kt_vcSetAsTheme' );
function kt_vcSetAsTheme() {
    vc_set_as_theme();
}


/**
 * Initialising Visual Composer
 * 
 */ 
if ( class_exists( 'Vc_Manager', false ) ) {


    /* Insert icon to parrams icons */
    require KT_FW_DATA . 'data-icons.php';

    if ( ! function_exists( 'kt_js_composer_bridge_admin' ) ) {
		function kt_js_composer_bridge_admin( $hook ) {
			wp_enqueue_style( 'js_composer_bridge', KT_FW_CSS . 'js_composer_bridge.css', array(), FW_VER );
		}
	}
    add_action( 'admin_enqueue_scripts', 'kt_js_composer_bridge_admin', 15 );


    if ( !function_exists('kt_js_composer_bridge') ) {
		function kt_js_composer_bridge() {
			require KT_FW_DIR . 'js_composer/js_composer_parrams.php';
            require KT_FW_DIR . 'js_composer/js_composer_bridge.php';
		}

        if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
            vc_set_shortcodes_templates_dir( KT_THEME_TEMP . '/vc_templates' );
        }
	}
    add_action( 'init', 'kt_js_composer_bridge', 20 );

    /**
     * Include js_composer update param
     *
     */
    require KT_FW_DIR . 'js_composer/js_composer_update.php';


}





if(kt_is_wc()){
    /**
     * support for woocommerce helpers
     *
     */
    require KT_FW_DIR . 'woocommerce.php';
}




/**
 * Include Widgets register and define all sidebars.
 *
 */
require KT_FW_DIR . 'widgets.php';
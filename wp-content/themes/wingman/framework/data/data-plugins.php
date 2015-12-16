<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;




add_action( 'tgmpa_register', 'kt_register_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function kt_register_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name'          => 'Wingman Custom Post', // The plugin name
            'slug'          => 'wingman_cp', // The plugin slug (typically the folder name)
            'source'            => THEME_DIR.'recommend-plugins/wingman_cp.zip', // The plugin source
            'required'          => true, // If false, the plugin is only 'recommended' instead of required
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'      => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'          => 'KT Mailchimp', // The plugin name
            'slug'          => 'kt_mailchimp', // The plugin slug (typically the folder name)
            'source'            => THEME_DIR.'recommend-plugins/kt_mailchimp.zip', // The plugin source
            'required'          => false, // If false, the plugin is only 'recommended' instead of required
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'      => '', // If set, overrides default API URL and points to an external URL
        ),

        array(
            'name'          => 'WPBakery Visual Composer', // The plugin name
            'slug'          => 'js_composer', // The plugin slug (typically the folder name)
            'source'            => THEME_DIR.'recommend-plugins/js_composer.zip', // The plugin source
            'required'          => true, // If false, the plugin is only 'recommended' instead of required
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'      => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'          => 'Revolution Slider', // The plugin name
            'slug'          => 'revslider', // The plugin slug (typically the folder name)
            'source'            => THEME_DIR.'recommend-plugins/revslider.zip', // The plugin source
            'required'          => true, // If false, the plugin is only 'recommended' instead of required
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'      => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name' => 'Redux - Options framework',
            'slug' => 'redux-framework',
            'required' => true,
        ),
        array(
            'name' => 'WooCommerce - excelling eCommerce',
            'slug' => 'woocommerce',
            'required' => true,
        ),
        array(
            'name' => 'Contact Form 7',
            'slug' => 'contact-form-7',
            'required' => false,
        ),
        array(
            'name' => 'YITH WooCommerce Compare',
            'slug' => 'yith-woocommerce-compare',
            'required' => false,
        ),
        array(
            'name' => 'YITH WooCommerce Wishlist',
            'slug' => 'yith-woocommerce-wishlist',
            'required' => false,
        ),
        array(
            'name' => 'WooCommerce Currency Switcher',
            'slug' => 'woocommerce-currency-switcher',
            'required' => false,
        ),

        //Breadcrumb NavXT

    );
  
    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = THEME_LANG;
  
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'        => $theme_text_domain, // Text domain - likely want to be the same as your theme.
        'default_path'      => '', // Default absolute path to pre-packaged plugins
        'parent_slug'           => 'plugins.php',
        'capability'            => 'manage_options',
        'menu'          => 'install-required-plugins', // Menu slug
        'has_notices'       => true, // Show admin notices or not
        'is_automatic'      => true, // Automatically activate plugins after installation or not
    );
    tgmpa( $plugins, $config );
}
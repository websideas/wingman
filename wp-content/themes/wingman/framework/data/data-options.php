<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


if ( ! class_exists( 'KT_config' ) ) {
    class KT_config{
        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if ( ! class_exists( 'ReduxFramework' ) ) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
            }
        }
        
        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Create the sections and fields
            $this->setSections();

            if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                return;
            }
            
            $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
        }
        
        
        /**
         * All the possible arguments for Redux.
         * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'             => KT_THEME_OPTIONS,
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'         => $theme->get( 'Name' ),
                // Name that appears at the top of your panel
                'display_version'      => $theme->get( 'Version' ),
                // Version that appears at the top of your panel
                'menu_type'            => 'menu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'       => true,
                // Show the sections below the admin menu item or not
                'menu_title'           => __( 'Theme Options', KT_THEME_LANG ),
                
                'page_title'           => $theme->get( 'Name' ).' '.__( 'Theme Options', KT_THEME_LANG ),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key'       => '',
                // Set it you want google fonts to update weekly. A google_api_key value is required.
                'google_update_weekly' => false,
                // Must be defined to add google fonts to the typography module
                'async_typography'     => false,
                // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar'            => false,
                // Show the panel pages on the admin bar
                'admin_bar_icon'     => 'dashicons-portfolio',
                // Choose an icon for the admin bar menu
                'admin_bar_priority' => 50,
                // Choose an priority for the admin bar menu
                'global_variable'      => '',
                // Set a different name for your global variable other than the opt_name
                'dev_mode'             => false,
                // Show the time the page took to load, etc
                'update_notice'        => false,
                // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                'customizer'           => true,
                // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority'        => 61,
                // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'          => 'themes.php',
                // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'     => 'manage_options',
                // Permissions needed to access the options panel.
                'menu_icon'            => 'dashicons-art',
                // Specify a custom URL to an icon
                'last_tab'             => '',
                // Force your panel to always open to a specific tab (by id)
                'page_icon'            => 'icon-themes',
                // Icon displayed in the admin panel next to your menu_title
                'page_slug'            => 'theme_options',
                // Page slug used to denote the panel
                'save_defaults'        => true,
                // On load save the defaults to DB before user clicks save or not
                'default_show'         => false,
                // If true, shows the default value next to each field that is not the default value.
                'default_mark'         => '',
                // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export'   => true,
                // Shows the Import/Export panel when not used as a field.

                // CAREFUL -> These options are for advanced use only
                'transient_time'       => 60 * MINUTE_IN_SECONDS,
                'output'               => true,
                // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'           => true,
                // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'             => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'          => false,
                // REMOVE
            );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url' => 'https://www.facebook.com/kitethemes/',
                'title' => __('Like us on Facebook', KT_THEME_LANG),
                'icon' => 'el-icon-facebook'
            );

            $this->args['share_icons'][] = array(
                'url' => 'http://themeforest.net/user/Kite-Themes/follow?ref=Kite-Themes',
                'title' => __('Follow us on Themeforest', KT_THEME_LANG),
                'icon' => 'fa fa-wordpress'
            );

            $this->args['share_icons'][] = array(
                'url' => '#',
                'title' => __('Get Email Newsletter', KT_THEME_LANG),
                'icon' => 'fa fa-envelope-o'
            );

            $this->args['share_icons'][] = array(
                'url' => 'http://themeforest.net/user/kite-themes/portfolio',
                'title' => __('Check out our works', KT_THEME_LANG),
                'icon' => 'fa fa-briefcase'
            );
            
        }
        
        public function setSections() {
            

            $image_sizes = kt_get_image_sizes();

            $this->sections[] = array(
                'id' 	=> 'general',
                'title'  => __( 'General', KT_THEME_LANG ),
                'desc'   => __( '', KT_THEME_LANG ),
                'icon'	=> 'icon-Settings-Window'
            );
            $this->sections[] = array(
                'id' 	=> 'general_layout',
                'title'  => __( 'General', KT_THEME_LANG ),
                'desc'   => __( '', KT_THEME_LANG ),
                'subsection' => true,
                'fields' => array(
                    array(
                        'id'       => 'archive_placeholder',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => __( 'Placeholder', KT_THEME_LANG ),
                        'subtitle'     => __( "Placeholder for none image", KT_THEME_LANG ),
                    ),

                    array(
                        'id' => 'page_animation',
                        'type' => 'switch',
                        'title' => __('Page Animation', KT_THEME_LANG),
                        'desc' => __('Enable Animation switcher in the page.', KT_THEME_LANG),
                        "default" => 0,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),

                )
            );

            /**
			 *	Logos
			 **/
			$this->sections[] = array(
				'id'			=> 'logos_favicon',
				'title'			=> __( 'Logos', KT_THEME_LANG ),
				'desc'			=> '',
				'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'logos_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Logos settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'logo',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => __( 'Logo', KT_THEME_LANG ),
                    ),
                    array(
                        'id'       => 'logo_retina',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => __( 'Logo (Retina Version @2x)', KT_THEME_LANG ),
                        'desc'     => __('Select an image file for the retina version of the logo. It should be exactly 2x the size of main logo.', KT_THEME_LANG)
                    ),
                )
            );
            
            
            /**
			 *	Header
			 **/
			$this->sections[] = array(
				'id'			=> 'Header',
				'title'			=> __( 'Header', KT_THEME_LANG ),
				'desc'			=> '',
				'subsection' => true,
				'fields'		=> array(

                    array(
                        'id'       => 'header',
                        'type'     => 'image_select',
                        'compiler' => true,
                        'presets'  => true,
                        'title'    => __( 'Header layout', KT_THEME_LANG ),
                        'subtitle' => __( 'Please choose header layout', KT_THEME_LANG ),
                        'options'  => array(
                            'layout1' => array( 
                                'alt' => __( 'Layout 1', KT_THEME_LANG ), 
                                'img' => KT_FW_IMG . 'header/header-v1.png',
                                'presets'   => array(
                                    'logo_margin_spacing' => array( 'margin-top' => '40px','margin-bottom' => '40px' ),
                                    'navigation_height' => array( 'height' => '60', 'units'  => 'px' ),
                                    'navigation_color' => '#ffffff',
                                    'header_sticky_background' => array( 'background-color' => '#252525' )
                                )
                            ),
                            'layout2' => array( 
                                'alt' => __( 'Layout 2', KT_THEME_LANG ), 
                                'img' => KT_FW_IMG . 'header/header-v2.png',
                                'presets'   => array(
                                    'logo_margin_spacing' => array( 'margin-top' => '0px','margin-bottom' => '0px' ),
                                    'navigation_height' => array( 'height' => '120', 'units'  => 'px' ),
                                    'navigation_color' => '#252525',
                                    'header_sticky_background' => array( 'background-color' => '#ffffff' )
                                )
                            ),
                        ),
                        'default'  => 'layout1'
                    ),

                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id' => 'header_search',
                        'type' => 'switch',
                        'title' => __('Search Icon', KT_THEME_LANG),
                        'desc' => __('Enable the search Icon in the header.', KT_THEME_LANG),
                        "default" => 1,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),

                )
            );


            /**
			 *	Footer
			 **/
			$this->sections[] = array(
				'id'			=> 'footer',
				'title'			=> __( 'Footer', KT_THEME_LANG ),
				'desc'			=> '',
				'subsection' => true,
				'fields'		=> array(
                    // Footer settings
                    
                    array(
                        'id'       => 'backtotop',
                        'type'     => 'switch',
                        'title'    => __( 'Back to top', KT_THEME_LANG ),
                        'default'  => true,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),

                    array(
                        'id'       => 'footer_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer',
                        'type'     => 'switch',
                        'title'    => __( 'Footer enable', KT_THEME_LANG ),
                        'default'  => true,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),

                    // Footer Top settings
                    array(
                        'id'       => 'footer_top_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer top settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_top',
                        'type'     => 'switch',
                        'title'    => __( 'Footer top enable', KT_THEME_LANG ),
                        'default'  => true,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),

                    // Footer widgets settings
                    array(
                        'id'       => 'footer_widgets_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer widgets settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_widgets',
                        'type'     => 'switch',
                        'title'    => __( 'Footer widgets enable', KT_THEME_LANG ),
                        'default'  => true,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),

                    array(
                        'id'       => 'footer_widgets_layout',
                        'type'     => 'image_select',
                        'compiler' => true,
                        'title'    => __( 'Footer widgets layout', KT_THEME_LANG ),
                        'subtitle' => __( 'Select your footer widgets layout', KT_THEME_LANG ),
                        'options'  => array(
                            '3-3-3-3' => array( 'alt' => __( 'Layout 1', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-1.png' ),
                            '6-3-3' => array( 'alt' => __( 'Layout 2', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-2.png' ),
                            '3-3-6' => array( 'alt' => __( 'Layout 3', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-3.png' ),
                            '6-6' => array( 'alt' => __( 'Layout 4', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-4.png' ),
                            '4-4-4' => array( 'alt' => __( 'Layout 5', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-5.png' ),
                            '8-4' => array( 'alt' => __( 'Layout 6', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-6.png' ),
                            '4-8' => array( 'alt' => __( 'Layout 7', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-7.png' ),
                            '3-6-3' => array( 'alt' => __( 'Layout 8', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-8.png' ),
                            '12' => array( 'alt' => __( 'Layout 9', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'footer/footer-9.png' ),
                        ),
                        'default'  => '3-3-3-3'
                    ),
                    
                    /* Footer bottom */
                    array(
                        'id'       => 'footer_bottom_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer bottom settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_bottom',
                        'type'     => 'switch',
                        'title'    => __( 'Footer bottom enable', KT_THEME_LANG ),
                        'default'  => false,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),
                    /* Footer copyright */
                    array(
                        'id'       => 'footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer copyright settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_copyright',
                        'type'     => 'switch',
                        'title'    => __( 'Footer copyright enable', KT_THEME_LANG ),
                        'default'  => true,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),
                    array(
                        'id'       => 'footer_copyright_layout',
                        'type'     => 'select',
                        'title'    => __( 'Footer copyright layout', KT_THEME_LANG ),
                        'subtitle'     => __( 'Select your preferred footer layout.', KT_THEME_LANG ),
                        'options'  => array(
                            'centered' => __('Centered', KT_THEME_LANG),
                            'sides' => __('Sides', KT_THEME_LANG )
                        ),
                        'default'  => 'centered',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'footer_copyright_left',
                        'type'     => 'select',
                        'title'    => __( 'Footer copyright left', KT_THEME_LANG ),
                        'options'  => array(
                            '' => __('Empty', KT_THEME_LANG ),
                            'navigation' => __('Navigation', KT_THEME_LANG ),
                            'socials' => __('Socials', KT_THEME_LANG ),
                            'copyright' => __('Copyright', KT_THEME_LANG ),
                        ),
                        'default'  => ''
                    ),
                    array(
                        'id'       => 'footer_copyright_right',
                        'type'     => 'select',
                        'title'    => __( 'Footer copyright right', KT_THEME_LANG ),
                        'options'  => array(
                            '' => __('Empty', KT_THEME_LANG ),
                            'navigation' => __('Navigation', KT_THEME_LANG ),
                            'socials' => __('Socials', KT_THEME_LANG ),
                            'copyright' => __('Copyright', KT_THEME_LANG ),
                        ),
                        'default'  => 'copyright'
                    ),
                    array(
                         'id'   => 'footer_socials',
                         'type' => 'kt_socials',
                         'title'    => __( 'Select your socials', KT_THEME_LANG ),
                    ),
                    array(
                        'id'       => 'footer_copyright_text',
                        'type'     => 'editor',
                        'title'    => __( 'Footer Copyright Text', KT_THEME_LANG ),
                        'default'  => '<p style="margin-bottom: 38px;"><a href="'.esc_url( home_url( '/' )).'"><img src="'.KT_THEME_IMG.'logo-light.png" alt="Wingman" /></a></p><p style="margin-bottom: 24px;"><img src="'.KT_THEME_IMG.'payment.png" alt="payment" /></p><p style="margin:0;">Copyright © 2015 - <a href="'.esc_url( home_url( '/' )).'">Wing Man</a> - All rights reserved. </p><p style="margin:0;">Powered by <a href="http://wordpress.org" target="_blank">Wordpress</a></p>'
                    ),
                )
            );

            /**
             * Page Loader
             *
             */
            $this->sections[] = array(
                'title' => __('Page Loader', KT_THEME_LANG),
                'desc' => __('Page Loader Options', KT_THEME_LANG),
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'use_page_loader',
                        'type' => 'switch',
                        'title' => __('Use Page Loader?', KT_THEME_LANG),
                        'desc' => __('', KT_THEME_LANG),
                        'default' => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'layout_loader',
                        'type'     => 'image_select',
                        'compiler' => true,
                        'title'    => __( 'Loader layout', KT_THEME_LANG ),
                        'subtitle' => __( 'Please choose loader layout', KT_THEME_LANG ),
                        'options'  => array(
                            'style-1' => array( 'alt' => __( 'Style 1', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'loader/loader_v1.png' ),
                            'style-2' => array( 'alt' => __( 'Style 2', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'loader/loader_v2.png' ),
                            'style-3' => array( 'alt' => __( 'Style 2', KT_THEME_LANG ), 'img' => KT_FW_IMG . 'loader/loader_v3.png' ),
                        ),
                        'default'  => 'style-1',
                    ),
                    array(
                        'id'       => 'background_page_loader',
                        'type'     => 'background',
                        'title'    => __( 'Background Color Page Loader', KT_THEME_LANG ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                        'default'   => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'   => array( '.kt_page_loader' ),
                        'required' => array( 'use_page_loader', 'equals', array( 1 ) ),
                    ),
                    array(
                        'id'       => 'color_first_loader',
                        'type'     => 'color',
                        'title'    => __( 'Color Loader', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false,
                        'required' => array( 'use_page_loader', 'equals', array( 1 ) ),
                    ),
                    array(
                        'id'       => 'color_second_loader',
                        'type'     => 'color',
                        'title'    => __( 'Color Second Loader', KT_THEME_LANG ),
                        'default'  => '#cccccc',
                        'transparent' => false,
                        'required' => array( 'use_page_loader', 'equals', array( 1 ) ),
                    ),
                )
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-cog',
                'title'     => __('Color Preset', KT_THEME_LANG),
                'fields'    => array(
                    array(
                        'id'       => 'kt-presets',
                        'type'     => 'image_select', 
                        'presets'  => true,
                        'title'    => __('Color Preset', KT_THEME_LANG),
                        'subtitle' => __('Select the color you want to use for the theme.', KT_THEME_LANG),
                        'default'  => 0,
                        'options'  => array(
                            'color_default'      => array(
                                'alt'   => 'Default', 
                                'img'   => KT_FW_IMG.'preset/default.jpg', 
                                'presets'   => array(
                                    'color_first_loader' => '#82c14f',
                                    'styling_accent' => '#82c14f',
                                    'navigation_box_border' => array( 'border-color' => '#82c14f'),
                                    'navigation_color_hover' => '#82c14f',
                                    'dropdown_color_hover' => '#82c14f',
                                    'mega_title_color_hover' => '#82c14f',
                                    'mobile_sub_color_hover' => '#82c14f',
                                    'mega_color_hover' => '#82c14f',
                                    'mobile_title_color_hover' => '#82c14f',
                                    'styling_link' => array(
                                        'regular' => '#82c14f',
                                        'hover' => '#689a3f',
                                        'active' => '#689a3f'
                                    ),
                                )
                            ),
                            'color_pale_red'      => array(
                                'alt'   => 'Pale Red', 
                                'img'   => KT_FW_IMG.'preset/pale-red.jpg', 
                                'presets'   => array(
                                    'color_first_loader' => '#ed727c',
                                    'styling_accent' => '#ed727c',
                                    'navigation_box_border' => array( 'border-color' => '#ed727c'),
                                    'navigation_color_hover' => '#ed727c',
                                    'dropdown_color_hover' => '#ed727c',
                                    'mega_title_color_hover' => '#ed727c',
                                    'mobile_sub_color_hover' => '#ed727c',
                                    'mega_color_hover' => '#ed727c',
                                    'mobile_title_color_hover' => '#ed727c',
                                    'styling_link' => array(
                                        'regular' => '#ed727c',
                                        'hover' => '#e74552',
                                        'active' => '#e74552'
                                    ),
                                )
                            ),
                            'color_purple'      => array(
                                'alt'   => 'purple', 
                                'img'   => KT_FW_IMG.'preset/purple.jpg', 
                                'presets'   => array(
                                    'color_first_loader' => '#ec6aec',
                                    'styling_accent' => '#ec6aec',
                                    'navigation_box_border' => array( 'border-color' => '#ec6aec'),
                                    'navigation_color_hover' => '#ec6aec',
                                    'dropdown_color_hover' => '#ec6aec',
                                    'mega_title_color_hover' => '#ec6aec',
                                    'mobile_sub_color_hover' => '#ec6aec',
                                    'mega_color_hover' => '#ec6aec',
                                    'mobile_title_color_hover' => '#ec6aec',
                                    'styling_link' => array(
                                        'regular' => '#ec6aec',
                                        'hover' => '#e63de6',
                                        'active' => '#e63de6'
                                    ),
                                )
                            ),
                        )
                    )
                )
            );

            

            /**
			 *	Styling
			 **/
			$this->sections[] = array(
				'id'			=> 'styling',
				'title'			=> __( 'Styling', KT_THEME_LANG ),
				'desc'			=> '',
				'icon'	=> 'icon-Palette',
            );
            /**
			 *	Styling General
			 **/
            $this->sections[] = array(
				'id'			=> 'styling_general',
				'title'			=> __( 'General', KT_THEME_LANG ),
				'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'styling_accent',
                        'type'     => 'color',
                        'title'    => __( 'Main Color', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false,
                    ),

                    array(
                        'id'       => 'styling_link',
                        'type'     => 'link_color',
                        'title'    => __( 'Links Color', KT_THEME_LANG ),
                        'output'   => array( 'a' ),
                        'default'  => array(
                            'regular' => '#82c14f',
                            'hover' => '#689a3f',
                            'active' => '#689a3f'
                        )
                    ),
                )
            );



            /**
             *	Styling Logo
             **/
            $this->sections[] = array(
                'id'			=> 'styling-logo',
                'title'			=> __( 'Logo', KT_THEME_LANG ),
                'subsection' => true,
                'fields'		=> array(

                    array(
                        'id'             => 'logo_width',
                        'type'           => 'dimensions',
                        'units'          => array( 'px'),
                        'units_extended' => 'true',
                        'title'          => __( 'Logo width', KT_THEME_LANG ),
                        'height'         => false,
                        'default'        => array( 'width'  => 215, 'unit'   => 'px' ),
                        'output'   => array( '.site-branding .site-logo img' ),
                    ),

                    array(
                        'id'       => 'logo_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'output'   => array( '.site-branding' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Logo margin spacing Option', KT_THEME_LANG ),
                        'default'  => array(
                            'margin-top'    => '40px',
                            'margin-right'  => '0',
                            'margin-bottom' => '40px',
                            'margin-left'   => '0'
                        )
                    ),

                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id'             => 'logo_mobile_width',
                        'type'           => 'dimensions',
                        'units'          => array( 'px'),
                        'units_extended' => 'true',
                        'title'          => __( 'Logo mobile width', KT_THEME_LANG ),
                        'height'         => false,
                        'default'        => array(
                            'width'  => 190,
                            'unit'   => 'px'
                        ),
                        'output'   => array( '#header-content-mobile .site-branding .site-logo img' ),
                    ),
                    array(
                        'id'       => 'logo_mobile_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Logo mobile margin spacing Option', KT_THEME_LANG ),
                        'default'  => array(
                            'margin-top'    => '16px',
                            'margin-right'  => '0px',
                            'margin-bottom' => '17px',
                            'margin-left'   => '0px'
                        ),
                        'output'   => array( '#header-content-mobile .site-branding' ),
                    ),

                )
            );
            
            /**
			 *	Styling Header
			 **/
            $this->sections[] = array(
				'id'			=> 'styling_header',
				'title'			=> __( 'Header', KT_THEME_LANG ),
				'subsection' => true,
                'fields'		=> array(



                    /*



                        array(
                            'id'   => 'divide_id',
                            'type' => 'divide'
                        ),

                        array(
                            'id'       => 'header_background',
                            'type'     => 'background',
                            'title'    => __( 'Header background', KT_THEME_LANG ),
                            'subtitle' => __( 'Header with image, color, etc.', KT_THEME_LANG ),
                            'default'   => '',
                            'output'      => array( '.header-background' ),
                        ),
                        array(
                            'id'            => 'header_opacity',
                            'type'          => 'slider',
                            'title'         => __( 'Background opacity', KT_THEME_LANG ),
                            'default'       => 1,
                            'min'           => 0,
                            'step'          => .1,
                            'max'           => 1,
                            'resolution'    => 0.1,
                            'display_value' => 'text'
                        ),
                    */
                )
            );
            /**
             *	Styling Footer
             **/
            $this->sections[] = array(
                'id'			=> 'styling_sticky',
                'title'			=> __( 'Sticky', KT_THEME_LANG ),
                'subsection' => true,
                'fields'		=> array(

                    array(
                        'id'       => 'fixed_header',
                        'type'     => 'button_set',
                        'title'    => __( 'Sticky header', KT_THEME_LANG ),
                        'options'  => array(
                            '1' => __('Disabled', KT_THEME_LANG),
                            '2' => __('Fixed Sticky', KT_THEME_LANG),
                            '3' => __('Slide Down', KT_THEME_LANG),
                        ),
                        'default'  => '3',
                        'desc' => __('Choose your sticky effect.', KT_THEME_LANG)
                    ),
                    array(
                        'id'             => 'logo_sticky_width',
                        'type'           => 'dimensions',
                        'units'          => array( 'px'),
                        'title'          => __( 'Logo width', KT_THEME_LANG ),
                        'height'         => false,
                        'default'        => array(
                            'width'  => '100',
                            'units'  => 'px'
                        ),
                        'output'   => array( '.header-layout2.header-container.is-sticky .site-branding .site-logo img' ),
                    ),

                    array(
                        'id'       => 'logo_sticky_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Logo sticky margin spacing Option', KT_THEME_LANG ),
                        'default'  => array(
                            'margin-top'    => '0',
                            'margin-right'  => '0',
                            'margin-bottom' => '0',
                            'margin-left'   => '0'
                        ),
                        'output'   => array( '.header-layout2.header-container.is-sticky .site-branding'),
                    ),

                    array(
                        'id'             => 'navigation_height_fixed',
                        'type'           => 'dimensions',
                        'units'          => array('px'),
                        'units_extended' => 'true',
                        'title'          => __( 'Main Navigation Sticky Height', KT_THEME_LANG ),
                        'subtitle'          => __( 'Change height of main navigation sticky', KT_THEME_LANG ),
                        'width'         => false,
                        'default'        => array(
                            'height'  => '60',
                            'units'  => 'px'
                        ),

                        'output'   => array(
                            '.header-container.is-sticky #main-navigation > li',
                            '.header-container.header-layout1.is-sticky .nav-container-inner',
                        ),
                    ),
                    array(
                        'id'       => 'header_sticky_background',
                        'type'     => 'background',
                        'title'    => __( 'Header sticky background', KT_THEME_LANG ),
                        'subtitle' => __( 'Header sticky with image, color, etc.', KT_THEME_LANG ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                        'default'   => array(
                            'background-color'      => '#252525',
                        ),
                        'output'      => array( '.header-sticky-background' ),
                    ),

                    array(
                        'id'            => 'header_sticky_opacity',
                        'type'          => 'slider',
                        'title'         => __( 'Sticky Background opacity', KT_THEME_LANG ),
                        'default'       => .8,
                        'min'           => 0,
                        'step'          => .1,
                        'max'           => 1,
                        'resolution'    => 0.1,
                        'display_value' => 'text'
                    ),

                )
            );


            /**
             *	Styling Footer
             **/
            $this->sections[] = array(
                'id'			=> 'styling_footer',
                'title'			=> __( 'Footer', KT_THEME_LANG ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'footer_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_background',
                        'type'     => 'background',
                        'title'    => __( 'Footer Background', KT_THEME_LANG ),
                        'subtitle' => __( 'Footer Background with image, color, etc.', KT_THEME_LANG ),
                        'default'   => array( 'background-color' => '#1e1e1e' ),
                        'output'      => array( '#footer' ),
                    ),

                    array(
                        'id'       => 'footer_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Footer padding', KT_THEME_LANG ),
                        'default'  => array( )
                    ),

                    array(
                        'id'       => 'footer_border',
                        'type'     => 'border',
                        'title'    => __( 'Footer Border', KT_THEME_LANG ),
                        'output'   => array( '#footer' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'bottom'      => false,
                        'default'  => array( )
                    ),

                    // Footer top settings
                    array(
                        'id'       => 'footer_top_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer top settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_top_background',
                        'type'     => 'background',
                        'title'    => __( 'Footer top Background', KT_THEME_LANG ),
                        'subtitle' => __( 'Footer top Background with image, color, etc.', KT_THEME_LANG ),
                        'default'   => array( ),
                        'output'      => array( '#footer-top' ),
                    ),
                    array(
                        'id'       => 'footer_top_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer-top' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Footer top padding', KT_THEME_LANG ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'footer_top_border',
                        'type'     => 'border',
                        'title'    => __( 'Footer top Border', KT_THEME_LANG ),
                        'output'   => array( '#footer-top' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'top'      => false,
                        'default'  => array(

                        )
                    ),
                    // Footer widgets settings
                    array(
                        'id'       => 'footer_widgets_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer widgets settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_widgets_background',
                        'type'     => 'background',
                        'title'    => __( 'Footer widgets Background', KT_THEME_LANG ),
                        'subtitle' => __( 'Footer widgets Background with image, color, etc.', KT_THEME_LANG ),
                        'default'   => array(  ),
                        'output'      => array( '#footer-area' ),
                    ),
                    array(
                        'id'       => 'footer_widgets_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer-area' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Footer widgets padding', KT_THEME_LANG ),
                        'default'  => array( )
                    ),

                    //Footer bottom settings
                    array(
                        'id'       => 'footer_bottom_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer bottom settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_bottom_background',
                        'type'     => 'background',
                        'title'    => __( 'Footer Background', KT_THEME_LANG ),
                        'subtitle' => __( 'Footer Background with image, color, etc.', KT_THEME_LANG ),
                        'default'   => array( ),
                        'output'      => array( '#footer-bottom' ),
                    ),

                    array(
                        'id'       => 'footer_bottom_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Footer bottom padding', KT_THEME_LANG ),
                        'default'  => array( ),
                        'subtitle' => 'Disable if you use instagram background',
                    ),

                    //Footer copyright settings
                    array(
                        'id'       => 'footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Footer copyright settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'footer_copyright_border',
                        'type'     => 'border',
                        'title'    => __( 'Footer Copyright Border', KT_THEME_LANG ),
                        'output'   => array( '#footer-copyright' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'bottom'      => false,
                        'default'  => array( )
                    ),

                    array(
                        'id'       => 'footer_copyright_background',
                        'type'     => 'background',
                        'title'    => __( 'Footer Background', KT_THEME_LANG ),
                        'subtitle' => __( 'Footer Background with image, color, etc.', KT_THEME_LANG ),
                        'default'   => array( ),
                        'output'      => array( '#footer-copyright' ),
                    ),
                    array(
                        'id'       => 'footer_copyright_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer-copyright' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Footer copyright padding', KT_THEME_LANG ),
                        'default'  => array( )
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'footer_socials_style',
                        'type'     => 'select',
                        'title'    => __( 'Footer socials style', KT_THEME_LANG ),
                        'options'  => array(
                            'accent' => __('Accent', KT_THEME_LANG ),
                            'dark'   => __('Dark', KT_THEME_LANG ),
                            'light'  => __('Light', KT_THEME_LANG ),
                            'color'  => __('Color', KT_THEME_LANG ),
                            'custom'  => __('Custom Color', KT_THEME_LANG ),
                        ),
                        'default'  => 'custom'
                    ),
                    array(
                        'id'       => 'custom_color_social',
                        'type'     => 'color',
                        'title'    => __( 'Footer socials Color', KT_THEME_LANG ),
                        'default'  => '#707070',
                        'transparent' => false,
                        'required' => array('footer_socials_style','equals', array( 'custom' ) ),
                    ),
                    array(
                        'id'       => 'footer_socials_background',
                        'type'     => 'select',
                        'title'    => __( 'Footer socials background', KT_THEME_LANG ),
                        'options'  => array(
                            'empty'       => __('None', KT_THEME_LANG ),
                            'rounded'   => __('Circle', KT_THEME_LANG ),
                            'boxed'  => __('Square', KT_THEME_LANG ),
                            'rounded-less'  => __('Rounded', KT_THEME_LANG ),
                            'diamond-square'  => __('Diamond Square', KT_THEME_LANG ),
                            'rounded-outline'  => __('Outline Circle', KT_THEME_LANG ),
                            'boxed-outline'  => __('Outline Square', KT_THEME_LANG ),
                            'rounded-less-outline'  => __('Outline Rounded', KT_THEME_LANG ),
                            'diamond-square-outline'  => __('Outline Diamond Square', KT_THEME_LANG ),
                        ),
                        'subtitle'     => __( 'Select background shape and style for social.', KT_THEME_LANG ),
                        'default'  => 'empty'
                    ),
                    array(
                        'id'       => 'footer_socials_size',
                        'type'     => 'select',
                        'title'    => __( 'Footer socials size', KT_THEME_LANG ),
                        'options'  => array(
                            'small'       => __('Small', KT_THEME_LANG ),
                            'standard'   => __('Standard', KT_THEME_LANG ),
                        ),
                        'default'  => 'small'
                    ),
                    array(
                        'id'       => 'footer_socials_space_between_item',
                        'type'     => 'text',
                        'title'    => __( 'Footer socials space between item', KT_THEME_LANG ),
                        'default'  => '10'
                    ),
                )
            );

            /**
             *	Main Navigation
             **/
            $this->sections[] = array(
                'id'			=> 'styling_navigation',
                'title'			=> __( 'Main Navigation', KT_THEME_LANG ),
                'desc'			=> '',
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'styling_navigation_general',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'General', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'             => 'navigation_height',
                        'type'           => 'dimensions',
                        'units'          => array('px'),
                        'units_extended' => 'true',
                        'title'          => __( 'Main Navigation Height', KT_THEME_LANG ),
                        'subtitle'          => __( 'Change height of main navigation', KT_THEME_LANG ),
                        'width'         => false,
                        'default'        => array(
                            'height'  => '60',
                            'units'  => 'px'
                        ),
                        'output'   => array(
                            '#main-navigation > li',
                            '.header-layout1 .nav-container-inner'
                        ),
                    ),
                    array(
                        'id'       => 'navigation_border',
                        'type'     => 'border',
                        'title'    => __( 'Main Navigation Border', KT_THEME_LANG ),
                        'output'   => array( '.nav-container' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'default'  => array(
                            'border-color' => '#e5e5e5'
                        )
                    ),

                    array(
                        'id'       => 'navigation_background',
                        'type'     => 'background',
                        'title'    => __( 'Background', KT_THEME_LANG ),
                        'subtitle' => __( 'Main Navigation with image, color, etc.', KT_THEME_LANG ),
                        'default'   => array(
                            'background-color'      => '#1e1e1e',
                        ),
                        'output'      => array( '.header-layout1 .nav-container'),
                    ),
                    array(
                        'id'       => 'navigation_box_border',
                        'type'     => 'border',
                        'title'    => __( 'MegaMenu & Dropdown Box Border', KT_THEME_LANG ),
                        'output'   => array(
                            '#main-navigation > li ul.sub-menu-dropdown',
                            '#main-navigation > li > .kt-megamenu-wrapper'
                        ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'default'  => array(
                            'border-color' => '#82c14f'
                        )
                    ),

                    array(
                        'id'       => 'navigation_box_background',
                        'type'     => 'background',
                        'title'    => __( 'MegaMenu & Dropdown Box background', KT_THEME_LANG ),
                        'default'   => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'      => array(
                            '#main-navigation > li ul.sub-menu-dropdown',
                            '#main-navigation > li > .kt-megamenu-wrapper'
                        ),
                        'transparent'           => false,
                    ),
                    array(
                        'id'       => 'styling_navigation_general',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Top Level', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'            => 'navigation_space',
                        'type'          => 'slider',
                        'title'         => __( 'Top Level space', KT_THEME_LANG ),
                        'default'       => 30,
                        'min'           => 0,
                        'step'          => 1,
                        'max'           => 50,
                        'resolution'    => 1,
                        'display_value' => 'text',
                        'subtitle' => __( 'Margin left between top level.', KT_THEME_LANG ),
                    ),

                    array(
                        'id'       => 'navigation_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-navigation > li > a'
                        ),
                        'title'    => __( 'Top Level Color', KT_THEME_LANG ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'navigation_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-navigation > li > a:hover',
                            '#main-navigation > li > a:focus',
                            '#main-navigation > li.current-menu-item > a',
                            '#main-navigation > li.current-menu-parent > a',
                            '#main-navigation > li.hovered > a',
                        ),
                        'title'    => __( 'Top Level hover Color', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false
                    ),


                    array(
                        'id'       => 'styling_navigation_dropdown',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Drop down', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'             => 'navigation_dropdown',
                        'type'           => 'dimensions',
                        'units'          => array('px'),
                        'units_extended' => 'true',
                        'title'          => __( 'Dropdown width', KT_THEME_LANG ),
                        'subtitle'          => __( 'Change width of Dropdown', KT_THEME_LANG ),
                        'height'         => false,
                        'default'        => array( 'width'  => 300, 'height' => 100 ),
                        'output'   => array( '#main-navigation > li ul.sub-menu-dropdown'),
                    ),
                    array(
                        'id'       => 'dropdown_background',
                        'type'     => 'background',
                        'title'    => __( 'Dropdown Background Color', KT_THEME_LANG ),
                        'default'  => array(
                            'background-color'      => '',
                        ),
                        'output'   => array(
                            '#main-navigation > li ul.sub-menu-dropdown > li > a'
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => true,
                    ),

                    array(
                        'id'       => 'dropdown_background_hover',
                        'type'     => 'background',
                        'title'    => __( 'Dropdown Background Hover Color', KT_THEME_LANG ),
                        'default'  => array(
                            'background-color'      => '',
                        ),
                        'output'   => array(
                            '#main-navigation > li ul.sub-menu-dropdown > li.current-menu-item > a',
                            '#main-navigation > li ul.sub-menu-dropdown > li.current-menu-parent > a',
                            '#main-navigation > li ul.sub-menu-dropdown > li.hovered > a',
                            '#main-navigation > li ul.sub-menu-dropdown > li > a:hover',
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => true,
                    ),
                    array(
                        'id'       => 'dropdown_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-nav-tool .kt-wpml-languages ul li > a',
                            '#main-navigation > li ul.sub-menu-dropdown > li > a',
                        ),
                        'title'    => __( 'Dropdown Text Color', KT_THEME_LANG ),
                        'default'  => '#707070',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'dropdown_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-navigation > li ul.sub-menu-dropdown > li.current-menu-item > a',
                            '#main-navigation > li ul.sub-menu-dropdown > li.current-menu-parent > a',
                            '#main-nav-tool .kt-wpml-languages ul li > a:hover',
                            '#main-navigation > li ul.sub-menu-dropdown > li:hover > a',
                            '#main-navigation > li ul.sub-menu-dropdown > li > a:hover',
                        ),
                        'title'    => __( 'Dropdown Text Hover Color', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'styling_navigation_mega',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Mega', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'mega_title_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a',
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > span',
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li .widget-title',
                        ),
                        'title'    => __( 'MegaMenu Title color', KT_THEME_LANG ),
                        'default'  => '#252525',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_title_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-navigation > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > a:hover',
                        ),
                        'title'    => __( 'MegaMenu Title Hover Color', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li ul.sub-menu-megamenu a'
                        ),
                        'title'    => __( 'MegaMenu Text color', KT_THEME_LANG ),
                        'default'  => '#707070',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'mega_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li ul.sub-menu-megamenu  > li.current-menu-item a:hover',
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li ul.sub-menu-megamenu a:hover',
                        ),
                        'title'    => __( 'MegaMenu Text Hover color', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'typography_heading',
                        'type'     => 'raw',
                        'content'  => '<div style="height:150px"></div>',
                        'full_width' => true
                    ),
                )
            );

            /**
             *	Mobile Navigation
             **/
            $this->sections[] = array(
                'id'			=> 'styling_mobile_menu',
                'title'			=> __( 'Mobile Menu', KT_THEME_LANG ),
                'desc'			=> '',
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'mobile_menu_background',
                        'type'     => 'background',
                        'title'    => __( 'Background', KT_THEME_LANG ),
                        'default'   => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'      => array( '#mobile-nav-holder'),
                        'transparent'           => false,
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),

                    array(
                        'id'       => 'mobile_menu_color',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li > a'
                        ),
                        'title'    => __( 'Top Level Color', KT_THEME_LANG ),
                        'default'  => '#282828',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mobile_menu_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li:hover > a',
                            'ul.navigation-mobile > li > a:hover'
                        ),
                        'title'    => __( 'Top Level hover Color', KT_THEME_LANG ),
                        'default'  => '#282828',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mobile_menu_background',
                        'type'     => 'background',
                        'title'    => __( 'Top Level Background Color', KT_THEME_LANG ),
                        'default'  => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'   => array(
                            'ul.navigation-mobile > li > a'
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                    ),

                    array(
                        'id'       => 'mobile_menu_background_hover',
                        'type'     => 'background',
                        'title'    => __( 'Top Level Hover Color', KT_THEME_LANG ),
                        'default'  => array(
                            'background-color'      => '#F5F5F5',
                        ),
                        'output'   => array(
                            'ul.navigation-mobile > li:hover > a',
                            'ul.navigation-mobile > li > a:hover',
                            //'ul.navigation-mobile > li.current-menu-item > a',
                            //'ul.navigation-mobile > li.active-menu-item > a',
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'mobile_sub_color',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li .sub-menu-dropdown > li > a',
                            'ul.navigation-mobile > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > .sub-menu-megamenu > li > a',
                        ),
                        'title'    => __( 'Text color', KT_THEME_LANG ),
                        'default'  => '#282828',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'mobile_sub_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li .sub-menu-dropdown > li > a:hover',
                            'ul.navigation-mobile > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > .sub-menu-megamenu > li > a:hover',
                        ),
                        'title'    => __( 'Text Hover color', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'mobile_title_color',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > a',
                            'ul.navigation-mobile > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > span',
                            'ul.navigation-mobile > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li .widget-title',
                        ),
                        'title'    => __( 'MegaMenu Title color', KT_THEME_LANG ),
                        'default'  => '#282828',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mobile_title_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > a:hover',
                        ),
                        'title'    => __( 'MegaMenu Title Hover Color', KT_THEME_LANG ),
                        'default'  => '#82c14f',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'typography_heading',
                        'type'     => 'raw',
                        'content'  => '<div style="height:150px"></div>',
                        'full_width' => true
                    ),
                )
            );
            /**
			 *	Typography
			 **/
			$this->sections[] = array(
				'id'			=> 'typography',
				'title'			=> __( 'Typography', KT_THEME_LANG ),
				'desc'			=> '',
				'icon'	=> 'icon-Font-Name',
            );
            
            /**
			 *	Typography General
			 **/
			$this->sections[] = array(
				'id'			=> 'typography_general',
				'title'			=> __( 'General', KT_THEME_LANG ),
				'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'typography_body',
                        'type'     => 'typography',
                        'title'    => __( 'Body Font', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the body font properties.', KT_THEME_LANG ),
                        'text-align' => false,
                        'letter-spacing'  => true,
                        'output'      => array(
                            'body',
                            '.tooltip',
                            '.woocommerce ul.shop-products h3',
                            '.woocommerce ul.shop-products h3',
                            '.woocommerce div.product .product_title',
                            '.woocommerce table.cart tbody td.product-name a',
                            '.woocommerce table.wishlist_table tbody td.product-add-to-cart a'
                        ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'typography_pragraph',
                        'type'     => 'typography',
                        'title'    => __( 'Pragraph', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the pragraph font properties.', KT_THEME_LANG ),
                        'output'   => array( 'p' ),
                        'default'  => array( ),
                        'color'    => false,
                        'text-align' => false,
                    ),

                    array(
                        'id'       => 'typography_blockquote',
                        'type'     => 'typography',
                        'title'    => __( 'Blockquote', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the blockquote font properties.', KT_THEME_LANG ),
                        'output'   => array( 'blockquote' ),
                        'default'  => array( ),
                        'color'    => false,
                        'text-align' => false,
                    ),
                    /*
                    array(
                        'id'       => 'typography_button',
                        'type'     => 'typography',
                        'title'    => __( 'Button', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the button font properties.', KT_THEME_LANG ),
                        'output'   => array(
                            '.button',
                            '.wpcf7-submit',
                            '.btn',
                            '.woocommerce #respond input#submit',
                            '.woocommerce a.button',
                            '.woocommerce button.button',
                            '.woocommerce input.button',
                            '.woocommerce #respond input#submit.alt',
                            '.woocommerce a.button.alt',
                            '.woocommerce button.button.alt',
                            '.woocommerce input.button.alt',
                            '.vc_general.vc_btn3',
                            '.kt-button',
                            '.readmore-link',
                            '.readmore-link-white'
                        ),
                        'default'  => array(
                            'font-family'     => 'Poppins',
                            'font-size'       => '14px',
                            'text-transform'  => 'uppercase',
                        ),
                        'color'    => false,
                        'text-align'    => false,
                        'font-size'    => false,
                        'text-transform' => true,
                        'letter-spacing'  => true,
                        'font-weight' => false
                    ),
                    */
                    array(
                        'id'       => 'typography_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Typography Heading settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_heading1',
                        'type'     => 'typography',
                        'title'    => __( 'Heading 1', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the heading 1 font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'text-align' => false,
                        'output'      => array( 'h1', '.h1' ),
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'text-transform'  => 'uppercase',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading2',
                        'type'     => 'typography',
                        'title'    => __( 'Heading 2', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the heading 2 font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h2', '.h2' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading3',
                        'type'     => 'typography',
                        'title'    => __( 'Heading 3', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the heading 3 font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h3', '.h3' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading4',
                        'type'     => 'typography',
                        'title'    => __( 'Heading 4', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the heading 4 font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h4', '.h4' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading5',
                        'type'     => 'typography',
                        'title'    => __( 'Heading 5', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the heading 5 font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h5', '.h5' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading6',
                        'type'     => 'typography',
                        'title'    => __( 'Heading 6', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the heading 6 font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h6', '.h6' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                )
            );
            /**
			 *	Typography header
			 **/
			$this->sections[] = array(
				'id'			=> 'typography_header',
				'title'			=> __( 'Header', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'typography_header_content',
                        'type'     => 'typography',
                        'title'    => __( 'Header', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the header title font properties.', KT_THEME_LANG ),
                        'google'   => true,
                        'text-align' => false,
                        'output'      => array( '#header' )
                    )
                )
            );
            
            /**
			 *	Typography footer
			 **/
			$this->sections[] = array(
				'id'			=> 'typography_footer',
				'title'			=> __( 'Footer', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'typography_footer_top_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Typography Footer top settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_top',
                        'type'     => 'typography',
                        'title'    => __( 'Footer top', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the footer top font properties.', KT_THEME_LANG ),
                        'google'   => true,
                        'text-align'      => false,
                        'output'      => array( '#footer-top' ),
                        'default'  => array(
                            'color'       => '',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Typography Footer widgets settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_widgets',
                        'type'     => 'typography',
                        'title'    => __( 'Footer widgets', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the footer widgets font properties.', KT_THEME_LANG ),
                        'google'   => true,
                        'text-align'      => false,
                        'output'      => array( '#footer-area' ),
                        'default'  => array(
                            'color'       => '#707070',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_title',
                        'type'     => 'typography',
                        'title'    => __( 'Footer widgets title', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the footer widgets title font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-align'      => true,
                        'text-transform' => true,
                        'output'      => array( '#footer-area h3.widget-title' ),
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_link',
                        'type'     => 'link_color',
                        'title'    => __( 'Footer widgets Links Color', KT_THEME_LANG ),
                        'output'      => array( '#footer-area a' ),
                        'default'  => array(
                            'regular' => '#707070',
                            'hover'   => '#ffffff',
                            'active'  => '#ffffff'
                        )
                    ),

                    array(
                        'id'       => 'typography_footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Typography Footer copyright settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_copyright_link',
                        'type'     => 'link_color',
                        'title'    => __( 'Footer Copyright Links Color', KT_THEME_LANG ),
                        'output'      => array( '#footer-copyright a' ),
                        'default'  => array(
                            'regular' => '#707070',
                            'hover'   => '#ffffff',
                            'active'  => '#ffffff'
                        )
                    ),
                    array(
                        'id'       => 'typography_footer_copyright',
                        'type'     => 'typography',
                        'title'    => __( 'Footer copyright', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the footer font properties.', KT_THEME_LANG ),
                        'text-align'      => false,
                        'output'      => array( '#footer-copyright' ),
                        'default'  => array(
                            'color'       => '',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),

                )
            );
            /**
			 *	Typography sidebar
			 **/
			$this->sections[] = array(
				'id'			=> 'typography_sidebar',
				'title'			=> __( 'Sidebar', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'typography_sidebar',
                        'type'     => 'typography',
                        'title'    => __( 'Sidebar title', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the sidebar title font properties.', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'output'      => array(
                            '.sidebar .widget-title',
                            '.wpb_widgetised_column .widget-title'
                        ),
                        'default'  => array(
                            'text-transform' => 'uppercase',
                        ),
                    ),
                    array(
                        'id'       => 'typography_sidebar_content',
                        'type'     => 'typography',
                        'title'    => __( 'Sidebar text', KT_THEME_LANG ),
                        'subtitle' => __( 'Specify the sidebar title font properties.', KT_THEME_LANG ),
                        'text-algin' => true,
                        'output'      => array( '.sidebar', '.wpb_widgetised_column' ),
                        'default'  => array(
                        
                        ),
                    ),
                )
            );
            
            /**
			 *	Typography Navigation
			 **/

			$this->sections[] = array(
				'id'			=> 'typography_navigation',
				'title'			=> __( 'Main Navigation', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'typography-navigation_top',
                        'type'     => 'typography',
                        'title'    => __( 'Top Menu Level', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array( '#main-navigation > li > a' ),
                        'default'  => array(
                            'text-transform' => 'uppercase',
                            'font-weight'    => '600'
                        ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'typography_navigation_dropdown',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Dropdown menu', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_navigation_second',
                        'type'     => 'typography',
                        'title'    => __( 'Second Menu Level', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '#main-navigation > li ul.sub-menu-dropdown li > a'
                        ),
                        'default'  => array(

                        ),
                    ),
                    array(
                        'id'       => 'typography_navigation_mega',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Mega menu', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_navigation_heading',
                        'type'     => 'typography',
                        'title'    => __( 'Heading title', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array( 
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a',
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > span',
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li .widget-title'
                        ),
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'text-transform' => 'uppercase',
                            'font-weight'  => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_navigation_mega_link',
                        'type'     => 'typography',
                        'title'    => __( 'Mega menu', KT_THEME_LANG ),
                        'google'   => true,
                        'text-align'      => false,
                        'color'           => false,
                        'text-transform' => true,
                        'line-height'     => false,
                        'output'      => array(
                            '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li ul.sub-menu-megamenu a'
                        ),
                        'default'  => array( ),
                    )

                )
            );


            /**
             *	Typography mobile Navigation
             **/

            $this->sections[] = array(
                'id'			=> 'typography_mobile_navigation',
                'title'			=> __( 'Mobile Navigation', KT_THEME_LANG ),
                'desc'			=> '',
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'typography_mobile_navigation_top',
                        'type'     => 'typography',
                        'title'    => __( 'Top Menu Level', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array( 'ul.navigation-mobile > li > a' ),
                        'default'  => array(
                            'text-transform' => 'uppercase',
                        ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'typography_mobile_navigation_second',
                        'type'     => 'typography',
                        'title'    => __( 'Sub Menu Level', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '.main-nav-mobile > ul > li ul.sub-menu-dropdown li a',
                            '.main-nav-mobile > ul > li ul.sub-menu-megamenu li a'
                        ),
                    ),
                    array(
                        'id'       => 'typography_mobile_navigation_heading',
                        'type'     => 'typography',
                        'title'    => __( 'Heading title', KT_THEME_LANG ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '.main-nav-mobile > ul > li div.kt-megamenu-wrapper > ul > li > a',
                            '.main-nav-mobile > ul > li div.kt-megamenu-wrapper > ul > li > span',
                            '.main-nav-mobile > ul > li div.kt-megamenu-wrapper > ul > li .widget-title'
                        ),
                        'default'  => array(
                            'text-transform' => 'uppercase',
                            'font-weight'  => '700'
                        ),
                    ),
                )
            );



            /**
             *	Sidebar
             **/
            $this->sections[] = array(
                'id'			=> 'sidebar_section',
                'title'			=> __( 'Sidebar Widgets', KT_THEME_LANG ),
                'desc'			=> '',
                'icon'          => 'icon-Sidebar-Window',
                'fields'		=> array(

                    array(
                        'id'          => 'custom_sidebars',
                        'type'        => 'slides',
                        'title'       => __('Slides Options', KT_THEME_LANG ),
                        'subtitle'    => __('Unlimited sidebar with drag and drop sortings.', KT_THEME_LANG ),
                        'desc'        => '',
                        'class'       => 'slider-no-image-preview',
                        'content_title' =>'Sidebar',
                        'show' => array(
                            'title' => true,
                            'description' => true,
                            'url' => false,
                        ),
                        'placeholder' => array(
                            'title'           => __('Sidebar title', KT_THEME_LANG ),
                            'description'     => __('Sidebar Description', KT_THEME_LANG ),
                        ),
                    ),
                )
            );

            /**
             *	Page header
             **/
            $this->sections[] = array(
                'id'			=> 'page_header_section',
                'title'			=> __( 'Page header', KT_THEME_LANG ),
                'desc'			=> '',
                'icon'          => 'icon-Add-SpaceBeforeParagraph',
                'fields'		=> array(

                    array(
                        'id'       => 'title_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Page header settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'title_layout',
                        'type'     => 'select',
                        'title'    => __( 'Page header layout', KT_THEME_LANG ),
                        'subtitle'     => __( 'Select your preferred Page header layout.', KT_THEME_LANG ),
                        'options'  => array(
                            'sides' => __('Sides', KT_THEME_LANG),
                            'centered' => __('Centered', KT_THEME_LANG ),
                        ),
                        'default'  => 'centered',
                        'clear' => false
                    ),

                    array(
                        'id'       => 'title_align',
                        'type'     => 'select',
                        'title'    => __( 'Page header align', KT_THEME_LANG ),
                        'subtitle'     => __( 'Please select page header align', KT_THEME_LANG ),
                        'options'  => array(
                            'left' => __('Left', KT_THEME_LANG ),
                            'center' => __('Center', KT_THEME_LANG),
                            'right' => __('Right', KT_THEME_LANG)
                        ),
                        'default'  => 'center',
                        'clear' => false,
                        'desc' => __('Align don\'t support for layout Sides', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'title_breadcrumbs',
                        'type'     => 'switch',
                        'title'    => __( 'Show breadcrumbs', KT_THEME_LANG ),
                        'default'  => true,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),
                    array(
                        'id'       => 'title_breadcrumbs_mobile',
                        'type'     => 'switch',
                        'title'    => __( 'Breadcrumbs on Mobile Devices', KT_THEME_LANG ),
                        'default'  => false,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),
                    array(
                        'id'       => 'title_separator',
                        'type'     => 'switch',
                        'title'    => __( 'Separator bettwen title and subtitle', KT_THEME_LANG ),
                        'default'  => true,
                        'on'		=> __( 'Enabled', KT_THEME_LANG ),
                        'off'		=> __( 'Disabled', KT_THEME_LANG ),
                    ),
                    array(
                        'id'       => 'title_separator_color',
                        'type'     => 'background',
                        'title'    => __( 'Separator Color', KT_THEME_LANG ),
                        'default'  => '',
                        'transparent' => false,
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'output'   => array( '.page-header .page-header-separator' ),
                    ),


                    array(
                        'id'       => 'title_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '.page-header' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => __( 'Title padding', KT_THEME_LANG ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'title_background',
                        'type'     => 'background',
                        'title'    => __( 'Background', KT_THEME_LANG ),
                        'subtitle' => __( 'Page header with image, color, etc.', KT_THEME_LANG ),
                        'output'      => array( '.page-header' )
                    ),

                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),

                    array(
                        'id'       => 'title_typography',
                        'type'     => 'typography',
                        'title'    => __( 'Typography title', KT_THEME_LANG ),
                        'google'   => true,
                        'text-align'      => false,
                        'line-height'     => false,
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'output'      => array( '.page-header h1.page-header-title' ),
                        'default'  => array(
                            'font-family'     => 'Josefin Slab',
                            'text-transform' => 'uppercase',
                            'font-weight' => '700'
                        ),
                    ),
                    array(
                        'id'       => 'title_typography_subtitle',
                        'type'     => 'typography',
                        'title'    => __( 'Typography sub title', KT_THEME_LANG ),
                        'google'   => true,
                        'text-align'      => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array( '.page-header .page-header-subtitle' )
                    ),
                    array(
                        'id'       => 'title_typography_breadcrumbs',
                        'type'     => 'typography',
                        'title'    => __( 'Typography breadcrumbs', KT_THEME_LANG ),
                        'google'   => true,
                        'text-align'      => false,
                        'line-height'     => false,
                        'output'      => array( '.page-header .breadcrumbs', '.page-header .breadcrumbs a' )
                    ),
                )
            );


            /**
             * General page
             *
             */
            $this->sections[] = array(
                'title' => __('Page', KT_THEME_LANG),
                'desc' => __('General Page Options', KT_THEME_LANG),
                'icon' => 'icon-Code-Window',
                'fields' => array(
                    array(
                        'id' => 'show_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),

                    array(
                        'id'       => 'sidebar',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar configuration', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose page layout", KT_THEME_LANG ),
                        'options'  => array(
                            'full' => __('No sidebars', KT_THEME_LANG),
                            'left' => __('Left Sidebar', KT_THEME_LANG),
                            'right' => __('Right Layout', KT_THEME_LANG)
                        ),
                        'default'  => 'full',
                        'clear' => false,
                    ),

                    array(
                        'id'       => 'sidebar_left',
                        'type' => 'select',
                        'title'    => __( 'Sidebar left area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose default layout", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('sidebar','equals','left')
                        //'clear' => false
                    ),

                    array(
                        'id'       => 'sidebar_right',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar right area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose page layout", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('sidebar','equals','right')
                        //'clear' => false
                    ),
                    array(
                        'id' => 'show_page_comment',
                        'type' => 'switch',
                        'title' => __('Show comments on page ?', KT_THEME_LANG),
                        'desc' => __('Show or hide the readmore button.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                )
            );

            
            /**
             * General Blog
             *
             */
            $this->sections[] = array(
                'title' => __('Blog', KT_THEME_LANG),
                'icon' => 'icon-Pen-2',
                'desc' => __('General Blog Options', KT_THEME_LANG)
            );


            /**
             *  Archive settings
             **/
            $this->sections[] = array(
                'id'            => 'archive_section',
                'title'         => __( 'Archive', KT_THEME_LANG ),
                'desc'          => 'Archive post settings',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'archive_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Archive post general', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'archive_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'archive_sidebar',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar configuration', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose archive page ", KT_THEME_LANG ),
                        'options'  => array(
                            'full' => __('No sidebars', KT_THEME_LANG),
                            'left' => __('Left Sidebar', KT_THEME_LANG),
                            'right' => __('Right Layout', KT_THEME_LANG)
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'archive_sidebar_left',
                        'type' => 'select',
                        'title'    => __( 'Sidebar left area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('archive_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'archive_sidebar_right',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar right area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('archive_sidebar','equals','right'),
                        'clear' => false
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'archive_loop_style',
                        'type' => 'select',
                        'title' => __('Loop Style', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            'classic' => __( 'Standard', 'js_composer' ),
                            'grid' => __( 'Grid', 'js_composer' ),
                            'list' => __( 'List', 'js_composer' ),
                            'masonry' => __( 'Masonry', 'js_composer' ),
                            'zigzag' => __( 'Zig Zag', 'js_composer' ),
                        ),
                        'default' => 'list'
                    ),
                    array(
                        'id' => 'archive_columns',
                        'type' => 'select',
                        'title' => __('Columns on desktop', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            '1' => __( '1 column', 'js_composer' ) ,
                            '2' => __( '2 columns', 'js_composer' ) ,
                            '3' => __( '3 columns', 'js_composer' ) ,
                            '4' => __( '4 columns', 'js_composer' ) ,
                            '6' => __( '6 columns', 'js_composer' ) ,
                        ),
                        'default' => '2',
                        'required' => array('archive_loop_style','equals', array( 'grid', 'masonry' ) ),
                    ),
                    array(
                        'id' => 'archive_columns_tablet',
                        'type' => 'select',
                        'title' => __('Columns on Tablet', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            '1' => __( '1 column', 'js_composer' ) ,
                            '2' => __( '2 columns', 'js_composer' ) ,
                            '3' => __( '3 columns', 'js_composer' ) ,
                            '4' => __( '4 columns', 'js_composer' ) ,
                            '6' => __( '6 columns', 'js_composer' ) ,
                        ),
                        'default' => '2',
                        'required' => array('archive_loop_style','equals', array( 'grid', 'masonry' ) ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'archive_align',
                        'type' => 'select',
                        'title' => __('Text align', KT_THEME_LANG),
                        'desc' => __('Not working for archive style Standard', KT_THEME_LANG),
                        'options' => array(
                            'left' => __( 'Left', KT_THEME_LANG ) ,
                            'center' => __( 'Center', KT_THEME_LANG ) ,
                        ),
                        'default' => 'left'
                    ),
                    array(
                        'id' => 'archive_readmore',
                        'type' => 'select',
                        'title' => __('Readmore button ', KT_THEME_LANG),
                        'desc' => __('Select button style.', KT_THEME_LANG),
                        "default" => 'link',
                        'options' => array(
                            '' => __('None', KT_THEME_LANG),
                            'link' => __( 'Link', 'js_composer' ),
                        ),
                    ),

                    array(
                        'id' => 'archive_thumbnail_type',
                        'type' => 'select',
                        'title' => __('Thumbnail type', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            'format' => __( 'Post format', KT_THEME_LANG ) ,
                            'image' => __( 'Featured Image', KT_THEME_LANG ) ,
                        ),
                        'default' => 'image'
                    ),
                    array(
                        'id' => 'archive_excerpt',
                        'type' => 'switch',
                        'title' => __('Show Excerpt? ', KT_THEME_LANG),
                        'desc' => __('Show or hide the excerpt.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'archive_excerpt_length',
                        'type' => 'text',
                        'title' => __('Excerpt Length', KT_THEME_LANG),
                        'desc' => __("Insert the number of words you want to show in the post excerpts.", KT_THEME_LANG),
                        'default' => '30',
                    ),
                    array(
                        'id' => 'archive_pagination',
                        'type' => 'select',
                        'title' => __('Pagination Type', KT_THEME_LANG),
                        'desc' => __('Select the pagination type.', KT_THEME_LANG),
                        'options' => array(
                            'classic' => __( 'Standard pagination', KT_THEME_LANG ),
                            'loadmore' => __( 'Load More button', KT_THEME_LANG ),
                            'normal' => __( 'Normal pagination', KT_THEME_LANG ),
                        ),
                        'default' => 'classic'
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'archive_meta',
                        'type' => 'switch',
                        'title' => __('Show Meta? ', KT_THEME_LANG),
                        'desc' => __('Show or hide the meta.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),

                    array(
                        'id' => 'archive_meta_author',
                        'type' => 'switch',
                        'title' => __('Post Meta Author', KT_THEME_LANG),
                        'desc' => __('Show meta author in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('archive_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'archive_meta_comments',
                        'type' => 'switch',
                        'title' => __('Post Meta Comments', KT_THEME_LANG),
                        'desc' => __('Show post meta comments in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('archive_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'archive_meta_categories',
                        'type' => 'switch',
                        'title' => __('Post Meta Categories', KT_THEME_LANG),
                        'desc' => __('Show post meta categories in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('archive_meta','equals', array( 1 ) ),
                    ),

                    array(
                        'id' => 'archive_meta_date',
                        'type' => 'switch',
                        'title' => __('Post Meta Date', KT_THEME_LANG),
                        'desc' => __('Show meta date in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('archive_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'archive_date_format',
                        'type' => 'select',
                        'title' => __('Date format', KT_THEME_LANG),
                        'desc' => __('Select the date formart.', KT_THEME_LANG),
                        'options' => array(
                            'd F Y' => __( '05 December 2014', 'js_composer' ) ,
                            'F jS Y' => __( 'December 13th 2014', 'js_composer' ) ,
                            'jS F Y' => __( '13th December 2014', 'js_composer' ) ,
                            'd M Y' => __( '05 Dec 2014', 'js_composer' ) ,
                            'M d Y' => __( 'Dec 05 2014', 'js_composer' ) ,
                            'time' => __( 'Time ago', 'js_composer' ) ,
                        ),
                        'default' => 'd F Y',
                        'required' => array('archive_meta','equals', array( 1 ) ),
                    ),
                    
                    array(
                        'id' => 'archive_like_post',
                        'type' => 'switch',
                        'title' => __('Like Post', KT_THEME_LANG),
                        'desc' => __('Show like post in blog posts.', KT_THEME_LANG),
                        'default' => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('archive_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'archive_view_number',
                        'type' => 'switch',
                        'title' => __('Show View Number', KT_THEME_LANG),
                        'desc' => __('Show view number in blog posts.', KT_THEME_LANG),
                        'default' => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('archive_meta','equals', array( 1 ) ),
                    ),
                )
            );


            /**
             *  Author settings
             **/
            $this->sections[] = array(
                'id'            => 'author_section',
                'title'         => __( 'Author', KT_THEME_LANG ),
                'desc'          => 'Author post settings',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'author_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Author post general', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'author_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'author_sidebar',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar configuration', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose archive page ", KT_THEME_LANG ),
                        'options'  => array(
                            'full' => __('No sidebars', KT_THEME_LANG),
                            'left' => __('Left Sidebar', KT_THEME_LANG),
                            'right' => __('Right Layout', KT_THEME_LANG)
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'author_sidebar_left',
                        'type' => 'select',
                        'title'    => __( 'Sidebar left area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'blog-widget-area',
                        'required' => array('author_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'author_sidebar_right',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar right area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'blog-widget-area',
                        'required' => array('author_sidebar','equals','right'),
                        'clear' => false
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'author_loop_style',
                        'type' => 'select',
                        'title' => __('Loop Style', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            'classic' => __( 'Standard', 'js_composer' ),
                            'grid' => __( 'Grid', 'js_composer' ),
                            'list' => __( 'List', 'js_composer' ),
                            'masonry' => __( 'Masonry', 'js_composer' ),
                            'zigzag' => __( 'Zig Zag', 'js_composer' ),
                        ),
                        'default' => 'list'
                    ),
                    array(
                        'id' => 'author_columns',
                        'type' => 'select',
                        'title' => __('Columns on desktop', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            '1' => __( '1 column', 'js_composer' ) ,
                            '2' => __( '2 columns', 'js_composer' ) ,
                            '3' => __( '3 columns', 'js_composer' ) ,
                            '4' => __( '4 columns', 'js_composer' ) ,
                            '6' => __( '6 columns', 'js_composer' ) ,
                        ),
                        'default' => '2',
                        'required' => array('author_loop_style','equals', array( 'grid', 'masonry' ) ),
                    ),
                    array(
                        'id' => 'author_columns_tablet',
                        'type' => 'select',
                        'title' => __('Columns on Tablet', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            '1' => __( '1 column', 'js_composer' ) ,
                            '2' => __( '2 columns', 'js_composer' ) ,
                            '3' => __( '3 columns', 'js_composer' ) ,
                            '4' => __( '4 columns', 'js_composer' ) ,
                            '6' => __( '6 columns', 'js_composer' ) ,
                        ),
                        'default' => '2',
                        'required' => array('author_loop_style','equals', array( 'grid', 'masonry' ) ),
                    ),
                    array(
                        'id' => 'author_sharebox',
                        'type' => 'switch',
                        'title' => __('Share box', KT_THEME_LANG),
                        'desc' => __('Show or hide share box.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('author_loop_style','equals', array( 'classic' ) ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'author_align',
                        'type' => 'select',
                        'title' => __('Text align', KT_THEME_LANG),
                        'desc' => __('Not working for archive style Standard', KT_THEME_LANG),
                        'options' => array(
                            'left' => __( 'Left', KT_THEME_LANG ) ,
                            'center' => __( 'Center', KT_THEME_LANG ) ,
                        ),
                        'default' => 'left'
                    ),
                    array(
                        'id' => 'author_readmore',
                        'type' => 'select',
                        'title' => __('Readmore button ', KT_THEME_LANG),
                        'desc' => __('Select button style.', KT_THEME_LANG),
                        "default" => 'link',
                        'options' => array(
                            '' => __('None', KT_THEME_LANG),
                            'link' => __( 'Link', 'js_composer' ),
                        ),
                    ),

                    array(
                        'id' => 'author_thumbnail_type',
                        'type' => 'select',
                        'title' => __('Thumbnail type', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            'format' => __( 'Post format', KT_THEME_LANG ) ,
                            'image' => __( 'Featured Image', KT_THEME_LANG ) ,
                        ),
                        'default' => 'image'
                    ),
                    array(
                        'id' => 'author_excerpt',
                        'type' => 'switch',
                        'title' => __('Show Excerpt? ', KT_THEME_LANG),
                        'desc' => __('Show or hide the excerpt.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'author_excerpt_length',
                        'type' => 'text',
                        'title' => __('Excerpt Length', KT_THEME_LANG),
                        'desc' => __("Insert the number of words you want to show in the post excerpts.", KT_THEME_LANG),
                        'default' => '30',
                    ),
                    array(
                        'id' => 'author_pagination',
                        'type' => 'select',
                        'title' => __('Pagination Type', KT_THEME_LANG),
                        'desc' => __('Select the pagination type.', KT_THEME_LANG),
                        'options' => array(
                            'classic' => __( 'Standard pagination', KT_THEME_LANG ),
                            'loadmore' => __( 'Load More button', KT_THEME_LANG ),
                            'normal' => __( 'Normal pagination', KT_THEME_LANG ),
                        ),
                        'default' => 'classic'
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'author_meta',
                        'type' => 'switch',
                        'title' => __('Show Meta? ', KT_THEME_LANG),
                        'desc' => __('Show or hide the meta.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),

                    array(
                        'id' => 'author_meta_author',
                        'type' => 'switch',
                        'title' => __('Post Meta Author', KT_THEME_LANG),
                        'desc' => __('Show meta author in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('author_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'author_meta_comments',
                        'type' => 'switch',
                        'title' => __('Post Meta Comments', KT_THEME_LANG),
                        'desc' => __('Show post meta comments in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('author_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'author_meta_categories',
                        'type' => 'switch',
                        'title' => __('Post Meta Categories', KT_THEME_LANG),
                        'desc' => __('Show post meta categories in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('author_meta','equals', array( 1 ) ),
                    ),

                    array(
                        'id' => 'author_meta_date',
                        'type' => 'switch',
                        'title' => __('Post Meta Date', KT_THEME_LANG),
                        'desc' => __('Show meta date in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('author_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'author_date_format',
                        'type' => 'select',
                        'title' => __('Date format', KT_THEME_LANG),
                        'desc' => __('Select the date formart.', KT_THEME_LANG),
                        'options' => array(
                            'd F Y' => __( '05 December 2014', 'js_composer' ) ,
                            'F jS Y' => __( 'December 13th 2014', 'js_composer' ) ,
                            'jS F Y' => __( '13th December 2014', 'js_composer' ) ,
                            'd M Y' => __( '05 Dec 2014', 'js_composer' ) ,
                            'M d Y' => __( 'Dec 05 2014', 'js_composer' ) ,
                            'time' => __( 'Time ago', 'js_composer' ) ,
                        ),
                        'default' => 'd F Y',
                        'required' => array('author_meta','equals', array( 1 ) ),
                    ),
                    
                    array(
                        'id' => 'author_like_post',
                        'type' => 'switch',
                        'title' => __('Like Post', KT_THEME_LANG),
                        'desc' => __('Show like post in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('author_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'author_view_number',
                        'type' => 'switch',
                        'title' => __('Show View Number', KT_THEME_LANG),
                        'desc' => __('Show view number in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('author_meta','equals', array( 1 ) ),
                    ),
                )
            );

            /**
             *	Single post settings
             **/
            $this->sections[] = array(
                'id'			=> 'post_single_section',
                'title'			=> __( 'Single Post', KT_THEME_LANG ),
                'desc'			=> 'Single post settings',
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'blog_single_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Single post general', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'single_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'blog_sidebar',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar configuration', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose sidebar for single post", KT_THEME_LANG ),
                        'options'  => array(
                            'full' => __('No sidebars', KT_THEME_LANG),
                            'left' => __('Left Sidebar', KT_THEME_LANG),
                            'right' => __('Right Layout', KT_THEME_LANG)
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'blog_sidebar_left',
                        'type' => 'select',
                        'title'    => __( 'Single post: Sidebar left area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('blog_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'blog_sidebar_right',
                        'type'     => 'select',
                        'title'    => __( 'Single post: Sidebar right area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('blog_sidebar','equals','right'),
                        'clear' => false
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    
                    array(
                        'id' => 'title_meta_center',
                        'type' => 'switch',
                        'title' => __('Title and meta center ', KT_THEME_LANG),
                        'desc' => __('', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'blog_post_format',
                        'type' => 'switch',
                        'title' => __('Show Post format ', KT_THEME_LANG),
                        'desc' => __('', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),

                    array(
                        'id'   => 'blog_image_size',
                        'type' => 'select',
                        'options' => $image_sizes,
                        'title'    => __( 'Image size', KT_THEME_LANG ),
                        'desc' => __("Select image size.", KT_THEME_LANG),
                        'default' => 'blog_post'
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'blog_share_box',
                        'type' => 'switch',
                        'title' => __('Share box in posts', KT_THEME_LANG),
                        'desc' => __('Show share box in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'blog_next_prev',
                        'type' => 'switch',
                        'title' => __('Previous & next buttons', KT_THEME_LANG),
                        'desc' => __('Show Previous & next buttons in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'blog_author',
                        'type' => 'switch',
                        'title' => __('Author info in posts', KT_THEME_LANG),
                        'desc' => __('Show author info in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'blog_related',
                        'type' => 'switch',
                        'title' => __('Related posts', KT_THEME_LANG),
                        'desc' => __('Show related posts in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'blog_related_type',
                        'type'     => 'select',
                        'title'    => __( 'Related Query Type', KT_THEME_LANG ),
                        'subtitle'     => __( "", KT_THEME_LANG ),
                        'options'  => array(
                            'categories' => __('Categories', KT_THEME_LANG),
                            'tags' => __('Tags', KT_THEME_LANG),
                            'author' => __('Author', KT_THEME_LANG)
                        ),
                        'default'  => 'categories',
                        'clear' => false,
                    ),

                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'blog_meta',
                        'type' => 'switch',
                        'title' => __('Meta information', KT_THEME_LANG),
                        'desc' => __('Show Meta information in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'blog_meta_author',
                        'type' => 'switch',
                        'title' => __('Post Meta Author', KT_THEME_LANG),
                        'desc' => __('Show meta author in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required'  => array('blog_meta', "=", 1),
                    ),

                    array(
                        'id' => 'blog_meta_comments',
                        'type' => 'switch',
                        'title' => __('Post Meta Comments', KT_THEME_LANG),
                        'desc' => __('Show post meta comments in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required'  => array('blog_meta', "=", 1),
                    ),
                    array(
                        'id' => 'blog_meta_categories',
                        'type' => 'switch',
                        'title' => __('Post Meta Categories', KT_THEME_LANG),
                        'desc' => __('Show post meta categories in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required'  => array('blog_meta', "=", 1),
                    ),

                    array(
                        'id' => 'blog_meta_date',
                        'type' => 'switch',
                        'title' => __('Post Meta Date', KT_THEME_LANG),
                        'desc' => __('Show meta date in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required'  => array('blog_meta', "=", 1),
                    ),
                    array(
                        'id' => 'blog_date_format',
                        'type' => 'select',
                        'title' => __('Date format', KT_THEME_LANG),
                        'desc' => __('Select the date format.', KT_THEME_LANG),
                        'options' => array(
                            'd F Y' => __( '05 December 2014', 'js_composer' ) ,
                            'F jS Y' => __( 'December 13th 2014', 'js_composer' ) ,
                            'jS F Y' => __( '13th December 2014', 'js_composer' ) ,
                            'd M Y' => __( '05 Dec 2014', 'js_composer' ) ,
                            'M d Y' => __( 'Dec 05 2014', 'js_composer' ) ,
                            'time' => __( 'Time ago', 'js_composer' ) ,
                        ),
                        'default' => 'd F Y',
                        'required' => array('blog_meta_date','equals', array( 1 ) ),
                    ),
                    
                    array(
                        'id' => 'blog_like_post',
                        'type' => 'switch',
                        'title' => __('Like Post', KT_THEME_LANG),
                        'desc' => __('Show like post in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required'  => array('blog_meta', "=", 1),
                    ),
                    array(
                        'id' => 'blog_view_number',
                        'type' => 'switch',
                        'title' => __('View Number', KT_THEME_LANG),
                        'desc' => __('Show view number in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required'  => array('blog_meta', "=", 1),
                    ),
                )
            );

            /**
             *  Search settings
             **/
            $this->sections[] = array(
                'id'            => 'search_section',
                'title'         => __( 'Search', KT_THEME_LANG ),
                'desc'          => 'Search settings',
                'icon'          => 'icon-Data-Search',
                'fields'        => array(
                    array(
                        'id'       => 'search_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Search post general', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'search_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'search_sidebar',
                        'type'     => 'select',
                        'title'    => __( 'Sidebar configuration', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose archive page ", KT_THEME_LANG ),
                        'options'  => array(
                            'full' => __('No sidebars', KT_THEME_LANG),
                            'left' => __('Left Sidebar', KT_THEME_LANG),
                            'right' => __('Right Layout', KT_THEME_LANG)
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'search_sidebar_left',
                        'type' => 'select',
                        'title'    => __( 'Sidebar left area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('search_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'search_sidebar_right',
                        'type'     => 'select',
                        'title'    => __( 'Search: Sidebar right area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('search_sidebar','equals','right'),
                        'clear' => false
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'search_loop_style',
                        'type' => 'select',
                        'title' => __('Search Loop Style', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            'grid' => __( 'Grid', 'js_composer' ),
                            'list' => __( 'List', 'js_composer' ),
                            'masonry' => __( 'Masonry', 'js_composer' ),
                            'zigzag' => __( 'Zig Zag', 'js_composer' ),
                        ),
                        'default' => 'grid'
                    ),
                    array(
                        'id' => 'search_sharebox',
                        'type' => 'switch',
                        'title' => __('Share box', KT_THEME_LANG),
                        'desc' => __('Show or hide share box.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('search_loop_style','equals', array( 'classic' ) ),
                    ),
                    array(
                        'id' => 'search_columns',
                        'type' => 'select',
                        'title' => __('Columns on desktop', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            '1' => __( '1 column', 'js_composer' ) ,
                            '2' => __( '2 columns', 'js_composer' ) ,
                            '3' => __( '3 columns', 'js_composer' ) ,
                            '4' => __( '4 columns', 'js_composer' ) ,
                            '6' => __( '6 columns', 'js_composer' ) ,
                        ),
                        'default' => '2',
                        'required' => array('search_loop_style','equals', array( 'grid', 'masonry' ) ),
                    ),
                    array(
                        'id' => 'search_columns_tablet',
                        'type' => 'select',
                        'title' => __('Columns on Tablet', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            '1' => __( '1 column', 'js_composer' ) ,
                            '2' => __( '2 columns', 'js_composer' ) ,
                            '3' => __( '3 columns', 'js_composer' ) ,
                            '4' => __( '4 columns', 'js_composer' ) ,
                            '6' => __( '6 columns', 'js_composer' ) ,
                        ),
                        'default' => '2',
                        'required' => array('search_loop_style','equals', array( 'grid', 'masonry' ) ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'search_align',
                        'type' => 'select',
                        'title' => __('Text align', KT_THEME_LANG),
                        'desc' => __('Not working for search style classic', KT_THEME_LANG),
                        'options' => array(
                            'left' => __( 'Left', KT_THEME_LANG ) ,
                            'center' => __( 'Center', KT_THEME_LANG ) ,
                        ),
                        'default' => 'left'
                    ),
                    array(
                        'id' => 'search_readmore',
                        'type' => 'select',
                        'title' => __('Readmore button ', KT_THEME_LANG),
                        'desc' => __('Select button style.', KT_THEME_LANG),
                        "default" => 'link',
                        'options' => array(
                            '' => __('None', KT_THEME_LANG),
                            'link' => __( 'Link', 'js_composer' ),
                        ),
                    ),
                    array(
                        'id' => 'search_pagination',
                        'type' => 'select',
                        'title' => __('Pagination Type', KT_THEME_LANG),
                        'desc' => __('Select the pagination type.', KT_THEME_LANG),
                        'options' => array(
                            'classic' => __( 'Classic pagination', KT_THEME_LANG ),
                            'loadmore' => __( 'Load More button', KT_THEME_LANG ),
                            'normal' => __( 'Normal pagination', KT_THEME_LANG ),
                        ),
                        'default' => 'classic'
                    ),
                    array(
                        'id' => 'search_excerpt',
                        'type' => 'switch',
                        'title' => __('Show Excerpt? ', KT_THEME_LANG),
                        'desc' => __('Show or hide the excerpt.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id' => 'search_excerpt_length',
                        'type' => 'text',
                        'title' => __('Excerpt Length', KT_THEME_LANG),
                        'desc' => __("Insert the number of words you want to show in the post excerpts.", KT_THEME_LANG),
                        'default' => '30',
                    ),

                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'search_meta',
                        'type' => 'switch',
                        'title' => __('Show Meta? ', KT_THEME_LANG),
                        'desc' => __('Show or hide the meta.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),

                    array(
                        'id' => 'search_meta_author',
                        'type' => 'switch',
                        'title' => __('Post Meta Author', KT_THEME_LANG),
                        'desc' => __('Show meta author in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('search_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'search_meta_comments',
                        'type' => 'switch',
                        'title' => __('Post Meta Comments', KT_THEME_LANG),
                        'desc' => __('Show post meta comments in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('search_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'search_meta_categories',
                        'type' => 'switch',
                        'title' => __('Post Meta Categories', KT_THEME_LANG),
                        'desc' => __('Show post meta categories in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('search_meta','equals', array( 1 ) ),
                    ),

                    array(
                        'id' => 'search_meta_date',
                        'type' => 'switch',
                        'title' => __('Post Meta Date', KT_THEME_LANG),
                        'desc' => __('Show meta date in blog posts.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('search_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'search_date_format',
                        'type' => 'select',
                        'title' => __('Date format', KT_THEME_LANG),
                        'desc' => __('Select the date format.', KT_THEME_LANG),
                        'options' => array(
                            'd F Y' => __( '05 December 2014', 'js_composer' ) ,
                            'F jS Y' => __( 'December 13th 2014', 'js_composer' ) ,
                            'jS F Y' => __( '13th December 2014', 'js_composer' ) ,
                            'd M Y' => __( '05 Dec 2014', 'js_composer' ) ,
                            'M d Y' => __( 'Dec 05 2014', 'js_composer' ) ,
                            'time' => __( 'Time ago', 'js_composer' ) ,
                        ),
                        'default' => 'd F Y',
                        'required' => array('search_meta','equals', array( 1 ) ),
                    ),
                    
                    array(
                        'id' => 'search_like_post',
                        'type' => 'switch',
                        'title' => __('Like Post', KT_THEME_LANG),
                        'desc' => __('Show like post in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('search_meta','equals', array( 1 ) ),
                    ),
                    array(
                        'id' => 'search_view_number',
                        'type' => 'switch',
                        'title' => __('Show View Number', KT_THEME_LANG),
                        'desc' => __('Show view number in blog posts.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG),
                        'required' => array('search_meta','equals', array( 1 ) ),
                    ),
                )
            );

            /**
             *	404 Page
             **/
            $this->sections[] = array(
                'id'			=> '404_section',
                'title'			=> __( '404 Page', KT_THEME_LANG ),
                'desc'			=> '404 Page settings',
                'icon'          => 'icon-Error-404Window',
                'fields'		=> array(
                    array(
                        'id'       => 'notfound_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( '404 Page general', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'notfound_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 0,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),

                    array(
                        'id'       => '404_image',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => __( '404 Image', KT_THEME_LANG ),
                        'default'  => array(
                            'url' => KT_THEME_IMG.'404.png'
                        )
                    ),

                    array(
                        'id' => 'notfound_page_type',
                        'type' => 'select',
                        'title' => __('404 Page', KT_THEME_LANG),
                        'desc' => '',
                        'options' => array(
                            'default' => __( 'Default', KT_THEME_LANG ) ,
                            'page' => __( 'From Page', KT_THEME_LANG ) ,
                            'home' => __( 'Redirect Home', KT_THEME_LANG ) ,
                        ),
                        'default' => 'default',
                    ),


                    array(
                        'id'       => 'notfound_page_id',
                        'type'     => 'select',
                        'data'     => 'pages',
                        'title'    => __( 'Pages Select Option', KT_THEME_LANG ),
                        'desc'     => __( 'Select your page 404 you want use', KT_THEME_LANG ),
                        'required' => array( 'notfound_page_type', '=', 'page' ),
                    ),

                )
            );

            /**
			 *	Woocommerce
			 **/
			$this->sections[] = array(
				'id'			=> 'woocommerce',
				'title'			=> __( 'Woocommerce', KT_THEME_LANG ),
				'desc'			=> '',
				'icon'	=> 'icon-Full-Cart',
				'fields'		=> array(
                    array(
                        'id'       => 'shop_products_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Shop Products settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'shop_content_banner',
                        'type'     => 'editor',
                        'title'    => __( 'Shop banner', KT_THEME_LANG ),
                        'default'  => ''
                    ),

                    array(
                        'id' => 'shop_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'shop_sidebar',
                        'type'     => 'select',
                        'title'    => __( 'Shop: Sidebar configuration', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose sidebar for shop post", KT_THEME_LANG ),
                        'options'  => array(
                            'full' => __('No sidebars', KT_THEME_LANG),
                            'left' => __('Left Sidebar', KT_THEME_LANG),
                            'right' => __('Right Layout', KT_THEME_LANG)
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'shop_sidebar_left',
                        'type' => 'select',
                        'title'    => __( 'Shop: Sidebar left area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'shop-widget-area',
                        'required' => array('shop_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'shop_sidebar_right',
                        'type'     => 'select',
                        'title'    => __( 'Shop: Sidebar right area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'shop-widget-area',
                        'required' => array('shop_sidebar','equals','right'),
                        'clear' => false
                    ),

                    array(
                        'id'       => 'shop_products_layout',
                        'type'     => 'select',
                        'title'    => __( 'Shop: Products default Layout', KT_THEME_LANG ),
                        'options'  => array(
                            'grid' => __('Grid', KT_THEME_LANG ),
                            'lists' => __('Lists', KT_THEME_LANG )
                        ),
                        'default'  => 'grid'
                    ),
                    array(
                        'id'       => 'shop_gird_cols',
                        'type'     => 'select',
                        'title'    => __( 'Number column to display width gird mod', KT_THEME_LANG ),
                        'options'  => array(
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                        ),
                        'default'  => 3,
                    ),
                    array(
                        'id'       => 'shop_products_effect',
                        'type'     => 'select',
                        'title'    => __( 'Shop product effect', KT_THEME_LANG ),
                        'options'  => array(
                            'center' => __('Center', KT_THEME_LANG ),
                            'bottom' => __('Bottom', KT_THEME_LANG )
                        ),
                        'default'  => 'center'
                    ),
                    array(
                        'id'       => 'loop_shop_per_page',
                        'type'     => 'text',
                        'title'    => __( 'Number of products displayed per page', KT_THEME_LANG ),
                        'default'  => '12'
                    ),

                    // For Single Products
                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id'       => 'shop_single_product',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Single Product settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'product_page_header',
                        'type' => 'switch',
                        'title' => __('Show Page header', KT_THEME_LANG),
                        'desc' => __('Show page header or?.', KT_THEME_LANG),
                        "default" => 1,
                        'on' => __('Enabled', KT_THEME_LANG),
                        'off' =>__('Disabled', KT_THEME_LANG)
                    ),
                    array(
                        'id'       => 'product_sidebar',
                        'type'     => 'select',
                        'title'    => __( 'Product: Sidebar configuration', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose single product page ", KT_THEME_LANG ),
                        'options'  => array(
                            'full' => __('No sidebars', KT_THEME_LANG),
                            'left' => __('Left Sidebar', KT_THEME_LANG),
                            'right' => __('Right Layout', KT_THEME_LANG)
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'product_sidebar_left',
                        'type' => 'select',
                        'title'    => __( 'Product: Sidebar left area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'shop-widget-area',
                        'required' => array('product_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'product_sidebar_right',
                        'type'     => 'select',
                        'title'    => __( 'Product: Sidebar right area', KT_THEME_LANG ),
                        'subtitle'     => __( "Please choose left sidebar ", KT_THEME_LANG ),
                        'data'     => 'sidebars',
                        'default'  => 'shop-widget-area',
                        'required' => array('product_sidebar','equals','right'),
                        'clear' => false
                    ),

                    //Slider effect: Lightbox - Zoom
                    //Product description position - Tab, Below
                    //Product reviews position - Tab,Below
                    //Social Media Sharing Buttons
                    //Single Product Gallery Type
                    
                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id'       => 'shop_single_product',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Shop Product settings', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'time_product_new',
                        'type'     => 'text',
                        'title'    => __( 'Time Product New', KT_THEME_LANG ),
                        'default'  => '30',
                        'desc' => __('Time Product New ( unit: days ).', KT_THEME_LANG),
                    ),
                )
            );
            $this->sections[] = array(
				'id'			=> 'social',
				'title'			=> __( 'Socials', KT_THEME_LANG ),
				'desc'			=> __('Social and share settings', KT_THEME_LANG),
				'icon'	=> 'icon-Facebook-2',
				'fields'		=> array(

                    array(
						'id' => 'twitter',
						'type' => 'text',
						'title' => __('Twitter', KT_THEME_LANG),
						'subtitle' => __("Your Twitter username (no @).", KT_THEME_LANG),
						'default' => '#'
                    ),
                    array(
						'id' => 'facebook',
						'type' => 'text',
						'title' => __('Facebook', KT_THEME_LANG),
						'subtitle' => __("Your Facebook page/profile url", KT_THEME_LANG),
						'default' => '#'
                    ),
                    array(
						'id' => 'pinterest',
						'type' => 'text',
						'title' => __('Pinterest', KT_THEME_LANG),
						'subtitle' => __("Your Pinterest username", KT_THEME_LANG),
						'default' => '#'
                    ),
                    array(
						'id' => 'dribbble',
						'type' => 'text',
						'title' => __('Dribbble', KT_THEME_LANG),
						'subtitle' => __("Your Dribbble username", KT_THEME_LANG),
						'desc' => '',
						'default' => ''
				    ),
                    array(
						'id' => 'vimeo',
						'type' => 'text',
						'title' => __('Vimeo', KT_THEME_LANG),
						'subtitle' => __("Your Vimeo username", KT_THEME_LANG),
						'desc' => '',
						'default' => '#'
                    ),
                    array(
						'id' => 'tumblr',
						'type' => 'text',
						'title' => __('Tumblr', KT_THEME_LANG),
						'subtitle' => __("Your Tumblr username", KT_THEME_LANG),
						'desc' => '',
						'default' => '#'
				    ),
                    array(
						'id' => 'skype',
						'type' => 'text',
						'title' => __('Skype', KT_THEME_LANG),
						'subtitle' => __("Your Skype username", KT_THEME_LANG),
						'desc' => '',
						'default' => ''
					),
                    array(
						'id' => 'linkedin',
						'type' => 'text',
						'title' => __('LinkedIn', KT_THEME_LANG),
						'subtitle' => __("Your LinkedIn page/profile url", KT_THEME_LANG),
						'desc' => '',
						'default' => ''
					),
					array(
						'id' => 'googleplus',
						'type' => 'text',
						'title' => __('Google+', KT_THEME_LANG),
						'subtitle' => __("Your Google+ page/profile URL", KT_THEME_LANG),
						'desc' => '',
						'default' => '#'
					),
					array(
						'id' => 'youtube',
						'type' => 'text',
						'title' => __('YouTube', KT_THEME_LANG),
						'subtitle' => __("Your YouTube username", KT_THEME_LANG),
						'desc' => '',
						'default' => ''
					),
					array(
						'id' => 'instagram',
						'type' => 'text',
						'title' => __('Instagram', KT_THEME_LANG),
						'subtitle' => __("Your Instagram username", KT_THEME_LANG),
						'desc' => '',
						'default' => ''
					)
                )
            );
            
            /**
			 *	Popup
			 **/
			$this->sections[] = array(
				'id'			=> 'popup',
				'title'			=> __( 'Popup', KT_THEME_LANG ),
				'desc'			=> '',
				'icon'	=> 'icon-Studio-Flash',
				'fields'		=> array(
                    array(
						'id'		=> 'enable_popup',
						'type'		=> 'switch',
						'title'		=> __( 'Enable Popup', KT_THEME_LANG ),
						'subtitle'	=> __( '', KT_THEME_LANG),
						"default"	=> true,
						'on'		=> __( 'On', KT_THEME_LANG ),
						'off'		=> __( 'Off', KT_THEME_LANG ),
					),
                    array(
						'id'		=> 'disable_popup_mobile',
						'type'		=> 'switch',
						'title'		=> __( 'Disable Popup on Mobile', KT_THEME_LANG ),
						'subtitle'	=> __( '', KT_THEME_LANG),
						"default"	=> false,
						'on'		=> __( 'On', KT_THEME_LANG ),
						'off'		=> __( 'Off', KT_THEME_LANG ),
                        'required' => array('enable_popup','equals', 1)
					),
                    array(
                        'id' => 'time_show',
                        'type' => 'text',
                        'title' => __('Time to show', KT_THEME_LANG), 
                        'desc' => __('Unit: s', KT_THEME_LANG),
                        'default' => __('0', KT_THEME_LANG),
                        'required' => array('enable_popup','equals', 1)
                    ),

                    array(
                        'id' => 'title_popup',
                        'type' => 'text',
                        'title' => __('Title Popup', KT_THEME_LANG), 
                        'default' => __('Advanced Popup Module', KT_THEME_LANG),
                    ),

                    array(
                        'id'       => 'popup_image',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => __( 'Popup Image', KT_THEME_LANG ),
                        'default'  => array(
                            'url' => KT_THEME_IMG.'popup_image.png'
                        )
                    ),
                    
                    array(
                        'id'       => 'content_popup',
                        'type'     => 'editor',
                        'title'    => __( 'Content Popup', KT_THEME_LANG ),
                        'subtitle' => __( '', KT_THEME_LANG ),
                        'required' => array('enable_popup','equals', 1),
                        'default'  => __('<h4 class="newletter-title">Sign up for out newsletter<br /> to receive special offers.</h4>[kt_mailchimp list="9306fec7e3" disable_names="yes"]', KT_THEME_LANG),
                    ),
                )
            );



            $importer_errors = array();
            $max_execution_time  = ini_get("max_execution_time");
            $max_input_time      = ini_get("max_input_time");
            $upload_max_filesize = ini_get("upload_max_filesize");

            if ($max_execution_time < 120) {
                $importer_errors[] = '<li><strong>Maximum Execution Time (max_execution_time) : </strong>' . $max_execution_time . ' seconds. <span style="color:red"> Recommended max_execution_time should be at least 120 Seconds.</span></li>';
            }
            if ($max_input_time < 120)
                $importer_errors[] = '<li><strong>Maximum Input Time (max_input_time) : </strong>' . $max_input_time . ' seconds. <span style="color:red"> Recommended max_input_time should be at least 120 Seconds.</span></li>';

            if(intval(WP_MEMORY_LIMIT) < 40){
                $importer_errors[] = '<li><strong>WordPress Memory Limit (WP_MEMORY_LIMIT) : </strong>' . WP_MEMORY_LIMIT . ' <span style="color:red"> Recommended memory limit should be at least 40MB.</span></li>';
            }
            if (intval($upload_max_filesize) < 15) {
                $importer_errors[] = '<li><strong>Maximum Upload File Size (upload_max_filesize) : </strong>' . $upload_max_filesize . ' <span style="color:red"> Recommended Maximum Upload Filesize should be at least 15MB.</li>';
            }


            $importer = array();
            if(count($importer_errors)){
                $importer[] = array(
                    'id'    => 'demo_importer_critical',
                    'type'  => 'info',
                    'style' => 'critical',
                    'icon'  => 'el el-info-circle',
                    'title' => __( 'Server Requirements (Please resolve these issues before installing template.)', KT_THEME_LANG ),
                    'desc'  => '<ul>'.implode('', $importer_errors).'</ul>'
                );
            }
            $importer[] = array(
                'id'   => 'wbc_demo_importer',
                'type' => 'wbc_importer'
            );

            /**
			 *	Import Demo
			 **/
            $this->sections[] = array(
                 'id' => 'wbc_importer_section',
                 'title'  => esc_html__( 'Demo Content', KT_THEME_LANG ),
                 'desc'   => esc_html__( 'Chose a demo to import', KT_THEME_LANG ),
                 'icon'   => 'icon-Blackboard',
                 'fields' => $importer
            );

            /**
			 *	Advanced
			 **/
			$this->sections[] = array(
				'id'			=> 'advanced',
				'title'			=> __( 'Advanced', KT_THEME_LANG ),
				'desc'			=> '',
                'icon'	=> 'icon-Settings-Window',
            );


            /**
             *	Advanced Social Share
             **/
            $this->sections[] = array(
                'id'			=> 'share_section',
                'title'			=> __( 'Social Share', KT_THEME_LANG ),
                'desc'			=> '',
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'social_share',
                        'type'     => 'sortable',
                        'mode'     => 'checkbox', // checkbox or text
                        'title'    => __( 'Social Share', KT_THEME_LANG ),
                        'desc'     => __( 'Reorder and Enable/Disable Social Share Buttons.', KT_THEME_LANG ),
                        'options'  => array(
                            'facebook' => __('Facebook', KT_THEME_LANG),
                            'twitter' => __('Twitter', KT_THEME_LANG),
                            'google_plus' => __('Google+', KT_THEME_LANG),
                            'pinterest' => __('Pinterest', KT_THEME_LANG),
                            'linkedin' => __('Linkedin', KT_THEME_LANG),
                            'tumblr' => __('Tumblr', KT_THEME_LANG),
                            'mail' => __('Mail', KT_THEME_LANG),
                        ),
                        'default'  => array(
                            'facebook' => true,
                            'twitter' => true,
                            'google_plus' => true,
                            'pinterest' => true,
                            'linkedin' => true,
                            'tumblr' => true,
                            'mail' => true,
                        )
                    )
                )
            );


            /**
             *	Advanced Mail Chimp API
             **/
            $this->sections[] = array(
                'id'			=> 'socials_api_section',
                'title'			=> __( 'Socials API', KT_THEME_LANG ),
                'desc'			=> '',
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'facebook_app_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.__( 'Facebook App', KT_THEME_LANG ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'facebook_app',
                        'type' => 'text',
                        'title' => __('Facebook App ID', KT_THEME_LANG),
                        'subtitle' => __("Add Facebook App ID.", KT_THEME_LANG),
                        'default' => '417674911655656'
                    ),
                )
            );






            /**
			 *	Advanced Custom CSS
			 **/
			$this->sections[] = array(
				'id'			=> 'advanced_css',
				'title'			=> __( 'Custom CSS', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'advanced_editor_css',
                        'type'     => 'ace_editor',
                        'title'    => __( 'CSS Code', KT_THEME_LANG ),
                        'subtitle' => __( 'Paste your CSS code here.', KT_THEME_LANG ),
                        'mode'     => 'css',
                        'theme'    => 'chrome',
                        'full_width' => true
                    ),
                )
            );
            /**
			 *	Advanced Custom CSS
			 **/
			$this->sections[] = array(
				'id'			=> 'advanced_js',
				'title'			=> __( 'Custom JS', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'advanced_editor_js',
                        'type'     => 'ace_editor',
                        'title'    => __( 'JS Code', KT_THEME_LANG ),
                        'subtitle' => __( 'Paste your JS code here.', KT_THEME_LANG ),
                        'mode'     => 'javascript',
                        'theme'    => 'chrome',
                        'default'  => "jQuery(document).ready(function(){\n\n});",
                        'full_width' => true
                    ),
                )
            );
            /**
			 *	Advanced Tracking Code
			 **/
			$this->sections[] = array(
				'id'			=> 'advanced_tracking',
				'title'			=> __( 'Tracking Code', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'advanced_tracking_code',
                        'type'     => 'textarea',
                        'title'    => __( 'Tracking Code', KT_THEME_LANG ),
                        'desc'     => __( 'Paste your Google Analytics (or other) tracking code here. This will be added into the header template of your theme. Please put code inside script tags.', KT_THEME_LANG ),
                    )
                )
            );
            
            $info_arr = array();
            $theme = wp_get_theme();
            
            $info_arr[] = "<li><span>".__('Theme Name:', KT_THEME_LANG)." </span>". $theme->get('Name').'</li>';
            $info_arr[] = "<li><span>".__('Theme Version:', KT_THEME_LANG)." </span>". $theme->get('Version').'</li>';
            $info_arr[] = "<li><span>".__('Theme URI:', KT_THEME_LANG)." </span>". $theme->get('ThemeURI').'</li>';
            $info_arr[] = "<li><span>".__('Author:', KT_THEME_LANG)." </span>". $theme->get('Author').'</li>';
            
            $system_info = sprintf("<div class='troubleshooting'><ul>%s</ul></div>", implode('', $info_arr));
            
            
            /**
			 *	Advanced Troubleshooting
			 **/
			$this->sections[] = array(
				'id'			=> 'advanced_troubleshooting',
				'title'			=> __( 'Troubleshooting', KT_THEME_LANG ),
				'desc'			=> '',
                'subsection' => true,
				'fields'		=> array(
                    array(
                        'id'       => 'opt-raw_info_4',
                        'type'     => 'raw',
                        'content'  => $system_info,
                        'full_width' => true
                    ),
                )
            );
            
        }
        
    }
    
    global $reduxConfig;
    $reduxConfig = new KT_config();
    
} else {
    echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
}


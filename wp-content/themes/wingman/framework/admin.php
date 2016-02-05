<?php

if ( !function_exists( 'kt_admin_enqueue_scripts' ) ) {

    /**
     * Add stylesheet and script for admin
     *
     * @since       1.0
     * @return      void
     * @access      public
     */
    function kt_admin_enqueue_scripts(){
        wp_enqueue_style( 'kt-font-awesome', KT_THEME_FONTS.'/font-awesome/css/font-awesome.min.css');
        wp_enqueue_style( 'kt-icomoon-theme', KT_THEME_FONTS . '/Lineicons/style.css', array());
        wp_enqueue_style( 'kt-font-pe-icon-7', KT_THEME_FONTS . '/pe-icon-7-stroke/css/pe-icon-7-stroke.css', array());
        wp_enqueue_style( 'kt-framework-core', KT_FW_CSS.'/framework-core.css');
        wp_enqueue_style( 'kt-chosen', KT_FW_LIBS.'/chosen/chosen.min.css');
        wp_enqueue_style('kt-admin-style', KT_FW_CSS.'/theme-admin.css');

        wp_enqueue_script( 'kt-image', KT_FW_JS.'/kt_image.js', array('jquery'), KT_FW_VER, true);
        wp_enqueue_script( 'jquery-chosen', KT_FW_LIBS.'/chosen/chosen.jquery.min.js', array('jquery'), KT_FW_VER, true);
        wp_enqueue_script( 'kt-cookie', KT_FW_JS.'/jquery.cookie.js', array('jquery'), KT_FW_VER, true);
        wp_enqueue_script( 'kt-icons', KT_FW_JS.'/kt_icons.js', array('jquery'), KT_FW_VER, true);


        wp_localize_script( 'kt-image', 'kt_image_lang', array(
            'frameTitle' => esc_html__('Select your image', 'wingman' )
        ));

        wp_register_script( 'kt-framework-core', KT_FW_JS.'/framework-core.js', array('jquery', 'jquery-ui-tabs'), KT_FW_VER, true);
        wp_enqueue_script('kt-framework-core');

        $accent = kt_option('styling_accent', '#82c14f');
        $css = '.vc_colored-dropdown .accent {background-color: '.$accent.' !important;}';
        wp_add_inline_style('kt-admin-style', $css);

    } // End kt_admin_enqueue_scripts.
    add_action( 'admin_enqueue_scripts', 'kt_admin_enqueue_scripts' );
}


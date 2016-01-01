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
        wp_enqueue_style( 'kt-font-awesome', KT_THEME_FONTS.'font-awesome/css/font-awesome.min.css');
        wp_enqueue_style( 'kt-icomoon-theme', KT_THEME_FONTS . 'Lineicons/style.css', array());
        wp_enqueue_style( 'kt-framework-core', KT_FW_CSS.'framework-core.css');
        wp_enqueue_style( 'kt-chosen', KT_FW_LIBS.'chosen/chosen.min.css');
        wp_enqueue_style('kt-admin-style', KT_FW_CSS.'theme-admin.css');

        wp_enqueue_script( 'kt-image', KT_FW_JS.'kt_image.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'kt-chosen', KT_FW_LIBS.'chosen/chosen.jquery.min.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'kt-cookie', KT_FW_JS.'jquery.cookie.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'kt-showhide-metabox', KT_FW_JS.'kt_showhide_metabox.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'kt-icons', KT_FW_JS.'kt_icons.js', array('jquery'), FW_VER, true);


        wp_localize_script( 'kt-image', 'kt_image_lang', array(
            'frameTitle' => __('Select your image', KT_THEME_LANG )
        ));

        wp_register_script( 'kt-framework-core', KT_FW_JS.'framework-core.js', array('jquery', 'jquery-ui-tabs'), FW_VER, true);
        wp_enqueue_script('kt-framework-core');

        $accent = kt_option('styling_accent', '#d0a852');

        if( $accent !='' ) {
            $accent_darker = kt_colour_brightness($accent, -0.8);
            $css = '
                .vc_btn3.vc_btn3-color-accent,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-flat,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-modern,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d {background: %1$s !important;}
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline:hover,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline:focus {border-color: %1$s !important;color: %1$s!important;background: transparent !important;}
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d.vc_btn3-size-sm {box-shadow: 0 4px 0 %2$s !important;}
                .vc_colored-dropdown .accent {background-color: %1$s !important;}
                ';

            wp_add_inline_style('kt-admin-style', sprintf($css, $accent, $accent_darker));
        }

    } // End kt_admin_enqueue_scripts.
    add_action( 'admin_enqueue_scripts', 'kt_admin_enqueue_scripts' );
}


<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;




if(!function_exists('kt_setting_script_footer')){
    /**
     * Add advanced js to footer
     *
     */
    function kt_setting_script_footer() {
        $advanced_js = kt_option('advanced_editor_js');
        if($advanced_js){
            echo sprintf('<script type="text/javascript">%s</script>', $advanced_js);
        }
    }
    add_action('wp_footer', 'kt_setting_script_footer', 100);
}

/**
 * Theme Custom CSS
 *
 * @since       1.0
 * @return      void
 * @access      public
 */
function kt_setting_script() {
    $advanced_css = kt_option('advanced_editor_css');
    $accent = kt_option('styling_accent', '#82c14f');
    $accent_darker = kt_colour_brightness($accent, -0.8);
    $styling_link = kt_option('styling_link');

    ?>
    <style id="kt-theme-custom-css" type="text/css">
        <?php
            echo $advanced_css;
            if($styling_link['active']){
                echo 'a:focus{color: '.$styling_link['active'].';}';
            }
        ?>

        <?php if( $accent !='#82c14f' ){ ?>
            ::-moz-selection{ background:<?php echo $accent; ?>;}
            ::-webkit-selection{ background:<?php echo $accent; ?>;}
            ::selection{ background:<?php echo $accent; ?>;}

        .readmore-link,
        .readmore-link:hover,
        .testimonial-rate span:after,
        .blog-posts .entry-title a:hover,

        .woocommerce .star-rating span:before

        {
            color: <?php echo $accent; ?>
        }

        #footer-area h3.widget-title:after,
        #footer-area h3.widget-title:before,
        .readmore-link:before,
        .readmore-link:after
        {
            background: <?php echo $accent; ?>;
        }

        <?php } ?>
        <?php
            $color_first_loader = kt_option('color_first_loader', $accent);
            echo '.kt_page_loader.style-1 .page_loader_inner{border-color: '.$color_first_loader.';}';
            echo '.kt_page_loader.style-1 .kt_spinner{background-color: '.$color_first_loader.';}';




            $is_shop = false;
            if(is_archive()){
                if(kt_is_wc()){
                    if(is_shop()){
                        $is_shop = true;
                    }
                }
            }

            if(is_page() || is_singular() || $is_shop){

                global $post;
                $post_id = $post->ID;
                if($is_shop){
                    $post_id = get_option( 'woocommerce_shop_page_id' );
                }

                $pageh_spacing = rwmb_meta('_kt_page_top_spacing', array(), $post_id);
                if($pageh_spacing != ''){
                    echo '#content{padding-top: '.$pageh_spacing.';}';
                }
                $pageh_spacing = rwmb_meta('_kt_page_bottom_spacing', array(), $post_id);
                if($pageh_spacing != ''){
                    echo '#content{padding-bottom:'.$pageh_spacing.';}';
                }
            }

            if($navigation_space = kt_option('navigation_space', 30)){
                echo '#main-navigation > li{margin-left: '.$navigation_space.'px;}#main-navigation > li:first-child {margin-left: 0;}#main-navigation > li:last-child {margin-right: 0;}';
            }
            if($navigation_color_hover = kt_option('navigation_color_hover')){
                echo '#main-navigation > li > a:before, #main-navigation > li > a:after{background: '.$navigation_color_hover.';}';
            }
            if($mega_title_color = kt_option('mega_title_color')){
                echo '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:after, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > span:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > span:after, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > .widget-title:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > .widget-title:after{background-color: '.$mega_title_color.';}';
            }
            if($mega_title_color_hover = kt_option('mega_title_color_hover')){
                echo '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:hover:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:hover:after{background-color: '.$mega_title_color_hover.';}';
            }

            $navigation_height = kt_option('navigation_height');
            if(!$navigation_height['height'] || $navigation_height['height'] == 'px'){
                $navigation_height['height'] = 100;
            }
            echo '#main-navigation > li{line-height: '.intval($navigation_height['height']).'px;}';
            echo '.header-container.is-sticky.sticky-header-down .nav-container .nav-container-inner,.header-container.header-layout2.is-sticky.sticky-header-down #header-content{top: -'.intval($navigation_height['height']).'px;}';

            $header_sticky_opacity = kt_option('header_sticky_opacity', 0.8);
            echo '.header-sticky-background{opacity:'.$header_sticky_opacity.';}';


            $navigation_height_fixed = kt_option('navigation_height_fixed');


            if(!$navigation_height_fixed['height'] || $navigation_height_fixed['height'] == 'px'){
                $navigation_height_fixed['height'] = 60;
            }
            echo '.header-container.is-sticky #main-navigation > li{line-height: '.intval($navigation_height_fixed['height']).'px;}';



        ?>

        /*
        @media (max-width: 991px) {
            <?php /*$logo_width = kt_option('logo_mobile_width'); ?>
            .site-branding .site-logo img{
                width: <?php echo $logo_width['width'] ?>!important;
            }
            <?php

            $logo_mobile_margin_spacing = kt_option('logo_mobile_margin_spacing', '');
            $style_logo_mobile = '';
            if($margin_top = $logo_mobile_margin_spacing['margin-top']){
                $style_logo_mobile .= 'margin-top:'.$margin_top.'!important;';
            }
            if($margin_right = $logo_mobile_margin_spacing['margin-right']){
                $style_logo_mobile .= 'margin-right:'.$margin_right.';';
            }
            if($margin_bottom = $logo_mobile_margin_spacing['margin-bottom']){
                $style_logo_mobile .= 'margin-bottom:'.$margin_bottom.'!important;';
            }
            if($margin_left = $logo_mobile_margin_spacing['margin-left']){
                $style_logo_mobile .= 'margin-left:'.$margin_left.';';
            }
            if($style_logo_mobile){
                echo '.site-branding .site-logo img{'.$style_logo_mobile.'}';
            }
            */
            ?>
        }
        */
    </style>
    <?php
}
add_action('wp_head', 'kt_setting_script');
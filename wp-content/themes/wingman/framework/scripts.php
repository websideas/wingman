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
    $accent = kt_option('styling_accent', '#d0a852');
    $accent_darker = kt_colour_brightness($accent, -0.8);
    $styling_link = kt_option('styling_link');

    ?>
    <style id="kt-theme-custom-css" type="text/css">
        <?php
            echo $advanced_css;
            if($styling_link['active']){
                echo 'a:focus{color: '.$styling_link['active'].';}';
            }
            if( $styling_link['hover'] ){ ?>
                .post-navigation .meta-nav a:hover,
                .post-navigation .meta-nav a:focus{
                    border-color:<?php echo $styling_link['hover']; ?>;
                    color:<?php echo $styling_link['hover']; ?>
                }
                .post-navigation .meta-nav.nav-blog a:hover span i,
                .post-navigation .meta-nav.nav-blog a:focus span i,
                .post-navigation .meta-nav.nav-previous a:focus:before,
                .post-navigation .meta-nav.nav-previous a:focus:after,
                .post-navigation .meta-nav.nav-next a:focus:before,
                .post-navigation .meta-nav.nav-next a:focus:after,
                .post-navigation .meta-nav.nav-previous a:hover:before,
                .post-navigation .meta-nav.nav-previous a:hover:after,
                .post-navigation .meta-nav.nav-next a:hover:before,
                .post-navigation .meta-nav.nav-next a:hover:after{
                    background-color:<?php echo $styling_link['hover']; ?>
                }
            <?php }
        ?>

        <?php if( $accent !='23456' ){ ?>

            ::-moz-selection{ background:<?php echo $accent; ?>;}
            ::-webkit-selection{ background:<?php echo $accent; ?>;}
            ::selection{ background:<?php echo $accent; ?>;}

            #header-content-mobile .mobile-cart-total,
            #nav > #main-nav-tool > li > a .mini-cart-total,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
            .featured-vertical-item .entry-main-content .cat-links a,
            .featured-carousel-item .entry-main-content .cat-links a,
            .widget_kt_instagram ul li a:after,
            .kt_flickr a:after,
            body .mejs-controls .mejs-time-rail .mejs-time-current,
            body .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
            .kt-skill-bg-accent .kt-skill-bar,

            .highlight,

            .woocommerce .product .yith-wcwl-add-to-wishlist .ajax-loading:hover,
            .woocommerce ul.shop-products .added_to_cart:hover,
            .woocommerce ul.shop-products .button:hover,
            .woocommerce ul.shop-products .product-quick-view:hover,
            .woocommerce.compare-button:hover,
            .woocommerce .product .yith-wcwl-add-button:hover,
            .woocommerce .product .yith-wcwl-wishlistaddedbrowse:hover,
            .woocommerce .product .yith-wcwl-wishlistexistsbrowse:hover,

            body .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar,
            body .mCSB_scrollTools .mCSB_dragger:active .mCSB_dragger_bar,
            body .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar,
            body .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar,

            .vc_btn3.vc_btn3-color-accent,
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-flat,
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-modern,
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d,

            #backtotop:hover,
            #cancel-comment-reply-link:hover,


            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-heading:focus,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:focus,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-panel.vc_active .vc_tta-panel-heading,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-heading,

            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-heading:focus,

            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-tab > a:hover,
            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-tab > a:focus,
            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-tab.vc_active > a,
            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-panel.vc_active .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-panel.vc_active .vc_tta-panel-heading:focus,
            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-panel .vc_tta-panel-body,
            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-panel .vc_tta-panel-heading,

            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-tab > a:hover,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-tab > a:focus,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-tab.vc_active > a,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-tab > a:hover,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-tab > a:focus,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-tab.vc_active > a,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-tab > a:hover,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-tab > a:focus,
            .vc_icon_element.vc_icon_element-outer .vc_icon_element-inner.vc_icon_element-background-color-accent.vc_icon_element-background
            {
                background-color: <?php echo $accent; ?>;
            }
            .woocommerce .single-product-main-thumbnails .owl-item.synced a,
            blockquote,
            .blockquote-reverse,
            blockquote.pull-right,
            .fotorama__thumb-border,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel.vc_active .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel.vc_active .vc_tta-panel-heading:focus,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-body,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-body:before,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-body:after,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-heading,

            .vc_tta-color-accent.vc_tta-style-outline.vc_tta-tabs .vc_tta-panels,
            .vc_tta-color-accent.vc_tta-style-outline.vc_tta-tabs .vc_tta-panels:before,
            .vc_tta-color-accent.vc_tta-style-outline.vc_tta-tabs .vc_tta-panels:after
            {
                border-color: <?php echo $accent; ?>;
            }

            .kt-aboutwidget-socials a:hover,
            .search-heading .search-keyword,
            .search-content-error .search-keyword,
            blockquote.classic footer,
            .kt-aboutwidget-title,
            .post-link-content .post-link-url a,
            .woocommerce div.product p.price,
            .woocommerce div.product span.price,
            .woocommerce .star-rating span:before,
            .woocommerce ul.shop-products h3 a:hover,
            .woocommerce .woocommerce-message .button.wc-forward:hover,
            .woocommerce p.stars a:hover,

            .readmore-link:hover,
            .readmore-link-white:hover,
            .blog-posts .entry-title a:hover,
            .kt_tabs_content ul li .title a:hover,
            .widget_kt_posts ul li .title a:hover,
            #related-article h2.entry-title a:hover,
            .author-info h2.author-title a:hover,
            .author-info .author-social a:hover,
            .comment-meta h5 a:hover,
            .widget_pages ul li a:hover,
            .widget_nav_menu ul li a:hover,
            .widget_meta ul li a:hover,
            .widget_archive ul li a:hover,
            .widget_product_categories ul li a:hover,
            .widget_categories ul li a:hover,
            .yith-woocompare-widget ul.products-list li a.title:hover,
            .woocommerce ul.product_list_widget li a:hover,
            .vc_icon_element.vc_icon_element-outer .vc_icon_element-inner.vc_icon_element-color-accent .vc_icon_element-icon,

            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel.vc_active .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel.vc_active .vc_tta-panel-heading:focus,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-heading,

            .woocommerce nav.woocommerce-pagination ul li a:focus,
            .woocommerce nav.woocommerce-pagination ul li a:hover,
            .woocommerce nav.woocommerce-pagination ul li span.current,
            .pagination a.page-numbers:hover,
            .pagination .page-numbers.current,
            .entry-share-box a:hover,

            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-tab.vc_active > a:hover,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-tab.vc_active > a:focus,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-tab.vc_active > a

            {
                color: <?php echo $accent; ?>;
            }

            .button.alt:hover,.button.alt:focus,
            .btn.alt,
            .button:hover,
            .button:focus,
            .btn-default.active,
            .btn-default.focus,
            .btn-default:active,
            .btn-default:focus,
            .btn-default:hover,
            .wpcf7-submit:hover,
            .button.btn-light:hover,
            .button.btn-light:focus,
            .btn.btn-light:hover,
            .btn.btn-light:focus,
            .button.btn-dark:hover,
            .button.btn-dark:focus,
            .btn.btn-dark:hover,
            .btn.btn-dark:focus,

            .kt_image_banner:hover .btn-default,
            .kt_image_banner:hover .btn-light,
            .kt_image_banner:hover .btn-dark,

            .woocommerce #respond input#submit:hover,
            .woocommerce a.button:hover,
            .woocommerce button.button:hover,
            .woocommerce input.button:hover,
            .woocommerce #respond input#submit.alt:hover,
            .woocommerce a.button.alt:hover,
            .woocommerce button.button.alt:hover,
            .woocommerce input.button.alt:hover,

            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline:hover,
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline:focus,

            .woocommerce div.product .cart .single_add_to_cart_button:hover,
            #main-content-sideshow .carousel-navigation-center .owl-kttheme .owl-buttons > div i,
            .widget_product_tag_cloud a:hover,
            .widget_tag_cloud a:hover,

            .wc-single-product .functional-buttons-product .woocommerce.compare-button a:hover:before,
            .woocommerce .wc-single-product .functional-buttons-product .yith-wcwl-add-button a.add_to_wishlist:hover:before,
            .woocommerce .wc-single-product .functional-buttons-product .yith-wcwl-wishlistaddedbrowse a:hover:before,
            .woocommerce .wc-single-product .functional-buttons-product .yith-wcwl-wishlistexistsbrowse a:hover:before,

            .wc-single-product .functional-buttons-product .woocommerce.compare-button a:hover:before,
            .woocommerce .wc-single-product .functional-buttons-product .yith-wcwl-add-button a.add_to_wishlist:hover:before,
            .woocommerce .wc-single-product .functional-buttons-product .yith-wcwl-wishlistaddedbrowse a:hover:before,
            .woocommerce .wc-single-product .functional-buttons-product .yith-wcwl-wishlistexistsbrowse a:hover:before{
                border-color: <?php echo $accent; ?>;
                background: <?php echo $accent; ?>;
            }



            .widget_nav_menu ul li a:hover:after,
            .widget_product_categories ul li a:hover:after,
            .widget_categories ul li a:hover:after,
            .widget_archive ul li a:hover:after,
            .widget_meta ul li a:hover:after,
            .yith-woocompare-widget ul.products-list li a.title:hover:after{
                background: <?php echo $accent; ?>;
                color: <?php echo $accent; ?>;
            }



            .button,
            .wpcf7-submit,
            .btn-default,
            .woocommerce .single-product-main-images.owl-theme .owl-controls .owl-buttons div:hover,

            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline,

            .button.btn-gray-b:hover,
            .button.btn-gray-b:focus,
            .btn.btn-gray-b:hover,
            .btn.btn-gray-b:focus,
            .button.btn-light-b:hover,
            .button.btn-light-b:focus,
            .btn.btn-light-b:hover,
            .btn.btn-light-b:focus,
            .button.btn-dark-b:hover,
            .button.btn-dark-b:focus,
            .btn.btn-dark-b:hover,
            .btn.btn-dark-b:focus,
            .vc_tta-color-accent.vc_tta-style-outline .vc_tta-tab > a
            {
                border-color: <?php echo $accent; ?>;
                color: <?php echo $accent; ?>;
            }

            .vc_icon_element.vc_icon_element-outer .vc_icon_element-inner.vc_icon_element-style-hexagonal.vc_icon_element-background-color-accent.vc_icon_element-background:before {
                border-bottom-color: <?php echo $accent; ?>;
            }

            .vc_icon_element.vc_icon_element-outer .vc_icon_element-inner.vc_icon_element-style-hexagonal.vc_icon_element-background-color-accent.vc_icon_element-background:after {
                border-top-color: <?php echo $accent; ?>;
            }

            .social-background-empty.social-style-accent a,
            .social-background-outline.social-style-accent a{
                border-color: <?php echo $accent; ?>!important;
            }

            .social-background-empty.social-style-accent a,
            .social-background-outline.social-style-accent a{
                color: <?php echo $accent; ?>!important;
            }
            .social-background-fill.social-style-accent a,
            .woocommerce.compare-button .blockUI.blockOverlay{
                background: <?php echo $accent; ?>!important;
            }

            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-heading:focus,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:hover,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:focus,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-panel.vc_active .vc_tta-panel-heading,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-heading,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-tab > a:hover,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-tab > a:focus,
            .vc_tta-color-accent.vc_tta-style-modern .vc_tta-tab.vc_active > a,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-tab > a:hover,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-tab > a:focus,
            .vc_tta-color-accent.vc_tta-style-classic .vc_tta-tab.vc_active > a
            {
                border-color: <?php echo $accent_darker; ?>;
            }




            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d{
                box-shadow: 0 5px 0 <?php echo $accent_darker; ?>;
            }
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d:hover,
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d:focus {
                box-shadow: 0 2px 0 <?php echo $accent_darker; ?>;
            }

            .vc_tta-color-accent.vc_tta-style-flat .vc_tta-tab > a,
            .vc_btn3.vc_btn3-color-accent:hover,
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-flat:hover,
            .vc_btn3.vc_btn3-color-accent:focus,
            .vc_btn3.vc_btn3-color-accent.vc_btn3-style-flat:focus{
                background-color: <?php echo $accent_darker; ?>;
            }




        <?php } ?>
        <?php

            /*
            $header_opacity = kt_option('header_opacity', 1);
            echo '.header-background{opacity:'.$header_opacity.';}';

            $header_sticky_opacity = kt_option('header_sticky_opacity', 0.8);
            echo '.header-sticky-background{opacity:'.$header_sticky_opacity.';}';

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

                $pageh_top = rwmb_meta('_kt_page_header_top', array(), $post_id);
                if($pageh_top != ''){
                    echo 'div.page-header{padding-top: '.$pageh_top.';}';
                }

                $pageh_bottom = rwmb_meta('_kt_page_header_bottom', array(), $post_id);
                if($pageh_bottom != ''){
                    echo 'div.page-header{padding-bottom: '.$pageh_bottom.';}';
                }

                $pageh_title_color = rwmb_meta('_kt_page_header_title_color', array(), $post_id);
                if($pageh_title_color != ''){
                    echo 'div.page-header h1.page-header-title{color:'.$pageh_title_color.';}';
                }

                $pageh_subtitle_color = rwmb_meta('_kt_page_header_subtitle_color', array(), $post_id);
                if($pageh_subtitle_color != ''){
                    echo 'div.page-header .page-header-subtitle{color:'.$pageh_subtitle_color.';}';
                }

                $pageh_breadcrumbs_color = rwmb_meta('_kt_page_header_breadcrumbs_color', array(), $post_id);
                if($pageh_breadcrumbs_color != ''){
                    echo 'div.page-header .breadcrumbs,div.page-header .breadcrumbs a{color:'.$pageh_breadcrumbs_color.';}';
                }

                kt_render_custom_css('_kt_page_header_bg', 'div.page-header', $post_id);
                kt_render_custom_css('_kt_body_background', 'body.layout-full, body.layout-boxed #page', $post_id);
                kt_render_custom_css('_kt_boxed_background', 'body.layout-boxed', $post_id);

            }

            $navigation_height = kt_option('navigation_height');
            if(!$navigation_height['height'] || $navigation_height['height'] == 'px'){
                $navigation_height['height'] = '100px';
            }
            echo '#nav > ul > li{line-height: '.$navigation_height['height'].'}';

            $navigation_height_fixed = kt_option('navigation_height_fixed');
            if(!$navigation_height_fixed['height'] || $navigation_height_fixed['height'] == 'px'){
                $navigation_height_fixed['height'] = '100px';
            }
            echo '.header-container.is-sticky #nav > ul > li{line-height: '.$navigation_height_fixed['height'].'}';

            if($navigation_bordertop = kt_option('navigation_bordertop')){
                echo '#main-nav-tool .kt-wpml-languages ul, #main-navigation > li .kt-megamenu-wrapper, #main-navigation > li ul.sub-menu-dropdown, .shopping-bag-wrapper{border-color: '.$navigation_bordertop.';}';
            }


            if($navigation_light_color = kt_option('navigation_light_color')){
                echo '.header-light #main-navigation > li > a span:after,.header-light .mobile-nav-bar span.mobile-nav-handle:before,.header-light .mobile-nav-bar span.mobile-nav-handle:after,.header-light .mobile-nav-bar span.mobile-nav-handle span{background:'.$navigation_light_color.';}';
                echo '.header-light .button-toggle .line,.header-light .button-toggle .close:before,.header-light .button-toggle .close:after{background:'.$navigation_light_color.';}';
            }
            if($navigation_light_color_hover = kt_option('navigation_light_color_hover')){
                echo '.header-light #nav > ul > li > a:hover span:after, .header-light #nav > ul > li > a:focus span:after, .header-light #nav > ul > li.current-menu-item > a span:after, .header-light #nav > ul > li.current-menu-parent > a span:after{background: '.$navigation_light_color_hover.';}';
                echo '.header-light .mobile-nav-bar:hover span.mobile-nav-handle:before,.header-light .mobile-nav-bar:hover span.mobile-nav-handle:after,.header-light .mobile-nav-bar:hover span.mobile-nav-handle span{background: '.$navigation_light_color_hover.';}';
            }
            if($navigation_dark_color = kt_option('navigation_dark_color')){
                echo '.header-dark #main-navigation > li > a span:after,.header-dark .mobile-nav-bar span.mobile-nav-handle:before,.header-dark .mobile-nav-bar span.mobile-nav-handle:after,.header-dark .mobile-nav-bar span.mobile-nav-handle span{background:'.$navigation_dark_color.';}';
                echo '.header-dark .button-toggle .line,.header-dark .button-toggle .close:before,.header-dark .button-toggle .close:after{background:'.$navigation_light_color.';}';
            }
            if($navigation_dark_color_hover = kt_option('navigation_dark_color_hover')){
                echo '.header-dark #nav > ul > li > a:hover span:after, .header-dark #nav > ul > li > a:focus span:after, .header-dark #nav > ul > li.current-menu-item > a span:after, .header-dark #nav > ul > li.current-menu-parent > a span:after{background: '.$navigation_dark_color_hover.';}';
            }

            if($navigation_space = kt_option('navigation_space', 20)){
                echo '#nav > ul > li{margin-left: '.$navigation_space.'px;}#main-navigation > li:first-child {margin-left: 0;}';
            }

            $dropdown_background_hover = kt_option('dropdown_background_hover');
            if($dropdown_background_hover['background-color'] != ''){
                echo '#main-nav-tool .kt-wpml-languages ul li, #main-navigation > li ul.sub-menu-dropdown > li{border-color: '.$dropdown_background_hover['background-color'].'}';
            }
            if($mega_vertical = kt_option('mega_vertical', '#282828')){
                echo '#main-navigation > li .kt-megamenu-wrapper.megamenu-layout-table > ul > li{border-color: '.$mega_vertical.';}';
            }
            if($mega_color = kt_option('mega_color', '#282828')){
                echo '.bag-buttons .btn-viewcart{border-color: '.$mega_color.';color:'.$mega_color.';}';
            }
            if($mega_color_hover = kt_option('mega_color_hover')){
                echo '#main-navigation > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > .sub-menu-megamenu > li > a:before{background-color: '.$mega_color_hover.';color:'.$mega_color_hover.';}';
            }



            if($navigation_box_background = kt_option('navigation_box_background')){
                    echo '.bag-buttons .btn-viewcart:hover{background: '.$mega_color.';border-color: '.$mega_color.';color:'.$navigation_box_background['background-color'].';}';
                }
            if($mega_title_color = kt_option('mega_title_color')){
                echo '#main-navigation > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > a, #main-navigation > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > span.megamenu-title, #main-navigation > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li .widget-title{border-color: '.$mega_title_color.';}';
            }
            if($cart_divders = kt_option('cart_divders')){
                echo '.shopping-bag-wrapper .cart-title, .bag-total{border-color: '.$cart_divders.';}';
            }

            if($mobile_sub_color_hover = kt_option('mobile_sub_color_hover')){
                echo 'ul.navigation-mobile > li .sub-menu-dropdown > li > a span:before, ul.navigation-mobile > li .kt-megamenu-wrapper > ul.kt-megamenu-ul > li > .sub-menu-megamenu > li > a span:before{background: '.$mobile_sub_color_hover.';}';
            }

            $typography_footer_widgets_link = kt_option('typography_footer_widgets_link');
            if($widgets_link = $typography_footer_widgets_link['regular']){
                echo '#footer-area .btn-default, #footer-area .button{border-color: '.$widgets_link.'; color: '.$widgets_link.';}';
                echo '#footer-area .widget_rss ul li:after, #footer-area .widget_recent_comments ul li:after, #footer-area .widget_recent_entries ul li:after, #footer-area .widget_nav_menu ul li a:after, #footer-area .widget_pages ul li a:after, #footer-area .widget_product_categories ul li a:after, #footer-area .widget_categories ul li a:after, #footer-area .widget_archive ul li a:after, #footer-area .widget_meta ul li a:after, #footer-area .yith-woocompare-widget ul li a.title:after{color: '.$widgets_link.';background: '.$widgets_link.'; }';
                echo '#footer-area .entry-meta-data{color: '.$widgets_link.';}';
            }
            if($widgets_link_hover = $typography_footer_widgets_link['hover']){
                echo '#footer-area .btn-default:hover, #footer-area .button:hover{border-color: '.$widgets_link_hover.'; background: '.$widgets_link_hover.';color: #FFF'.'}';
                echo '#footer-area .widget_nav_menu ul li a:hover:after, #footer-area .widget_product_categories ul li a:hover:after,#footer-area .widget_categories ul li a:hover:after,#footer-area .widget_archive ul li a:hover:after, #footer-area .widget_meta ul li a:hover:after, #footer-area .yith-woocompare-widget ul.products-list li a.title:hover:after{color: '.$widgets_link_hover.';background: '.$widgets_link_hover.'; }';
                echo '#footer-area .widget_product_tag_cloud a:hover, #footer-area .widget_product_tag_cloud a:focus, #footer-area .widget_tag_cloud a:hover, #footer-area .widget_tag_cloud a:focus{color: #FFF;background: '.$widgets_link_hover.';border-color: '.$widgets_link_hover.';}';
            }

            if($use_page_loader = kt_option( 'use_page_loader', 1 )){
                $bg_page_loader = kt_option( 'background_page_loader', '#ffffff' );
                $color_loader = kt_option( 'color_first_loader' , '#d0a852');
                $color_second_loader = kt_option( 'color_second_loader', '#cccccc' );


                if( $bg_page_loader ){
                    echo '.kt_page_loader{ background: '.$bg_page_loader.'; }';
                }
                if( $color_second_loader ){
                    echo '.kt_page_loader.style-1 .kt_spinner{ border-color: '.$color_second_loader.'}';
                    echo '.kt_page_loader.style-2 .kt_spinner{ border-color: '.$color_second_loader.'}';
                }



                if( $color_loader ){
                    echo '.kt_page_loader.style-1 .kt_spinner:after{ background:'.$color_loader.'; }';
                    echo '.kt_page_loader.style-2 .kt_spinner,.kt_page_loader.style-3 .kt_spinner{ border-bottom-color: '.$color_loader.'; }';
                    echo '.kt_page_loader.style-3 .kt_spinner{ border-top-color: '.$color_loader.'; }';
                    echo '.kt_page_loader.style-4 .kt_spinner:after, .kt_page_loader.style-4 .kt_spinner:before{ background: '.$color_loader.'; }';
                }
            }
           */
        ?>

        /*
        @media (max-width: 991px) {
            <?php $logo_width = kt_option('logo_mobile_width'); ?>
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
            ?>
        }
        */
    </style>
    <?php
}
//add_action('wp_head', 'kt_setting_script');
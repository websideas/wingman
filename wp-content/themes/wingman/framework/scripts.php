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
            .testimonial-carousel-skin-light .testimonial-item .testimonial-rate span::after,
            .blog-posts .entry-title a:hover,
            .woocommerce .woocommerce-pagination .page-numbers:hover, 
            .woocommerce .woocommerce-pagination .page-numbers:focus, 
            .woocommerce .woocommerce-pagination .page-numbers.current, 
            .pagination .page-numbers:hover, 
            .pagination .page-numbers:focus, 
            .pagination .page-numbers.current,
            .post-single .tags-links a:hover, 
            .post-single .tags-links a:focus,

            .widget_pages ul li a:hover, 
            .widget_pages ul li a:focus, 
            .widget_nav_menu ul li a:hover, 
            .widget_nav_menu ul li a:focus, 
            .widget_meta ul li a:hover, 
            .widget_meta ul li a:focus, 
            .widget_archive ul li a:hover, 
            .widget_archive ul li a:focus, 
            .widget_product_categories ul li a:hover, 
            .widget_product_categories ul li a:focus, 
            .widget_categories ul li a:hover, 
            .widget_categories ul li a:focus, 
            .yith-woocompare-widget ul li a:hover, 
            .yith-woocompare-widget ul li a:focus,
            .woocommerce ul.product_list_widget li a:hover,
            .widget_recent_comments ul li:hover a,
            .widget_recent_entries ul li:hover a,

            .uranus.tparrows:hover::before, 
            .uranus.tparrows:hover::after,
            .team .team-attr .agency,
            .wrapper-comingsoon.style2 .coming-soon .wrap .value-time,
            .wrapper-comingsoon.style3 .coming-soon .wrap .value-time,

            .owl-carousel-kt.carousel-dark .owl-buttons > div:hover,
            .owl-carousel-kt.carousel-light .owl-buttons > div:hover,
            .woocommerce p.stars a:hover,
            .comment-actions a:hover, 
            .comment-actions a:focus,
            .menu-bars-outer .menu-bars-items ul li a:hover,
            .bag-products .bag-product .bag-product-title a:hover,
            .woocommerce .widget_price_filter .price_slider_amount .price_label span,
            .page-header .breadcrumbs a:hover,
            .woocommerce table.cart tbody td.product-name a:hover, 
            .woocommerce table.cart tbody td.product-name a:focus,
            .widget_layered_nav ul li a:hover,
            #main-nav-tool li > a:hover,
            .woocommerce ul.shop-products h3 a:hover,
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover, 
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus,
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover::before, 
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus::before,
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover::before, 
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus::before,
            .woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:hover, 
            .woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:focus,
            .woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:hover::before, 
            .woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:focus::before,
            .single-product-quickview .single-product-quickview-images .slick-arrow:hover::before, 
            .single-product-quickview .single-product-quickview-images .slick-arrow:focus::before,
            
            .woocommerce .woocommerce-per-page::after, 
            .woocommerce .woocommerce-ordering::after,
            .woocommerce .woocommerce-message .button.wc-forward:hover, 
            .woocommerce-page .woocommerce-message .button.wc-forward:hover,
            .menu-bars-outer > a:hover,
            .woocommerce .widget_price_filter .price_slider_amount .button:hover, 
            .woocommerce .widget_price_filter .price_slider_amount .button:focus,
            .entry-share-box a:hover,
            .woocommerce-page div.product .product_meta > span a:hover, 
            .woocommerce div.product .product_meta > span a:hover,
            .woocommerce .star-rating span:before

            {
                color: <?php echo $accent; ?>
            }
            
            .woocommerce.compare-button a:hover, 
            .woocommerce.compare-button a.add_to_wishlist:hover, 
            .woocommerce .yith-wcwl-add-button a:hover, 
            .woocommerce .yith-wcwl-add-button a.add_to_wishlist:hover, 
            .woocommerce .yith-wcwl-wishlistaddedbrowse a:hover, 
            .woocommerce .yith-wcwl-wishlistaddedbrowse a.add_to_wishlist:hover, 
            .woocommerce .yith-wcwl-wishlistexistsbrowse a:hover, 
            .woocommerce .yith-wcwl-wishlistexistsbrowse a.add_to_wishlist:hover,
            .woocommerce .yith-wcwl-add-to-wishlist .ajax-loading,
            .woocommerce.compare-button:hover, 
            .woocommerce .yith-wcwl-add-button:hover, 
            .woocommerce .yith-wcwl-wishlistaddedbrowse:hover, 
            .woocommerce .yith-wcwl-wishlistexistsbrowse:hover,
            .woocommerce ul.shop-products .added_to_cart:hover, 
            .woocommerce ul.shop-products .button:hover, 
            .woocommerce ul.shop-products .product-quick-view:hover,
            .woocommerce mark, .woocommerce .mark,
            .btn-accent,
            .woocommerce #respond input#submit:hover, 
            .woocommerce a.button:hover, 
            .woocommerce button.button:hover, 
            .woocommerce input.button:hover, 
            .woocommerce #respond input#submit.alt:hover, 
            .woocommerce a.button.alt:hover, 
            .woocommerce button.button.alt:hover, 
            .woocommerce input.button.alt:hover,
            .woocommerce .woocommerce-pagination .page-numbers.current::before, 
            .woocommerce .woocommerce-pagination .page-numbers.current::after, 
            .pagination .page-numbers.current::before, 
            .pagination .page-numbers.current::after,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
            .woocommerce .gridlist-toggle li a:hover, 
            .woocommerce .gridlist-toggle li a.active,
            .woocommerce span.onsale,
            .btn-default:hover, .btn-default:focus, .btn-default:active,
            .widget_rss ul li:hover::after, 
            .widget_recent_comments ul li:hover::after, 
            .widget_recent_entries ul li:hover::after,
            
            .owl-carousel-kt .owl-pagination .owl-page.active, 
            .owl-carousel-kt .owl-pagination .owl-page:hover,
            #header-content-mobile .header-mobile-tools a.mobile-cart span,
            #cancel-comment-reply-link:hover,
            body .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar,
            body .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar, 
            body .mCSB_scrollTools .mCSB_dragger:focus .mCSB_dragger_bar, 
            body .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar,
            .woocommerce-category-products-tab ul.block-heading-tabs li a::before, 
            .woocommerce-category-products-tab ul.block-heading-tabs li a::after,
            .menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li a span::after,
            #back-to-top,
            .woocommerce-page div.product .cart .single_add_to_cart_button:hover, 
            .woocommerce div.product .cart .single_add_to_cart_button:hover,
            .woocommerce-page div.product .cart .single_add_to_cart_button:focus,
            .woocommerce div.product .cart .single_add_to_cart_button:focus,

            .widget_nav_menu ul li a:hover::after, 
            .widget_pages ul li a:hover::after, 
            .widget_product_categories ul li a:hover::after, 
            .widget_categories ul li a:hover::after, 
            .widget_archive ul li a:hover::after, 
            .widget_meta ul li a:hover::after, 
            .yith-woocompare-widget ul li a:hover::after,
            .kt-skill-wrapper .kt-skill-item-wrapper .kt-skill-bg-accent .kt-skill-bar,
            #main-nav-tool li.mini-cart > a span,
            #footer-area h3.widget-title:after,
            #search-fullwidth .searchform .postform,
            #footer-area h3.widget-title:before,
            .readmore-link:before,
            .readmore-link:after
            {
                background-color: <?php echo $accent; ?>;
            }
            
            .woocommerce #respond input#submit:hover, 
            .woocommerce a.button:hover, 
            .woocommerce button.button:hover, 
            .woocommerce input.button:hover, 
            .woocommerce #respond input#submit.alt:hover, 
            .woocommerce a.button.alt:hover, 
            .woocommerce button.button.alt:hover, 
            .woocommerce input.button.alt:hover,

            .woocommerce-page div.product .cart .single_add_to_cart_button:hover, 
            .woocommerce div.product .cart .single_add_to_cart_button:hover,
            .woocommerce-page div.product .cart .single_add_to_cart_button:focus,
            .woocommerce div.product .cart .single_add_to_cart_button:focus,
            
            .owl-carousel-kt.carousel-dark .owl-buttons > div:hover,
            .owl-carousel-kt.carousel-light .owl-buttons > div:hover,
            div.swatch-wrapper.selected, div.swatch-wrapper:hover,
            .btn-accent,
            .woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-slide.slick-current,
            .woocommerce .product-detail-thumbarea.slick-carousel .single-product-main-thumbnails .slick-slide.slick-current,
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover, 
            .woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus,
            .woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:hover, 
            .woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:focus,
            .single-product-quickview .single-product-quickview-images .slick-arrow:hover, 
            .single-product-quickview .single-product-quickview-images .slick-arrow:focus,
            .post-single .tags-links a:hover, 
            .post-single .tags-links a:focus,
            blockquote, .blockquote,
            .comment-actions a:hover, 
            .comment-actions a:focus,
            .woocommerce .gridlist-toggle li a:hover, 
            .woocommerce .gridlist-toggle li a.active,
            .btn-default:hover, .btn-default:focus, .btn-default:active,
            .menu-bars-outer .menu-bars-items,
            .mini-cart .shopping-bag-wrapper{
                border-color: <?php echo $accent; ?>;
            }

            .menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li.active span, 
            .menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li a:hover span, 
            .menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li a:focus span{
                box-shadow: 0 0 0 1px <?php echo $accent; ?> inset;
                -webkit-box-shadow: 0 0 0 1px <?php echo $accent; ?> inset;
                -moz-box-shadow: 0 0 0 1px <?php echo $accent; ?> inset;
                -ms-box-shadow: 0 0 0 1px <?php echo $accent; ?> inset;
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
    </style>
    <?php
}
add_action('wp_head', 'kt_setting_script');
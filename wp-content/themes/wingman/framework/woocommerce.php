<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Enable support for woocommerce after setip theme
 *
 */
add_action( 'after_setup_theme', 'kt_woocommerce_theme_setup' );
if ( ! function_exists( 'kt_woocommerce_theme_setup' ) ):
    function kt_woocommerce_theme_setup() {
        /**
         * Enable support for woocommerce
         */
        add_theme_support( 'woocommerce' );
    }
endif;

function kt_disable_woocommerce_enable_setup_wizard(){
    return false;
}
add_filter('woocommerce_enable_setup_wizard', 'kt_disable_woocommerce_enable_setup_wizard');


/**
 * Add custom style to woocommerce
 *
 */
function kt_wp_enqueue_scripts(){
    wp_enqueue_style( 'kt-woocommerce', KT_THEME_CSS . 'woocommerce.css' );
    wp_enqueue_script( 'kt-woocommerce', KT_THEME_JS . 'woocommerce.js', array( 'jquery', 'jquery-ui-accordion', 'jquery-ui-tabs' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'kt_wp_enqueue_scripts' );



/**
 * Theme Custom CSS
 *
 * @since       1.0
 * @return      void
 * @access      public
 */
function kt_setting_script_woocommerce() {
    $css = '';
    $accent = kt_option('styling_accent', '#82c14f');
    if( $accent !='#82c14f' ){
        $selections_color = array(
            '.woocommerce p.stars a:hover',
            '.bag-products .bag-product .bag-product-title a:hover',
            '.woocommerce .widget_price_filter .price_slider_amount .price_label span',
            '.woocommerce table.cart tbody td.product-name a:hover',
            '.woocommerce table.cart tbody td.product-name a:focus',
            '.widget_layered_nav ul li a:hover',

            '.woocommerce .woocommerce-pagination .page-numbers a:hover',
            '.woocommerce .woocommerce-pagination .page-numbers a:focus ',
            '.woocommerce .woocommerce-pagination .page-numbers.current a',
            '.yith-woocompare-widget ul li a:hover',
            '.yith-woocompare-widget ul li a:focus',
            '.woocommerce ul.product_list_widget li a:hover',
            '.woocommerce ul.shop-products h3 a:hover',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover:before',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus:before',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover:before',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus:before',
            '.woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:hover',
            '.woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:focus',
            '.woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:hover:before',
            '.woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:focus:before',
            '.single-product-quickview .single-product-quickview-images .slick-arrow:hover:before',
            '.single-product-quickview .single-product-quickview-images .slick-arrow:focus:before',
            '.woocommerce .woocommerce-per-page:after',
            '.woocommerce .woocommerce-ordering:after',
            '.woocommerce .woocommerce-message .button.wc-forward:hover',
            '.woocommerce-page .woocommerce-message .button.wc-forward:hover',
            '.woocommerce-page div.product .product_meta > span a:hover',
            '.woocommerce div.product .product_meta > span a:hover',
            '.woocommerce .star-rating span:before',
            '.woocommerce .widget_price_filter .price_slider_amount .button:hover',
            '.woocommerce .widget_price_filter .price_slider_amount .button:focus',
        );
        $css .= implode($selections_color, ',').'{color: '.$accent.';}';
        $selections_bg = array(
            '.woocommerce.compare-button a:hover',
            '.woocommerce.compare-button a.add_to_wishlist:hover',
            '.woocommerce .yith-wcwl-add-button a:hover',
            '.woocommerce .yith-wcwl-add-button a.add_to_wishlist:hover',
            '.woocommerce .yith-wcwl-wishlistaddedbrowse a:hover',
            '.woocommerce .yith-wcwl-wishlistaddedbrowse a.add_to_wishlist:hover',
            '.woocommerce .yith-wcwl-wishlistexistsbrowse a:hover',
            '.woocommerce .yith-wcwl-wishlistexistsbrowse a.add_to_wishlist:hover',
            '.woocommerce .yith-wcwl-add-to-wishlist .ajax-loading',
            '.woocommerce.compare-button:hover',
            '.woocommerce .yith-wcwl-add-button:hover',
            '.woocommerce .yith-wcwl-wishlistaddedbrowse:hover',
            '.woocommerce .yith-wcwl-wishlistexistsbrowse:hover',
            '.woocommerce ul.shop-products .added_to_cart:hover',
            '.woocommerce ul.shop-products .button:hover',
            '.woocommerce ul.shop-products .product-quick-view:hover',
            '.woocommerce mark, .woocommerce .mark',
            '.woocommerce #respond input#submit:hover',
            '.woocommerce a.button:hover',
            '.woocommerce button.button:hover',
            '.woocommerce input.button:hover',
            '.woocommerce #respond input#submit.alt:hover',
            '.woocommerce a.button.alt:hover',
            '.woocommerce button.button.alt:hover',
            '.woocommerce input.button.alt:hover',
            '.woocommerce .woocommerce-pagination .page-numbers.current:before',
            '.woocommerce .woocommerce-pagination .page-numbers.current:after',
            '.woocommerce .widget_price_filter .ui-slider .ui-slider-range',
            '.woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
            '.woocommerce .gridlist-toggle li a:hover',
            '.woocommerce .gridlist-toggle li a.active',
            '.woocommerce span.onsale',
            '.woocommerce-page div.product .cart .single_add_to_cart_button:hover',
            '.woocommerce div.product .cart .single_add_to_cart_button:hover',
            '.woocommerce-page div.product .cart .single_add_to_cart_button:focus',
            '.woocommerce div.product .cart .single_add_to_cart_button:focus',
            'body .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar',
            'body .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar',
            'body .mCSB_scrollTools .mCSB_dragger:focus .mCSB_dragger_bar',
            'body .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar',
            '.woocommerce-category-products-tab ul.block-heading-tabs li a:before',
            '.woocommerce-category-products-tab ul.block-heading-tabs li a:after',
            '#header-content-mobile .header-mobile-tools a.mobile-cart span',
            '.menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li a span:after',
            '.yith-woocompare-widget ul li a:hover:after',
        );
        $css .= implode($selections_bg, ',').'{background: '.$accent.';}';


        $selections_border = array(
            '.woocommerce #respond input#submit:hover',
            '.woocommerce a.button:hover',
            '.woocommerce button.button:hover',
            '.woocommerce input.button:hover',
            '.woocommerce #respond input#submit.alt:hover',
            '.woocommerce a.button.alt:hover',
            '.woocommerce button.button.alt:hover',
            '.woocommerce input.button.alt:hover',

            '.woocommerce-page div.product .cart .single_add_to_cart_button:hover',
            '.woocommerce div.product .cart .single_add_to_cart_button:hover',
            '.woocommerce-page div.product .cart .single_add_to_cart_button:focus',
            '.woocommerce div.product .cart .single_add_to_cart_button:focus',
            'div.swatch-wrapper.selected',
            'div.swatch-wrapper:hover',
            '.woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-slide.slick-current',
            '.woocommerce .product-detail-thumbarea.slick-carousel .single-product-main-thumbnails .slick-slide.slick-current',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:hover',
            '.woocommerce .product-detail-thumbarea .single-product-main-images .slick-arrow:focus',
            '.woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:hover',
            '.woocommerce .product-detail-thumbarea .single-product-main-thumbnails .slick-arrow:focus',
            '.single-product-quickview .single-product-quickview-images .slick-arrow:hover',
            '.single-product-quickview .single-product-quickview-images .slick-arrow:focus',
            '.woocommerce .gridlist-toggle li a:hover',
            '.woocommerce .gridlist-toggle li a.active',
            '.mini-cart .shopping-bag-wrapper'
        );
        $css .= implode($selections_border, ',').'{border-color: '.$accent.';}';



        $selections_shadow = array(
            '.menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li.active span',
            '.menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li a:hover span',
            '.menu-bars-outer .menu-bars-items .menu-bars-item.menu-bars-currency li a:focus span'
        );
        $css .= sprintf(
            '%1$s{box-shadow: 0 0 0 1px %2$s inset;-webkit-box-shadow: 0 0 0 1px %2$s inset;-moz-box-shadow: 0 0 0 1px %2$s inset; }',
            implode($selections_shadow, ','),
            $accent
        );

    }

    wp_add_inline_style( 'kt-woocommerce', $css );
}
add_action('wp_enqueue_scripts', 'kt_setting_script_woocommerce');


/**
 * Define image sizes
 *
 *
 */
function kt_woocommerce_set_option() {
    global $pagenow;

    if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
        return;
    }

    $catalog = array('width' => '500','height' => '555', 'crop' => 1 );
    $thumbnail = array('width' => '200', 'height' => '250', 'crop' => 1 );
    $single = array( 'width' => '800','height' => '1050', 'crop' => 1);
    $swatches = array( 'width' => '30','height' => '30', 'crop' => 1);

    // Image sizes
    update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
    update_option( 'shop_single_image_size', $single ); 		// Single product image
    update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
    update_option( 'swatches_image_size', $swatches ); 	        // Image swatches thumbs

}
add_action( 'after_switch_theme', 'kt_woocommerce_set_option', 1 );

/**
 *
 * Set the number of products per page when the customer
 * changes the amount in the drop down.
 *
 */

function kt_products_per_page_action() {
    if ( isset( $_REQUEST['per_page'] ) ) :
        WC()->session->set( 'kt_products_per_page', intval( $_REQUEST['per_page'] ) );
    endif;
}
add_action( 'init', 'kt_products_per_page_action');

/**
 * Per page hook.
 *
 * Return the number of products per page to the hook
 *
 * @return int Products per page.
 *
 *
 */
function kt_loop_shop_per_page( $number = 12 ){

    if ( isset( $_REQUEST['per_page'] ) ) :
        $num = intval( $_REQUEST['per_page'] );
    elseif ( WC()->session->__isset( 'kt_products_per_page' ) ) :
        $num = intval( WC()->session->__get( 'kt_products_per_page' ) );
    else :
        $num = intval( kt_option('loop_shop_per_page', 12) );
        if( $num <=0 ){
            $num = $number;
        }
    endif;

    return $num;

}
add_filter('loop_shop_per_page', 'kt_loop_shop_per_page', 999 );


/**
 * Posts per page.
 *
 * Set the number of posts per page on a hard way, build in fix for many themes who override the offical loop_shop_per_page filter.
 *
 *
 * @param	object 	$q		Existing query object.
 * @param	object	$class	Class object.
 * @return 	object 			Modified query object.
 */
function kt_woocommerce_product_query( $q, $class ) {

    if ( function_exists( 'woocommerce_products_will_display' ) && woocommerce_products_will_display() && $q->is_main_query() ) :
        $q->set( 'posts_per_page', kt_loop_shop_per_page(12) );
    endif;

}
add_action( 'woocommerce_product_query', 'kt_woocommerce_product_query', 50, 2 );


/**
 * Display drop down.
 *
 * Display the drop down front end to the user to choose
 * the number of products per page.
 *
 */
function kt_products_per_page_dropdown() {

    $per_page =  kt_option('loop_shop_per_page');
    $cols =  kt_option('shop_gird_cols');

    if(isset($_REQUEST['cols'])){
        $cols = $_REQUEST['cols'];
    }

    $products_per_page = sprintf('%s %s %s -1', $per_page, $per_page + $cols*2,$per_page + $cols*3);

    // Set the products per page options (e.g. 4, 8, 12)
    $products_per_page_options = explode( ' ', apply_filters( 'kt_products_per_page', $products_per_page ) );

    // Only show on product categories
    if ( ! woocommerce_products_will_display() ) :
        return;
    endif;

    $current_per_page = kt_loop_shop_per_page();
    do_action( 'woo_before_products_per_page_form' );

    ?><form method="GET" class="woocommerce-per-page">
    <select name="per_page" onchange="this.form.submit()">
        <?php foreach( $products_per_page_options as $key => $value ) : ?>
            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $current_per_page); ?>><?php
                $text = apply_filters( 'woo_products_per_page_text', esc_html__( '%s item/pages', 'wingman' ), $value );
                esc_html( printf( $text, $value == -1 ? esc_html__( 'All', 'wingman' ) : $value ) ); // Set to 'All' when value is -1
                ?></option>
        <?php endforeach; ?>
    </select>
    <?php

    // Keep query string vars intact
    foreach ( $_GET as $key => $val ) :
        if ( 'per_page' === $key || 'submit' === $key ) :
            continue;
        endif;
        if ( is_array( $val ) ) :
            foreach( $val as $inner_val ) :
                ?><input type="hidden" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( $inner_val ); ?>" /><?php
            endforeach;
        else :
            ?><input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" /><?php
        endif;
    endforeach;
    do_action( 'woo_after_products_per_page' );
    ?></form><?php
    do_action( 'woo_after_products_per_page_form' );
}
add_action( 'woocommerce_before_shop_loop', 'kt_products_per_page_dropdown', 25 );



/**
 * Display Gird List toogle
 *
 *
 */

function kt_woocommerce_gridlist_toggle(){ ?>
    <?php $gridlist = apply_filters('woocommerce_gridlist_toggle', kt_get_gridlist_toggle()) ?>
    <ul class="gridlist-toggle hidden-xs clearfix">
        <li>
            <a class="list<?php if($gridlist == 'list'){ ?> active<?php } ?>" data-placement="top" data-toggle="tooltip" href="#" title="<?php _e('List view', 'wingman') ?>" data-layout="list" data-remove="grid">
                <span class="style-toggle"><span></span><span></span><span></span><span></span></span>
            </a>
        </li>
        <li>
            <a class="grid<?php if($gridlist == 'grid'){ ?> active<?php } ?>" data-placement="top" data-toggle="tooltip" href="#" title="<?php _e('Grid view', 'wingman') ?>" data-layout="grid" data-remove="list">
                <span class="style-toggle"><span></span><span></span><span></span><span></span></span>
            </a>
        </li>
    </ul>
<?php }
add_action( 'woocommerce_before_shop_loop', 'kt_woocommerce_gridlist_toggle', 40);


/**
 *
 * Ajax Update posts layout
 *
 *
 */
function kt_frontend_update_posts_layout(){
    WC()->session->set( 'products_layout', $_REQUEST['layout']);
}

add_action( 'wp_ajax_frontend_update_posts_layout', 'kt_frontend_update_posts_layout' );
add_action( 'wp_ajax_nopriv_frontend_update_posts_layout', 'kt_frontend_update_posts_layout' );

/**
 * Get Grid or List layout.
 *
 * Return the layout of products
 *
 * @return string layout of products.
 *
 *
 */
function kt_get_gridlist_toggle( $layout = 'grid' ){
    if(isset($_REQUEST['view'])){
        return $_REQUEST['view'];
    }elseif ( WC()->session->__isset( 'products_layout' ) ) {
        return WC()->session->__get( 'products_layout' );
    }else{
        return kt_option('shop_products_layout', $layout);
    }
}


add_filter( 'woocommerce_product_loop_start', 'kt_woocommerce_product_loop_start_callback' );
function kt_woocommerce_product_loop_start_callback($classes){
    if(is_product_category() || is_shop() || is_product_tag()){
        $products_layout = kt_get_gridlist_toggle();
        $classes .= ' '.$products_layout;
    }
    return $classes;
}



/**
 * Change placeholder for woocommerce
 *
 */
add_filter('woocommerce_placeholder_img_src', 'kt_woocommerce_placeholder_img_src');

function kt_woocommerce_placeholder_img_src( $src ) {
    return KT_THEME_IMG . 'placeholder.png';
}


/**
 * remove WC breadcrumb
 *
 */
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20 );



/**
 * Woocommerce cart in header
 *
 * @since 1.0
 */
function kt_woocommerce_get_cart( $wrapper = true ){
    $output = '';
    if ( kt_is_wc() ) {
        $cart_total = WC()->cart->get_cart_total();
        $cart_count = WC()->cart->cart_contents_count;
        if( $wrapper == true ){
            $output .= '<li class="mini-cart">';
        }
        $output .= '<a href="'.WC()->cart->get_cart_url().'">';
        $output .= '<i class="fa fa-shopping-cart"></i>';
        $output .= '<span class="mini-cart-total">'.$cart_count.'</span>';
        $output .= '</a>';
        $output .= '<div class="shopping-bag woocommerce">';
        $output .= '<div class="shopping-bag-wrapper ">';
        $output .= '<div class="shopping-bag-content">';
        if ( sizeof(WC()->cart->cart_contents)>0 ) {
            $output .= '<div class="cart-title">'.esc_html__( 'Recently added item(s)','wingman' ).'</div>';
            $output .= '<div class="bag-products mCustomScrollbar">';
            $output .= '<div class="bag-products-content">';
            foreach (WC()->cart->cart_contents as $cart_item_key => $cart_item) {
                $bag_product = $cart_item['data'];

                if ($bag_product->exists() && $cart_item['quantity']>0) {
                    $output .= '<div class="bag-product clearfix">';
                    $output .= '<figure><a class="bag-product-img" href="'.get_permalink($cart_item['product_id']).'">'.$bag_product->get_image().'</a></figure>';
                    $output .= '<div class="bag-product-details">';
                    $output .= '<h3 class="bag-product-title"><a href="'.get_permalink($cart_item['product_id']).'">' . apply_filters('woocommerce_cart_widget_product_title', $bag_product->get_title(), $bag_product) . '</a></h3>';

                    $output .= WC()->cart->get_item_data( $cart_item );

                    $output .= '<div class="bag-product-price">'.$cart_item['quantity'].'x'.wc_price($bag_product->get_price()).'</div>';

                    $output .= '</div>';
                    $output .= apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="#" data-itemkey="'.$cart_item_key.'" data-id="'.$cart_item['product_id'].'" class="remove" title="%s"></a>', esc_html__('Remove this item', 'woocommerce') ), $cart_item_key );

                    $output .= '</div>';
                }
            }
            $output .= '</div>';
            $output .= '</div>';
        }else{
            $output .=  "<p class='cart_block_no_products'>".esc_html__('Your cart is currently empty.', 'wingman')."</p>";
        }

        if ( sizeof(WC()->cart->cart_contents)>0 ) {
            $output .= '<div class="bag-total"><strong>'.esc_html__('Subtotal: ', 'wingman').'</strong>'.$cart_total.'</div><!-- .bag-total -->';
            $output .= '<div class="bag-buttons">';
            $output .= '<div class="bag-buttons-content clearfix">';
            $output .= '<span><a href="'.esc_url( WC()->cart->get_cart_url() ).'" class="btn btn-default btn-animation"><span>'.esc_html__('View cart', 'wingman').'<i class="fa fa-long-arrow-right"></i></span></a></span>';
            $output .= '<span><a href="'.esc_url( WC()->cart->get_checkout_url() ).'" class="btn btn-default btn-animation"><span>'.esc_html__('Checkout', 'wingman').'<i class="fa fa-long-arrow-right"></i></span></a></span>';
            $output .= '</div><!-- .bag-buttons -->';
            $output .= '</div><!-- .bag-buttons -->';
        }

        $output .= '</div><!-- .shopping-bag-content -->';
        $output .= '</div><!-- .shopping-bag-wrapper -->';
        $output .= '</div><!-- .shopping-bag -->';
        if( $wrapper == true ){
            $output .= '</li>';
        }
    }
    return $output;
}


/**
 * Woocommerce cart in header
 *
 * @since 1.0
 */
function kt_woocommerce_get_cart_mobile( $wrapper = true ){
    $output = '';
    if ( kt_is_wc() ) {
        $cart_count = WC()->cart->cart_contents_count;
        if( $wrapper == true ){
            $output .= '<a href="'.WC()->cart->get_cart_url().'" class="mobile-cart">';
        }
        $output .= '<i class="fa fa-shopping-cart"></i>';
        $output .= '<span class="mobile-cart-total">'.$cart_count.'</span>';

        if( $wrapper == true ){
            $output .= '</a>';
        }
    }
    return $output;
}


/**
 * Woocommerce replate cart in header
 *
 */
function kt_woocommerce_header_add_to_cart_fragment( $fragments ) {
    $fragments['.mini-cart'] = kt_woocommerce_get_cart();
    $fragments['.mobile-cart'] = kt_woocommerce_get_cart_mobile();
    return $fragments;
}
add_filter('add_to_cart_fragments', 'kt_woocommerce_header_add_to_cart_fragment');



/**
 * Woocommerce replace before main content and after main content
 *
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'kt_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'kt_wrapper_end', 10);

function kt_wrapper_start() {
    echo '<div class="content-wrapper"><div class="container wc-container">';
}

function kt_wrapper_end() {
    echo '</div><!-- .container --></div>';
}

/**
 * Change columns of shop
 *
 */

add_filter( 'loop_shop_columns', 'kt_woo_shop_columns' );
function kt_woo_shop_columns( $columns ) {
    $cols =  kt_option('shop_gird_cols');
    if(isset($_REQUEST['cols'])){
        $cols = $_REQUEST['cols'];
    }


    $cols = intval(  $cols );
    if( $cols <= 0 ){
        $layout = kt_option('shop_sidebar','full');
        if($layout == 'left' || $layout == 'right'){
            return 3;
        }else{
            return 4;
        }
    }
    return $cols ;
}


/**
 * Change layout of single product
 *
 */
add_filter( 'single_product_layout', 'kt_single_product_layout' );
function kt_single_product_layout( $columns ) {
    $layout = kt_option('product_sidebar', 'full');
    return $layout;
}

/**
 * Change layout of carousel single product
 *
 */
add_filter( 'woocommerce_single_product_carousel', 'kt_woocommerce_single_product_carousel_callback' );
function kt_woocommerce_single_product_carousel_callback( $columns ) {

    $sidebar = kt_get_woo_sidebar();

    if( isset($sidebar['sidebar']) && ($sidebar['sidebar'] == 'left' || $sidebar['sidebar'] == 'right')){
        return '{"pagination": false, "navigation": true, "desktop": 3, "desktopsmall" : 2, "tablet" : 2, "mobile" : 1, "navigation_pos": "top"}';
    }else{
        return '{"pagination": false, "navigation": true, "desktop": 4, "desktopsmall" : 3, "tablet" : 2, "mobile" : 1, "navigation_pos": "top"}';
    }
}

/**
 * Remove tab heading
 *
 */
add_filter('woocommerce_product_additional_information_heading', '__return_false');
add_filter('woocommerce_product_description_heading', '__return_false');

/**
 * Layout for thumbarea
 *
 * If have sidebar use col 12
 * else use 5
 *
 */

add_filter( 'woocommerce_single_product_thumb_area', 'kt_woocommerce_single_product_thumb_area_callback' );
function kt_woocommerce_single_product_thumb_area_callback(){
    $sidebar = kt_get_woo_sidebar();
    if(isset($sidebar['sidebar']) && ($sidebar['sidebar'] == 'left' || $sidebar['sidebar'] == 'right')){
        return 'col-xs-12 col-sm-5';
    }else{
        return 'col-xs-12 col-sm-6';
    }
}

/**
 * Layout for summary area
 *
 * If have sidebar use col 12
 * else use 7
 *
 */

add_filter( 'woocommerce_single_product_summary_area', 'kt_woocommerce_single_product_summary_area_callback' );
function kt_woocommerce_single_product_summary_area_callback(){
    $sidebar = kt_get_woo_sidebar();
    if(isset($sidebar['sidebar']) && ($sidebar['sidebar'] == 'left' || $sidebar['sidebar'] == 'right')){
        return 'col-xs-12 col-sm-7';
    }else{
        return 'col-xs-12 col-sm-6';
    }
}




/**
 * Change hook of archive-product.php
 *
 */

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

add_action( 'woocommerce_shop_loop_item_before_image', 'woocommerce_show_product_loop_sale_flash', 10);
add_action( 'woocommerce_shop_loop_item_before_image', 'woocommerce_template_loop_add_to_cart', 10);


add_action( 'woocommerce_shop_tool_list_before', 'woocommerce_template_loop_add_to_cart', 5);

function kt_woocommerce_sale_flash($text, $post, $product){
    $text = '<span class="onsale">' . esc_html__( 'Sale', 'wingman' ) . '</span>';
    return $text;
}
add_filter('woocommerce_sale_flash', 'kt_woocommerce_sale_flash', 20, 3);


function kt_woocommerce_add_archive_tool(){
    $count = 1;
    if(class_exists('YITH_WCWL_UI')){
        $count++;
    }
    if(defined( 'YITH_WOOCOMPARE' )){
        $count++;
    }
    ?>
    <div class="product-image-tool tool-<?php echo esc_attr($count); ?>">
        <?php
        if(class_exists('YITH_WCWL_UI')){
            echo do_shortcode('<div class="tool-inner" data-toggle="tooltip" data-placement="top" title="'. esc_html__('wishlist','wingman').'">[yith_wcwl_add_to_wishlist]</div>');
        }
        printf(
            '<div class="tool-inner" data-toggle="tooltip" data-placement="top" title="'. esc_html__('Quick View','wingman').'"><a href="#" class="product-quick-view" data-id="%s">%s</a></div>',
            get_the_ID(),
            esc_html__('Quick view', 'wingman')
        );
        if(defined( 'YITH_WOOCOMPARE' )){
            echo do_shortcode('<div class="tool-inner" data-toggle="tooltip" data-placement="top" title="'. esc_html__('Compare','wingman').'">[yith_compare_button]</div>');
        }
        ?>
    </div>
    <?php
}

add_action( 'woocommerce_shop_tool_list', 'kt_woocommerce_add_archive_tool', 10);
add_action( 'woocommerce_shop_loop_item_after_image', 'kt_woocommerce_add_archive_tool', 10);



/**
 * Change hook of single-product.php
 *
 */

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );


// Remove compare product
if(defined( 'YITH_WOOCOMPARE' )){
    global $yith_woocompare;
    remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link' ), 35 );
}

// Remove wishlist product
add_filter('yith_wcwl_positions', 'yith_wcwl_positions_callback');
function yith_wcwl_positions_callback($positions){
    $positions['add-to-cart']['hook'] = '';
    return $positions;
}

add_action( 'woocommerce_after_add_to_cart_button', 'kt_woocommerce_shop_loop_item_action_action_product', 50);
function kt_woocommerce_shop_loop_item_action_action_product(){
    if(class_exists('YITH_WCWL_UI') || defined( 'YITH_WOOCOMPARE' )){
        echo "<div class='functional-buttons-product clearfix'>";
        echo "<div class='functional-buttons'>";
        if(class_exists('YITH_WCWL_UI')){
            echo '<div data-placement="top" data-toggle="tooltip" data-original-title="wishlist">'.do_shortcode('[yith_wcwl_add_to_wishlist]').'</div>';
        }
        if(defined( 'YITH_WOOCOMPARE' )){
            echo '<div data-placement="top" data-toggle="tooltip" data-original-title="Compare">'.do_shortcode('[yith_compare_button]').'</div>';
        }
        echo "</div>";
        echo "</div>";
    }
}



remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );



if ( ! function_exists( 'woocommerce_content' ) ) {

    /**
     * Output WooCommerce content.
     *
     * This function is only used in the optional 'woocommerce.php' template
     * which people can add to their themes to add basic woocommerce support
     * without hooks or modifying core templates.
     *
     */
    function woocommerce_content() {

        if ( is_singular( 'product' ) ) {

            while ( have_posts() ) : the_post();

                wc_get_template_part( 'content', 'single-product' );

            endwhile;

        } else { ?>

            <?php do_action( 'woocommerce_archive_description' ); ?>

            <?php if ( have_posts() ) : ?>
                <div class="woocommerce-before-shop clearfix">
                    <?php do_action('woocommerce_before_shop_loop'); ?>
                </div>

                <?php woocommerce_product_loop_start(); ?>

                <?php woocommerce_product_subcategories(); ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php wc_get_template_part( 'content', 'product' ); ?>

                <?php endwhile; // end of the loop. ?>


                <?php woocommerce_product_loop_end(); ?>

                <div class="woocommerce-end-shop clearfix">
                    <?php do_action('woocommerce_after_shop_loop'); ?>
                </div>

            <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

                <?php wc_get_template( 'loop/no-products-found.php' ); ?>

            <?php endif;

        }
    }
}

function kt_woocommerce_show_product_loop_new_flash(){
    global $post;

    $time_new = kt_option('time_product_new', 30);

    $now = strtotime( date("Y-m-d H:i:s") );
    $post_date = strtotime( $post->post_date );
    $num_day = (int)(($now - $post_date)/(3600*24));
    if( $num_day < $time_new ){
        echo "<span class='kt_new'>".esc_html__( 'New','wingman' )."</span>";
    }
}
add_action( 'woocommerce_shop_loop_item_before_image', 'kt_woocommerce_show_product_loop_new_flash', 5 );
add_action( 'woocommerce_before_single_product_summary', 'kt_woocommerce_show_product_loop_new_flash', 5 );


add_action( 'woocommerce_single_product_summary', 'kt_share_box_woo', 35 );
if( ! function_exists( 'kt_share_box_woo' ) ){
    function kt_share_box_woo($post_id = null, $style = "", $class = ''){
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $link = urlencode(get_permalink($post_id));
        $title = urlencode(addslashes(get_the_title($post_id)));
        $excerpt = urlencode(get_the_excerpt());
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');

        $html = '';

        $social_share = kt_option('social_share');

        foreach($social_share as $key => $val){
            if($val){
                if($key == 'facebook'){
                    // Facebook
                    $html .= '<a class="'.$style.'" href="#" onclick="popUp=window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $title . '&amp;p[url]=' . $link.'\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');popUp.focus();return false;">';
                    $html .= '<i class="fa fa-facebook"></i>';
                    $html .= '</a>';
                }elseif($key == 'twitter'){
                    // Twitter
                    $html .= '<a class="'.$style.'" href="#" onclick="popUp=window.open(\'http://twitter.com/home?status=' . $link . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                    $html .= '<i class="fa fa-twitter"></i>';
                    $html .= '</a>';
                }elseif($key == 'google_plus'){
                    // Google plus
                    $html .= '<a class="'.$style.'" href="#" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . $link . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-google-plus"></i>';
                    $html .= "</a>";
                }elseif($key == 'pinterest'){
                    // Pinterest
                    $html .= '<a class="share_link" href="#" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . $link . '&amp;description=' . $title . '&amp;media=' . urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-pinterest"></i>';
                    $html .= "</a>";
                }elseif($key == 'linkedin'){
                    // linkedin
                    $html .= '<a class="'.$style.'" href="#" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . $link . '&amp;title=' . $title. '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-linkedin"></i>';
                    $html .= "</a>";
                }elseif($key == 'tumblr'){
                    // Tumblr
                    $html .= '<a class="'.$style.'" href="#" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . $link . '&amp;name=' . $title . '&amp;description=' . $excerpt . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-tumblr"></i>';
                    $html .= "</a>";
                }elseif($key == 'email'){
                    // Email
                    $html .= '<a class="'.$style.'" href="mailto:?subject='.$title.'&amp;body='.$link.'">';
                    $html .= '<i class="fa fa-envelope-o"></i>';
                    $html .= "</a>";
                }
            }
        }

        if($html){
            printf(
                '<div class="entry-share-box %s">'.esc_html__( 'Share Link: ','wingman' ).'%s</div>',
                $class,
                $html
            );
        }
    }
}


add_action( 'woocommerce_after_shop_loop_item_title', 'kt_template_single_excerpt', 15 );
function kt_template_single_excerpt(){
    global $post;

    if ( ! $post->post_excerpt ) {
        return;
    }
    ?>
    <div class="product-short-description">
        <?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ); ?>
    </div>
    <?php
}


function kt_thumbnail_page_shop(){
    if( is_shop() ){
        $banner = kt_option( 'shop_content_banner' );
        if( $banner ){
            printf( '<div class="shop-thumb">%s</div>', do_shortcode($banner) );
        }
    }
}
add_action( 'woocommerce_archive_description', 'kt_thumbnail_page_shop', 10 );



add_action( 'woocommerce_after_subcategory', 'kt_woocommerce_category_description', 10, 1 );
function kt_woocommerce_category_description( $category ) {
    if ( is_product_category() ) {
        printf('<div class="description product-short-description">%s</div>', $category->description);
    }
}

function kt_woocommerce_pagination_args($args){
    $args['next_text'] = '<i class="fa fa-long-arrow-right"></i>';
    $args['prev_text'] = '<i class="fa fa-long-arrow-left"></i>';

    return $args;
}
add_filter('woocommerce_pagination_args', 'kt_woocommerce_pagination_args');



if (!function_exists('kt_get_woo_sidebar')) {
    /**
     * Get woo sidebar
     *
     * @param null $post_id
     * @return array
     */
    function kt_get_woo_sidebar( $post_id = null )
    {


        $sidebar = array('sidebar_area' => '');
        $requestCustom = false;

        if(isset($_REQUEST['sidebar'])){
            $sidebar['sidebar'] = $_REQUEST['sidebar'];
            $requestCustom = true;
        }

        if(is_product() || $post_id || is_shop()){
            if(is_shop() && !$post_id){
                $post_id = get_option( 'woocommerce_shop_page_id' );
            }
            global $post;
            if(!$post_id) $post_id = $post->ID;


            if(!isset($sidebar['sidebar'])){
                $sidebar['sidebar'] = rwmb_meta('_kt_sidebar', array(), $post_id);
            }
            if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' || $requestCustom){

                if(!$requestCustom){
                    if(is_shop()) {
                        $sidebar['sidebar'] = kt_option('shop_sidebar', 'left');
                    }else{
                        $sidebar['sidebar'] = kt_option('product_sidebar', 'right');
                    }
                }
                if($sidebar['sidebar'] == 'left' ){
                    if(is_shop()){
                        $sidebar['sidebar_area'] = kt_option('shop_sidebar_left', 'shop-widget-area');
                    }else{
                        $sidebar['sidebar_area'] = kt_option('product_sidebar_left', 'shop-widget-area');
                    }
                }elseif($sidebar['sidebar'] == 'right'){
                    if(is_shop()){
                        $sidebar['sidebar_area'] = kt_option('shop_sidebar_right', 'shop-widget-area');
                    }else{
                        $sidebar['sidebar_area'] = kt_option('product_sidebar_right', 'shop-widget-area');
                    }
                }
            }elseif($sidebar['sidebar'] == 'left'){
                $sidebar['sidebar_area'] = rwmb_meta('_kt_left_sidebar', array(), $post_id);
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = rwmb_meta('_kt_right_sidebar', array(), $post_id);
            }



        }elseif( is_product_taxonomy() || is_product_tag()){
            if(!isset($sidebar['sidebar'])) {
                $sidebar['sidebar'] = kt_option('shop_sidebar', 'left');
            }

            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = kt_option('shop_sidebar_left', 'shop-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = kt_option('shop_sidebar_right', 'shop-widget-area');
            }
        }elseif(is_cart()){
            $sidebar['sidebar'] = 'full';
        }elseif(is_page()){
            global $post;
            if(!$post_id) $post_id = $post->ID;

            if(!isset($sidebar['sidebar'])){
                $sidebar['sidebar'] = rwmb_meta('_kt_sidebar', array(), $post_id);
            }

            if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' || $requestCustom){
                if(!$requestCustom){
                    $sidebar['sidebar'] = kt_option('sidebar', 'full');
                }
                if($sidebar['sidebar'] == 'left' ){
                    $sidebar['sidebar_area'] = kt_option('shop_sidebar_left', 'shop-widget-area');
                }elseif($sidebar['sidebar'] == 'right'){
                    $sidebar['sidebar_area'] = kt_option('shop_sidebar_right', 'shop-widget-area');
                }
            }elseif($sidebar['sidebar'] == 'left'){
                $sidebar['sidebar_area'] = rwmb_meta('_kt_left_sidebar', array(), $post_id);
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = rwmb_meta('_kt_right_sidebar', array(), $post_id);
            }
        }
        return apply_filters('kt_wc_sidebar', $sidebar);
    }
}
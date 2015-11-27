<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


function kt_loop_shop_per_page( $number ){
    $num = kt_option('loop_shop_per_page');
    $num =  intval( $num );
    if( $num <=0 ){
        $num = $number;
    }
    return $num;
}
add_filter('loop_shop_per_page', 'kt_loop_shop_per_page' );



/**
 * Sale price Percentage
 */
/*
add_filter( 'woocommerce_sale_price_html', 'kt_woocommerce_sale_price_html', 10, 2 );
function kt_woocommerce_sale_price_html( $price, $product ) {
    $percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
    return $price . sprintf( __('<span class="price-save"> %s</span>', THEME_LANG ), $percentage . '%' );
}
*/

/**
 * Define image sizes
 */
function kt_woocommerce_image_dimensions() {
	global $pagenow;

	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}

  	$catalog = array('width' => '500','height' => '555', 'crop' => 1 );
    $thumbnail = array('width' => '200', 'height' => '250', 'crop' => 1 );
	$single = array( 'width' => '1000','height' => '1200', 'crop' => 1);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}
add_action( 'after_switch_theme', 'kt_woocommerce_image_dimensions', 1 );


/**
 * Change placeholder for woocommerce
 *
 */
add_filter('woocommerce_placeholder_img_src', 'kt_woocommerce_placeholder_img_src');

function kt_woocommerce_placeholder_img_src( $src ) {
	return THEME_IMG . 'placeholder.png';
}


/**
 * Enable support for woocommerce after setip theme
 *
 */
add_action( 'after_setup_theme', 'woocommerce_theme_setup' );
if ( ! function_exists( 'woocommerce_theme_setup' ) ):
    function woocommerce_theme_setup() {
        /**
    	 * Enable support for woocommerce
    	 */
        add_theme_support( 'woocommerce' );
    }
endif;

/**
 * remove WC breadcrumb
 *
 */
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20 );

/**
 * Add custom style to woocommerce
 *
 */

function kt_wp_enqueue_scripts(){
    wp_enqueue_style( 'kt-woocommerce', THEME_CSS . 'woocommerce.css' );
    wp_enqueue_style( 'easyzoom', THEME_CSS . 'easyzoom.css', array());

    wp_enqueue_script( 'easyzoom', THEME_JS . 'easyzoom.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'variations-plugin-script', THEME_JS . 'woo-variations-plugin.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'mCustomScrollbar-script', THEME_JS . 'jquery.mCustomScrollbar.min.js', array( 'jquery' ), null, true );
    
    wp_enqueue_script( 'jquery-ui-accordion' );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'kt-woocommerce', THEME_JS . 'woocommerce.js', array( 'jquery' ), null, true );

}
add_action( 'wp_enqueue_scripts', 'kt_wp_enqueue_scripts' );


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
                $output .= '<div class="cart-title">'.__( 'Recently added item(s)',THEME_LANG ).'</div>';
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
    					$output .= apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="#" data-itemkey="'.$cart_item_key.'" data-id="'.$cart_item['product_id'].'" class="remove" title="%s"></a>', __('Remove this item', 'woocommerce') ), $cart_item_key );

    					$output .= '</div>';
                    }
                }
                $output .= '</div>';
                $output .= '</div>';
            }else{
               $output .=  "<p class='cart_block_no_products'>".__('Your cart is currently empty.', THEME_LANG)."</p>";
            }

            if ( sizeof(WC()->cart->cart_contents)>0 ) {
                $output .= '<div class="bag-total"><strong>'.__('Subtotal: ', THEME_LANG).'</strong>'.$cart_total.'</div><!-- .bag-total -->';
                $output .= '<div class="bag-buttons">';
                $output .= '<div class="bag-buttons-content clearfix">';
                    $output .= '<span><a href="'.esc_url( WC()->cart->get_cart_url() ).'" class="btn btn-default">'.__('View cart', THEME_LANG).'</a></span>';
                    $output .= '<span><a href="'.esc_url( WC()->cart->get_checkout_url() ).'" class="btn btn-default">'.__('Checkout', THEME_LANG).'</a></span>';
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
        $output .= '<span class="icon-bag"></span>';
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
function woocommerce_header_add_to_cart_fragment( $fragments ) {
    $fragments['.mini-cart'] = kt_woocommerce_get_cart();
    $fragments['.mobile-cart'] = kt_woocommerce_get_cart_mobile();
	return $fragments;
}
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');



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
 * Add checkout button to cart page
 *
 */
//add_action('woocommerce_cart_actions', 'woocommerce_button_proceed_to_checkout');


/**
 * Change columns of shop
 *
 */

add_filter( 'loop_shop_columns', 'kt_woo_shop_columns' );
function kt_woo_shop_columns( $columns ) {
    $cols =  kt_option('shop_gird_cols');
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
add_filter( 'woocommerce_single_product_carousel', 'woocommerce_single_product_carousel_callback' );
function woocommerce_single_product_carousel_callback( $columns ) {

    $sidebar = kt_get_woo_sidebar();

    if($sidebar['sidebar'] == 'left' || $sidebar['sidebar'] == 'right'){
        return '[[992,3], [768, 2], [480, 2]]';
    }else{
        return '[[992,4], [768, 3], [480, 2]]';
    }
}

/**
 * Layout for thumbarea
 *
 * If have sidebar use col 12
 * else use 5
 *
 */

add_filter( 'woocommerce_single_product_thumb_area', 'woocommerce_single_product_thumb_area_callback' );
function woocommerce_single_product_thumb_area_callback(){
    $sidebar = kt_get_woo_sidebar();
    if($sidebar['sidebar'] == 'left' || $sidebar['sidebar'] == 'right'){
        return 'col-sm-12 col-md-5';
    }else{
        return 'col-sm-6 col-md-6';
    }
}

/**
 * Layout for summary area
 *
 * If have sidebar use col 12
 * else use 7
 *
 */

add_filter( 'woocommerce_single_product_summary_area', 'woocommerce_single_product_summary_area_callback' );
function woocommerce_single_product_summary_area_callback(){
    $sidebar = kt_get_woo_sidebar();
    if($sidebar['sidebar'] == 'left' || $sidebar['sidebar'] == 'right'){
        return 'col-sm-12 col-md-7';
    }else{
        return 'col-sm-6 col-md-6';
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
add_action( 'woocommerce_shop_tool_list', 'kt_woocommerce_add_archive_tool', 10);
add_action( 'woocommerce_shop_loop_item_after_image', 'kt_woocommerce_add_archive_tool', 10);
function kt_woocommerce_add_archive_tool(){
    if(class_exists('YITH_WCWL_UI')){
        echo do_shortcode('<div class="tool-inner">[yith_wcwl_add_to_wishlist]</div>');
    }
    printf(
        '<div class="tool-inner"><a href="#" class="product-quick-view" data-id="%s">%s</a></div>',
        get_the_ID(),
        __('Quick view', THEME_LANG)
    );
    if(defined( 'YITH_WOOCOMPARE' )){
        echo do_shortcode('<div class="tool-inner">[yith_compare_button]</div>');
    }
}



/**
 * Change hook of single-product.php
 *
 */


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

add_action( 'woocommerce_after_add_to_cart_button', 'woocommerce_shop_loop_item_action_action_product', 50);
function woocommerce_shop_loop_item_action_action_product(){
    if(class_exists('YITH_WCWL_UI') || defined( 'YITH_WOOCOMPARE' )){
        echo "<div class='functional-buttons-product clearfix'>";
        echo "<div class='functional-buttons'>";
        if(class_exists('YITH_WCWL_UI')){
            echo do_shortcode('[yith_wcwl_add_to_wishlist]');
        }
        if(defined( 'YITH_WOOCOMPARE' )){
            echo do_shortcode('[yith_compare_button]');
        }
        echo "</div>";
        echo "</div>";
    }
}




/**
 * Add count products before cart
 *
 */
/*
add_action('woocommerce_before_cart_table', 'kt_woocommerce_before_cart_table', 20);
function kt_woocommerce_before_cart_table( $args ){
    $html = '<h2 class="shopping-cart-title">'. sprintf( __( 'Your shopping cart: <span>(%d items)</span>', THEME_LANG ), WC()->cart->cart_contents_count ) . '</h2>';
	echo $html;
}*/



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

function woocommerce_show_product_loop_new_flash(){
    global $post;

    $time_new = kt_option('time_product_new', 30);

    $now = strtotime( date("Y-m-d H:i:s") );
	$post_date = strtotime( $post->post_date );
	$num_day = (int)(($now - $post_date)/(3600*24));
	if( $num_day < $time_new ){
		echo "<span class='kt_new'>".__( 'New!',THEME_LANG )."</span>";
	}
}
add_action( 'woocommerce_shop_loop_item_before_image', 'woocommerce_show_product_loop_new_flash', 5 );
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_loop_new_flash', 5 );


function woo_product_pagination(){ ?>
    <div class="kt_woo_pagination">
        <?php
            if( !get_previous_post_link('&laquo; %link','<i class="fa fa-angle-left"></i>') ){
                echo '<span><i class="fa fa-angle-left"></i></span>';
            }else{
                previous_post_link('%link','<i class="fa fa-angle-left"></i>');
            }
            
            if( !get_next_post_link('&laquo; %link','<i class="fa fa-angle-right"></i>') ){
                echo '<span><i class="fa fa-angle-right"></i></span>';
            }else{
                next_post_link('%link','<i class="fa fa-angle-right"></i>');
            }
        ?>
    </div>
    <?php
}
//add_action( 'woocommerce_single_product_summary', 'woo_product_pagination', 1 );

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
                '<div class="entry-share-box %s">'.__( 'Share Link: ',THEME_LANG ).'%s</div>',
                $class,
                $html
            );
        }
    }
}


add_action( 'woocommerce_before_shop_loop', 'woocommerce_gridlist_toggle', 40);
function woocommerce_gridlist_toggle(){ ?>
    <?php $gridlist = apply_filters('woocommerce_gridlist_toggle', 'grid') ?>
    <ul class="gridlist-toggle hidden-xs">
        <li><span><?php _e('View as:', THEME_LANG) ?></span></li>
		<li>
			<a <?php if($gridlist == 'lists'){ ?>class="active"<?php } ?> href="#" title="<?php _e('List view', THEME_LANG) ?>" data-layout="lists" data-remove="grid"><i class="fa fa-th-list"></i></a>
		</li>
		<li>
			<a <?php if($gridlist == 'grid'){ ?>class="active"<?php } ?> href="#" title="<?php _e('Grid view', THEME_LANG) ?>" data-layout="grid" data-remove="lists"><i class="fa fa-th-large"></i></a>
		</li>
	</ul>
<?php }

add_filter( 'woocommerce_gridlist_toggle', 'woocommerce_gridlist_toggle_callback' );
function woocommerce_gridlist_toggle_callback(){
    return kt_option('shop_products_layout', 'grid');
}

add_filter( 'woocommerce_product_loop_start', 'woocommerce_product_loop_start_callback' );
function woocommerce_product_loop_start_callback($classes){
    if(is_product_category() || is_shop() || is_product_tag()){
        $products_layout = kt_option('shop_products_layout', 'grid');
        $classes .= ' '.$products_layout;
    }
    
    $effect = kt_option('shop_products_effect', 'center');
    $classes .= ' effect-'.$effect;
    
    return $classes;
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

add_action( 'woocommerce_sale_sountdown_item', 'kt_template_single_excerpt', 10 );
add_action( 'woocommerce_sale_sountdown_item', 'woocommerce_after_shop_loop_item_sale_sale_price', 15, 2 );
function woocommerce_after_shop_loop_item_sale_sale_price($product = false, $post = false){

    $product_id = 0;
    if( is_object( $product ) ){
        $product_id = $product->id;
    }elseif( is_object( $post) ){
        $product_id = $post->ID;
    }else{
        global $post;
        $product_id =  $post->ID;
    }

    if( ! $product_id  ){
        return;
    }

    $cache_key = 'time_sale_price_'.$product_id;
    $cache = wp_cache_get($cache_key);
    if( $cache ){
        echo $cache;
        return;
    }
    // Get variations
    $args = array(
        'post_type'     => 'product_variation',
        'post_status'   => array( 'private', 'publish' ),
        'numberposts'   => -1,
        'orderby'       => 'menu_order',
        'order'         => 'asc',
        'post_parent'   => $product_id
    );
    $variations = get_posts( $args );
    $variation_ids = array();
    if( $variations ){
        foreach ( $variations as $variation ) {
            $variation_ids[]  = $variation->ID;
        }
    }
    $sale_price_dates_to = false;

    if( !empty(  $variation_ids )   ){
        global $wpdb;
        $sale_price_dates_to = $wpdb->get_var( "
            SELECT
            meta_value
            FROM $wpdb->postmeta
            WHERE meta_key = '_sale_price_dates_to' and post_id IN(".join(',',$variation_ids).")
            ORDER BY meta_value DESC
            LIMIT 1
        " );

        if( $sale_price_dates_to !='' ){
            $sale_price_dates_to = date('Y-m-d', $sale_price_dates_to);
        }
    }

    if( !$sale_price_dates_to ){
        $sale_price_dates_to 	= ( $date = get_post_meta( $product_id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
    }

    if($sale_price_dates_to){
        $cache = '<div class="woocommerce-countdown coming-soon clearfix" data-date="'.$sale_price_dates_to.'"></div>';
        wp_cache_add( $cache_key, $cache );
        echo $cache;
    }else{
        wp_cache_delete( $cache_key );
    }
}
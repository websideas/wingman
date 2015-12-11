<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



function wp_ajax_fronted_loadmore_archive_callback(){
    check_ajax_referer( 'ajax_frontend', 'security' );

    $settings = $_POST['settings'];
    $query_vars = (is_array($_POST['queryvars'])) ? $_POST['queryvars'] : json_decode( stripslashes( $_POST['queryvars'] ), true );
    $query_vars['paged'] = intval($_POST['paged']);


    $output = array('settings' => $settings);
    extract($output['settings']);

    $excerpt_length =  intval( $excerpt_length );
    $exl_function = create_function('$n', 'return '.$excerpt_length.';');
    add_filter( 'excerpt_length', $exl_function , 999 );

    $wp_query = new WP_Query( $query_vars );

    if($blog_type == 'grid' || $blog_type == 'masonry'){
        $elementClass[] = 'blog-posts-columns-'.$blog_columns;
        $bootstrapColumn = round( 12 / $blog_columns );
        $bootstrapTabletColumn = round( 12 / $blog_columns_tablet );
        $classes = 'col-xs-12 col-sm-'.$bootstrapTabletColumn.' col-md-' . $bootstrapColumn.' col-lg-' . $bootstrapColumn;
    }

    $blog_atts_posts = array(
        'readmore' => $readmore,
        'show_excerpt' =>  apply_filters('sanitize_boolean', $show_excerpt),
        'show_meta' =>  apply_filters('sanitize_boolean', $show_meta),
        "show_author" => apply_filters('sanitize_boolean', $show_author),
        "show_category" => apply_filters('sanitize_boolean', $show_category),
        "show_comment" => apply_filters('sanitize_boolean', $show_comment),
        "show_date" => apply_filters('sanitize_boolean', $show_date),
        "date_format" => $date_format,
        "show_like_post" => apply_filters('sanitize_boolean', $show_like_post),
        "show_view_number" => apply_filters('sanitize_boolean', $show_view_number),
        'thumbnail_type' => $thumbnail_type,
        'sharebox' => apply_filters('sanitize_boolean', $sharebox),
        "class" => 'loadmore-item',
        "type" => $blog_type,
    );


    if( $blog_type == 'classic' ){
        $path = 'templates/blog/classic/content';
    }elseif( $blog_type == 'zigzag' ){
        $path = 'templates/blog/zigzag/content';
    }elseif( $blog_type =='list' ){
        $path = 'templates/blog/list/content';
    }else{
        $path = 'templates/blog/layout/content';
    }
    
    ob_start();

    $i = ( $query_vars['paged'] - 1 ) * $max_items + 1 ;

    while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $blog_atts = $blog_atts_posts;
        $blog_atts['blog_number'] = $i;
        if($blog_type == 'grid' || $blog_type == 'masonry'){
            echo "<div class='article-post-item ".$classes."'>";
        }

        kt_get_template_part( $path, get_post_format(), $blog_atts);

        if($blog_type == 'grid' || $blog_type == 'masonry'){
            echo "</div><!-- .article-post-item -->";
        }
        $i++;


    endwhile;

    remove_filter('excerpt_length', $exl_function, 999 );

    $output['html'] = ob_get_clean();

    echo json_encode($output);
    die();

}




add_action( 'wp_ajax_fronted_loadmore_archive', 'wp_ajax_fronted_loadmore_archive_callback' );
add_action( 'wp_ajax_nopriv_fronted_loadmore_archive', 'wp_ajax_fronted_loadmore_archive_callback' );

if(!function_exists('putRevSlider')){
    function putRevSlider($data,$putIn = ""){
        if(class_exists( 'RevSlider' )){
            $operations = new RevOperations();
            $arrValues = $operations->getGeneralSettingsValues();
            $includesGlobally = UniteFunctionsRev::getVal($arrValues, "includes_globally","on");
            $strPutIn = UniteFunctionsRev::getVal($arrValues, "pages_for_includes");
            $isPutIn = RevSliderOutput::isPutIn($strPutIn,true);

            if($isPutIn == false && $includesGlobally == "off"){
                $output = new RevSliderOutput();
                $option1Name = "Include RevSlider libraries globally (all pages/posts)";
                $option2Name = "Pages to include RevSlider libraries";
                $output->putErrorMessage(__("If you want to use the PHP function \"putRevSlider\" in your code please make sure to check \" ",REVSLIDER_TEXTDOMAIN).$option1Name.__(" \" in the backend's \"General Settings\" (top right panel). <br> <br> Or add the current page to the \"",REVSLIDER_TEXTDOMAIN).$option2Name.__("\" option box."));
                return(false);
            }

            RevSliderOutput::putSlider($data,$putIn);
        }
    }
}

/**
 * Product Quick View callback AJAX request 
 *
 * @since 1.0
 * @return json
 */

function wp_ajax_frontend_product_quick_view_callback() {
    check_ajax_referer( 'ajax_frontend', 'security' );
    global $product, $post;
	$product_id = intval($_POST["product_id"]);
	$post = get_post( $product_id );
	$product = wc_get_product( $product_id );
    wc_get_template( 'content-single-product-quick-view.php');
    die();
    
}
add_action( 'wp_ajax_frontend_product_quick_view', 'wp_ajax_frontend_product_quick_view_callback' );
add_action( 'wp_ajax_nopriv_frontend_product_quick_view', 'wp_ajax_frontend_product_quick_view_callback' );




function wp_ajax_fronted_remove_product_callback(){
    check_ajax_referer( 'ajax_frontend', 'security' );
    $item_key = $_POST['item_key'];
    $output = array();
    foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ){
        if($cart_item_key == $item_key ){
            WC()->cart->remove_cart_item( $cart_item_key );
        }
    }
    $output['content_product'] = kt_woocommerce_get_cart(false);
    echo json_encode($output);
    die();
}
add_action( 'wp_ajax_fronted_remove_product', 'wp_ajax_fronted_remove_product_callback' );
add_action( 'wp_ajax_nopriv_fronted_remove_product', 'wp_ajax_fronted_remove_product_callback' );

/**==============================
***  Like Post
===============================**/


add_action( 'wp_ajax_fronted_likepost', 'wp_ajax_fronted_likepost_callback' );
add_action( 'wp_ajax_nopriv_fronted_likepost', 'wp_ajax_fronted_likepost_callback' );


function wp_ajax_fronted_likepost_callback() {
    check_ajax_referer( 'ajax_frontend', 'security' );
    
    if(!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) return;
     
    $post_id = $_POST['post_id'];
     
    $output = array();    
    
    $like_count = get_post_meta($post_id, '_like_post', true);

    if( !isset($_COOKIE['like_post_'. $post_id]) ){
        $like_count++;
        update_post_meta($post_id, '_like_post', $like_count);

        //The cookie will expire after 30 days
        setcookie('like_post_'. $post_id, $post_id, time() + (86400 * 30), '/');
    }
    $text = ($like_count == 0 || $like_count == 1) ? __('like',THEME_LANG) : __('likes',THEME_LANG);

    $output['count'] = $like_count. ' '.$text;
    echo json_encode($output);
    die();
}


add_action( 'wp_ajax_fronted_popup', 'wp_ajax_fronted_popup_callback' );
add_action( 'wp_ajax_nopriv_fronted_popup', 'wp_ajax_fronted_popup_callback' );

function wp_ajax_fronted_popup_callback() {
    check_ajax_referer( 'ajax_frontend', 'security' );
    
    $dont_show = $_POST['val_input'];

    if( $dont_show == true ){
        setcookie('kt_popup', 1, time() + ( 1000*60*60*24), '/');
    }
    
    die();
}
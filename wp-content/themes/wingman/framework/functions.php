<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


/**
 * Flag boolean.
 *
 * @param $input string
 * @return boolean
 */
function kt_sanitize_boolean( $input = '' ) {
    $input = (string)$input;
    return in_array($input, array('1', 'true', 'y', 'on'));
}
add_filter( 'sanitize_boolean', 'kt_sanitize_boolean', 15 );




/**
 * Add class to next button
 *
 * @param string $attr
 * @return string
 */
function kt_next_posts_link_attributes( $attr = '' ) {
    return "class='btn btn-default'";
}
add_filter( 'next_posts_link_attributes', 'kt_next_posts_link_attributes', 15 );


/**
 * Add class to prev button
 *
 * @param string $attr
 * @return string
 */
function kt_previous_posts_link_attributes( $attr = '' ) {
    return "class='btn btn-default'";
}
add_filter( 'previous_posts_link_attributes', 'kt_previous_posts_link_attributes', 15 );



if ( ! function_exists( 'kt_track_post_views' ) ){
    /**
     * Track post views
     *
     * @param $post_id
     */
    function kt_track_post_views ($post_id) {

        if('post' == get_post_type() && is_single()) {
            if ( empty ( $post_id) ) {
                global $post;
                $post_id = $post->ID;
            }
            
            $count_key = 'kt_post_views_count';
            $count = get_post_meta($post_id, $count_key, true);
            if($count==''){
                $count = 0;
                delete_post_meta($post_id, $count_key);
                add_post_meta($post_id, $count_key, '0');
            }else{
                $count++;
                update_post_meta($post_id, $count_key, $count);
            }
        }
    }
}
add_action( 'wp_head', 'kt_track_post_views');

/**
 * Add page header
 *
 * @since 1.0
 */
add_action( 'theme_before_content', 'get_page_header', 20 );
function get_page_header( ){
    global $post;
    $show_title = true;

    if ( is_front_page() && is_singular('page')){
        $show_title = rwmb_meta('_kt_page_header', array(), get_option('page_on_front', true));
        if( $show_title == '' ||  $show_title == '-1'){
            $show_title = kt_option('show_page_header', 1);
        }
    }elseif(is_archive()){
        $show_title = kt_option('archive_page_header', 1);
        if(kt_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                $show_title = rwmb_meta('_kt_page_header', array(), $shop_page_id);
            }
            if(is_product_taxonomy() || is_product_tag() || $show_title == '' ||  $show_title == '-1'){
                $show_title = kt_option('shop_page_header', 1);
            }
        }
    }elseif(is_search()){
        $show_title = kt_option('search_page_header', 1);
    }elseif(is_author()){
        $show_title = kt_option('author_page_header', 1);
    }elseif(is_404()){
        $show_title = kt_option('notfound_page_header', 1);
    }elseif(is_page() || is_singular()){
        $post_id = $post->ID;
        $show_title = rwmb_meta('_kt_page_header', array(), $post_id);
        if( $show_title == '' ||  $show_title == '-1'){
            if(is_page()){
                $show_title = kt_option('show_page_header', 1);
            }elseif(is_singular('post')){
                $show_title = kt_option('single_page_header', 1);
            }elseif(is_singular('portfolio')){
                $show_title = kt_option('portfolio_page_header', 1);
            }elseif(is_singular('product')){
                $show_title = kt_option('product_page_header', 1);
            }else{
                if(kt_is_wc()){
                    if(is_product()){
                        $show_title = kt_option('product_page_header', 1);
                    }
                }else{
                    $show_title = kt_option('single_page_header', 1);
                }
            }
        }
    }

    $show_title = apply_filters( 'kt_show_title', $show_title );

    if($show_title){


        $title = kt_get_page_title();
        $subtitle = kt_get_page_subtitle();
        $breadcrumb = kt_get_breadcrumb();
        $page_header_layout = kt_get_page_layout();
        $page_header_align = kt_get_page_align();


        $title = '<h1 class="page-header-title">'.$title.'</h1>';
        if($subtitle != ''){
            $subtitle = '<div class="page-header-subtitle">'.$subtitle.'</div>';
        }

        $breadcrumb_class = (!kt_option('title_breadcrumbs_mobile', false)) ? 'hidden-xs hidden-sm' : '';


        $classes = array('page-header', 'page-header-'.$page_header_align, 'page-header-'.$page_header_layout);
        if($page_header_layout != 'sides'){
            $classes[] = 'page-header-sides';
        }


        if($breadcrumb == '' || $page_header_layout == 'centered'){
            $layout = '%3$s%1$s%2$s';
        }else{
            if($breadcrumb != ''){
                if($page_header_align == 'right'){
                    $layout = '<div class="row"><div class="col-md-8 page-header-right pull-right">%1$s%2$s</div><div class="col-md-4 page-header-left %4$s">%3$s</div></div>';
                }else{
                    $layout = '<div class="row"><div class="col-md-8 page-header-left">%1$s%2$s</div><div class="col-md-4 page-header-right %4$s">%3$s</div></div>';
                }
            }else{
                $layout = '%1$s%2$s%3$s%4$s';
            }
        }
        echo '<div class="'.implode(' ', $classes).'">';
        echo '<div class="container">';
            printf(
                $layout,
                $title,
                $subtitle,
                $breadcrumb,
                $breadcrumb_class
            );
        echo "</div>";
        echo "</div>";


    }
}

/**
 * Get page layout
 *
 * @return mixed
 *
 */
function kt_get_page_layout(){

    $page_header_layout = '';
    if ( is_front_page() && is_singular('page') ){
        $page_header_layout =  rwmb_meta('_kt_page_header_layout');
    }elseif(is_page() || is_singular()){
        $page_header_layout =  rwmb_meta('_kt_page_header_layout');
    }elseif(is_archive()){
        if(kt_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                $page_header_layout = rwmb_meta('_kt_page_header_align', array(), $shop_page_id);
            }
        }
    }


    if($page_header_layout == ''){
        $page_header_layout = kt_option('title_layout', 'sides');
    }
    return $page_header_layout;
}

/**
 * Get page align
 *
 * @return mixed
 *
 */
function kt_get_page_align(){

    $page_header_align = '';
    if ( is_front_page() && is_singular('page') ){
        $page_header_align =  rwmb_meta('_kt_page_header_align');
    }elseif(is_page() || is_singular()){
        $page_header_align =  rwmb_meta('_kt_page_header_align');
    }elseif(is_archive()){
        if(kt_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                $page_header_align = rwmb_meta('_kt_page_header_align', array(), $shop_page_id);
            }
        }
    }


    if($page_header_align == ''){
        $page_header_align = kt_option('title_align', 'left');
    }
    return $page_header_align;
}

/**
 * Get page title
 *
 * @param string $title
 * @return mixed|void
 */

function kt_get_page_title( $title = '' ){
    global $post;

    if ( is_front_page() && !is_singular('page') ) {
            $title = __( 'Blog', THEME_LANG );
    } elseif ( is_search() ) {
        $title = __( 'Search', THEME_LANG );
    } elseif( is_home() ){
        $page_for_posts = get_option('page_for_posts', true);
        if($page_for_posts){
            $title = get_the_title($page_for_posts) ;
        }
    } elseif( is_404() ) {
        $title = __( 'Page not found', THEME_LANG );
    } elseif ( is_archive() ){
        $title = get_the_archive_title();
        if(kt_is_wc()) {
            if (is_shop()) {
                $shop_page_id = get_option('woocommerce_shop_page_id');
                $title = get_the_title($shop_page_id);
            }
        }
    } elseif ( is_front_page() && is_singular('page') ){
        $page_on_front = get_option('page_on_front', true);
        $title = get_the_title($page_on_front) ;
    } elseif( is_page() || is_singular() ){
        $post_id = $post->ID;
        $custom_text = rwmb_meta('_kt_page_header_custom', array(), $post_id);
        $title = ($custom_text != '') ? $custom_text : get_the_title($post_id);
    }

    return apply_filters( 'kt_title', $title );

}

/**
 * Get page tagline
 *
 * @return mixed|void
 */

function kt_get_page_subtitle(){
    global $post;
    $tagline = '';
    if ( is_front_page() && !is_singular('page') ) {
        $tagline =  __('Lastest posts', THEME_LANG);
    }elseif( is_home() ){
        $page_for_posts = get_option('page_for_posts', true);
        $tagline = nl2br(rwmb_meta('_kt_page_header_subtitle', array(), $page_for_posts))  ;
    }elseif ( is_front_page() && is_singular('page') ){
        $tagline =  rwmb_meta('_kt_page_header_subtitle');
    }elseif ( is_archive() ){
        $tagline = get_the_archive_description( );
        if(kt_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                $tagline = rwmb_meta('_kt_page_header_subtitle', array(), $shop_page_id);
            }
            if( is_product_category() || is_product_tag() ){
                $tagline = '';
            }
        }
    }elseif(is_search()){
        $tagline = '';
    }elseif( $post ){
        $post_id = $post->ID;
        $tagline = nl2br(rwmb_meta('_kt_page_header_subtitle', array(), $post_id));
    }

    return apply_filters( 'kt_subtitle', $tagline );
}


add_filter( 'get_the_archive_title', 'kt_get_the_archive_title');
/**
 * Remove text Category and Archives in get_the_archive_title
 *
 * @param $title
 * @return null|string
 */
function kt_get_the_archive_title($title) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $tax = get_taxonomy( get_queried_object()->taxonomy );
        /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
        $title =  single_term_title( '', false );
    }

    return $title;

}


/**
 * Get breadcrumb
 *
 * @param string $breadcrumb
 * @return mixed|void
 */
function kt_get_breadcrumb($breadcrumb = ''){
    $show = '';
    if( is_page() || is_singular() ){
        $show_option = rwmb_meta( '_kt_show_breadcrumb' );
        if($show_option != ''){
            $show = $show_option;
        }
    }elseif ( is_front_page() && !is_singular('page') ) {
        $show_option = rwmb_meta( '_kt_show_breadcrumb' );
        if($show_option != ''){
            $show = $show_option;
        }
    }elseif ( is_archive() ){
        if(kt_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                if($shop_page_id){
                    $show = rwmb_meta('_kt_show_breadcrumb', array(), $shop_page_id);
                }
            }
        }
    }



    if($show == '' || $show == '-1'){
        $show = kt_option('title_breadcrumbs');
    }

    if($show){
        if(kt_is_wc()){
            if( is_woocommerce() ){
                ob_start();
                woocommerce_breadcrumb(
                    array(
                        'delimiter' =>'<span class="sep navigation-pipe"></span>',
                        'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs">',

                    ) );
                $breadcrumb = ob_get_clean();
            }else{
                if(function_exists('breadcrumb_trail')) {
                    $breadcrumb = breadcrumb_trail(array( 'echo' => false));
                }
            }
        }else{
            if(function_exists('breadcrumb_trail')) {
                $breadcrumb = breadcrumb_trail(array( 'echo' => false));
            }
        }


    }
    return apply_filters( 'kt_breadcrumb', $breadcrumb );
}

/**
 * Get settings archive
 *
 * @return array
 */
function kt_get_settings_archive(){
    if(is_author()){
        $settings = array(
            'blog_type' => kt_option('author_loop_style', 'classic'),
            'blog_columns' => kt_option('author_columns', 2),
            'blog_columns_tablet' => kt_option('author_columns_tablet', 2),
            'readmore' => kt_option('author_readmore', 'link'),
            'blog_pagination' => kt_option('author_pagination', 'classic'),
            'thumbnail_type' => kt_option('author_thumbnail_type', 'image'),
            'sharebox' => kt_option('author_sharebox', 1),
            'align' => kt_option('author_align', 'left'),
            'show_excerpt' => kt_option('author_excerpt', 1),
            'excerpt_length' => kt_option('author_excerpt_length', 30),
            'show_meta' => kt_option('author_meta', 1),
            'show_author' => kt_option('author_meta_author', 1),
            'show_category' => kt_option('author_meta_categories', 1),
            'show_comment' => kt_option('author_meta_comments', 1),
            'show_date' => kt_option('author_meta_date', 1),
            'date_format' => kt_option('author_date_format', 1),
            'show_like_post' => kt_option('author_like_post', 0),
            'show_view_number' => kt_option('author_view_number', 0),
            'image_size' => kt_option('author_image_size', 'blog_post'),
            'max_items' => get_option('posts_per_page')
        );
    }else{
        $settings = array(
            'blog_type' => kt_option('archive_loop_style', 'classic'),
            'blog_columns' => kt_option('archive_columns', 2),
            'blog_columns_tablet' => kt_option('archive_columns_tablet', 2),
            'readmore' => kt_option('archive_readmore', 'link'),
            'blog_pagination' => kt_option('archive_pagination', 'classic'),
            'thumbnail_type' => kt_option('archive_thumbnail_type', 'image'),
            'sharebox' => kt_option('archive_sharebox', 1),
            'align' => kt_option('archive_align', 'left'),
            'show_excerpt' => kt_option('archive_excerpt', 1),
            'excerpt_length' => kt_option('archive_excerpt_length', 30),
            'show_meta' => kt_option('archive_meta', 1),
            'show_author' => kt_option('archive_meta_author', 1),
            'show_category' => kt_option('archive_meta_categories', 1),
            'show_comment' => kt_option('archive_meta_comments', 1),
            'show_date' => kt_option('archive_meta_date', 1),
            'date_format' => kt_option('archive_date_format', 1),
            'show_like_post' => kt_option('archive_like_post', 0),
            'show_view_number' => kt_option('archive_view_number', 0),
            'image_size' => kt_option('archive_image_size', 'blog_post'),
            'max_items' => get_option('posts_per_page')
        );
    }
    return $settings;
}

/**
 * Get settings search
 *
 * @return array
 */
function kt_get_settings_search(){
    return array(
        'blog_type' => kt_option('search_loop_style', 'classic'),
        'blog_columns' => kt_option('search_columns', 3),
        'blog_columns_tablet' => kt_option('search_columns_tablet', 2),
        'align' => kt_option('archive_align', 'left'),
        'readmore' => kt_option('search_readmore', 'link'),
        'blog_pagination' => kt_option('search_pagination', 'classic'),
        'thumbnail_type' => kt_option('search_thumbnail_type', 'image'),
        'sharebox' => kt_option('search_sharebox', 0),
        'show_excerpt' => kt_option('search_excerpt', 1),
        'excerpt_length' => kt_option('search_excerpt_length', 30),
        'show_meta' => kt_option('search_meta', 1),
        'show_author' => kt_option('search_meta_author', 1),
        'show_category' => kt_option('search_meta_categories', 1),
        'show_comment' => kt_option('search_meta_comments', 1),
        'show_date' => kt_option('search_meta_date', 1),
        'date_format' => kt_option('search_date_format', 1),
        'show_like_post' => kt_option('search_like_post', 0),
        'show_view_number' => kt_option('search_view_number', 0),
        'image_size' => kt_option('search_image_size', 'blog_post'),
        'max_items' => get_option('posts_per_page'),

    );
}


/**
 * Extend the default WordPress body classes.
 *
 * @since 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function theme_body_classes( $classes ) {
    global $post;
    
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }
    
    if( is_page() || is_singular('post')){
        $classes[] = 'layout-'.kt_getlayout($post->ID);
        $classes[] = rwmb_meta('_kt_extra_page_class');
    }elseif(is_archive()){
        if(kt_is_wc()){
            if(is_shop()){
                $page_id = get_option( 'woocommerce_shop_page_id' );

                $classes[] = 'layout-'.kt_getlayout($page_id);
                $classes[] = rwmb_meta('_kt_extra_page_class', array(), $page_id);
            }else{
                $classes[] = 'layout-'.kt_option('layout');
            }
        }else{
            $classes[] = 'layout-'.kt_option('layout');
        }
    }else{
        $classes[] = 'layout-'.kt_option('layout');
    }

    if( kt_option( 'footer_fixed' ) == 1 ){
        $classes[] = 'footer_fixed';
    }

    return $classes;
}
add_filter( 'body_class', 'theme_body_classes' );




/**
 * Add class layout for main class
 *
 * @since 1.0
 *
 * @param string $classes current class
 * @param string $layout layout current of page 
 *  
 * @return array The filtered body class list.
 */
function kt_main_class_callback($classes, $layout){
    
    if($layout == 'left' || $layout == 'right'){
        $classes .= ' col-md-9 col-sm-12 col-xs-12';
    }else{
        $classes .= ' col-md-12 col-xs-12';
    }
    
    if($layout == 'left'){
         $classes .= ' pull-right';
    }
    return $classes;
}
add_filter('kt_main_class', 'kt_main_class_callback', 10, 2);


/**
 * Add class layout for sidebar class
 *
 * @since 1.0
 *
 * @param string $classes current class
 * @param string $layout layout current of page 
 *  
 * @return array The filtered body class list.
 */
function kt_sidebar_class_callback( $classes, $layout ){
    if($layout == 'left' || $layout == 'right'){
        $classes .= ' col-md-3 col-sm-12 col-xs-12';
    }
    return $classes;
}
add_filter('kt_sidebar_class', 'kt_sidebar_class_callback', 10, 2);



/**
 * Add class sticky to header
 */
function theme_header_class_callback($classes, $layout){
    $fixed_header = kt_option('fixed_header', 2);
    if($fixed_header == 2 || $fixed_header == 3 ){
        $classes .= ' sticky-header';
        if($fixed_header == 3){
            $classes .= ' sticky-header-down';
        }
    }

    $header_shadow = kt_option('header_shadow', true);
    if($header_shadow){
        $classes .= ' header-shadow';
    }

    if($layout == 'layout1' || $layout == 'layout2'){
        $classes .= ' header-layout-normal';
    }

    return $classes;
}

add_filter('theme_header_class', 'theme_header_class_callback', 10, 2);


/**
 * Add class sticky to header
 */
function theme_header_content_class_callback( $classes, $layout ){

    if(kt_option('header_full', 1)){
        $classes .= ' header-fullwidth';
    }


    return $classes;
}

add_filter('theme_header_content_class', 'theme_header_content_class_callback', 10, 2);

/**
 * Add slideshow header
 *
 * @since 1.0
 */
add_action( 'kt_slideshows_position', 'kt_slideshows_position_callback' );
function kt_slideshows_position_callback(){
    if(is_page() || is_singular()){
        kt_show_slideshow();
    }elseif ( kt_is_wc() ) {
        if(is_shop()){
            $shop_page_id = get_option( 'woocommerce_shop_page_id' );
            kt_show_slideshow($shop_page_id);
        }
    }
}

add_action( 'comment_form_before_fields', 'kt_comment_form_before_fields', 1 );
function kt_comment_form_before_fields(){
    echo '<div class="comment-form-fields clearfix">';
}



add_action( 'comment_form_after_fields', 'kt_comment_form_after_fields', 9999 );
function kt_comment_form_after_fields(){
    echo '</div>';
}

/**
 * Change separator of breadcrumb
 * 
 */
function kt_breadcrumb_trail_args( $args ){
    $args['separator'] = "&nbsp;";
    return $args;
}
add_filter('breadcrumb_trail_args', 'kt_breadcrumb_trail_args');


/*
 * Add social media to author
 */

function kt_contactmethods( $contactmethods ) {

    // Add Twitter, Facebook
    $contactmethods['facebook'] = __('Facebook page/profile url', THEME_LANG);
    $contactmethods['twitter'] = __('Twitter username (without @)', THEME_LANG);
    $contactmethods['pinterest'] = __('Pinterest username', THEME_LANG);
    $contactmethods['googleplus'] = __('Google+ page/profile URL', THEME_LANG);
    $contactmethods['instagram'] = __('Instagram username', THEME_LANG);
    $contactmethods['tumblr'] = __('Tumblr username', THEME_LANG);


    return $contactmethods;
}
add_filter( 'user_contactmethods','kt_contactmethods', 10, 1 );


if(!function_exists('kt_placeholder_callback')) {
    /**
     * Return PlaceHolder Image
     * @param string $size
     * @return string
     */
    function kt_placeholder_callback($size = '')
    {

        $placeholder = kt_option('archive_placeholder');
        if(is_array($placeholder) && $placeholder['id'] != '' ){
            $obj = get_thumbnail_attachment($placeholder['id'], $size);
            $imgage = $obj['url'];
        }elseif($size == 'blog_post' || $size == 'blog_post_sidebar'){
            $imgage = THEME_IMG . 'placeholder-blogpost.png';
        }else{
            $imgage = THEME_IMG . 'placeholder-post.png';
        }

        return $imgage;
    }
    add_filter('kt_placeholder', 'kt_placeholder_callback');
}


if ( ! function_exists( 'kt_excerpt_more' ) ) :
    /**
     * Replaces "[...]" (appended to automatically generated excerpts) with ...
     *
     * @param string $more Default Read More excerpt link.
     * @return string Filtered Read More excerpt link.
     */
    function kt_excerpt_more( $more ) {
        return ' &hellip; ';
    }
    add_filter( 'excerpt_more', 'kt_excerpt_more' );
endif;


add_filter( 'the_content_more_link', 'kt_modify_read_more_link', 10, 2 );
function kt_modify_read_more_link( $link, $more_link_text ) {
    return '';
}


if ( ! function_exists( 'kt_page_loader' ) ) :
    /**
     * Add page loader to frontend
     *
     */
    function kt_page_loader(){
        $use_loader = kt_option( 'use_page_loader',1 );
        if( $use_loader ){
            $layout_loader = kt_option( 'layout_loader', 'style-1' );
            ?>
            <div class="kt_page_loader <?php echo esc_attr($layout_loader); ?>">
                <div class="page_loader_inner">
                    <div class="kt_spinner"></div>
                </div>
            </div>
        <?php }
    }
    add_action( 'theme_body_top', 'kt_page_loader');
endif;



function add_search_full(){
    if(kt_option('header_search', 1)){

        if(kt_is_wc()){
            $search = get_product_search_form(false);
        }else{
            $search = get_search_form(false);
        }

        printf(
            '<div id="%1$s" class="%2$s">%3$s</div>',
            'search-fullwidth',
            'mfp-hide mfp-with-anim',
            $search
        );
    }
}
add_action('theme_body_top', 'add_search_full');



if ( ! function_exists( 'kt_login_body_class' ) ) :
    /**
     * Add class regsiter to body
     *
     */
    function kt_login_body_class($classes, $action){
        if ( get_option( 'users_can_register' ) ) {
            $classes[] = 'register-allow';
        }
        return $classes;
    }
    add_filter( 'login_body_class', 'kt_login_body_class', 10, 2);
endif;


//if ( ! function_exists( 'kt_register_account' ) ) :
    /**
     * Change register account
     *
     */
    /*
    function kt_register_account(){
        $rememberme = ! empty( $_POST['rememberme'] );
        ?>
        <p class="forgetmenot kt_forget"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_attr_e('Remember Me'); ?></label></p>
        <?php
        if ( get_option( 'users_can_register' ) ) {
            $registration_url = sprintf( '<p class="register"><a class="button button-primary button-large" href="%s">%s</a></p>', esc_url( wp_registration_url() ), __( 'Register' ) );            
            echo apply_filters( 'register', $registration_url );
        }
    }
    add_action('login_form', 'kt_register_account');*/
//endif;

if ( ! function_exists( 'kt_theme_before_main' ) ) :
    /**
     * Add <div> overlap before row main
     *
     */
    function kt_theme_before_main(){
        $overlap = rwmb_meta('_kt_overlap_content');
        if($overlap == 'yes'){
            echo "<div class='overlap-maincontent'>";
        }
    }
    add_action('theme_before_main', 'kt_theme_before_main');
endif;



if ( ! function_exists( 'kt_theme_after_main' ) ) :
    /**
     * Add </div> overlap after row main
     *
     */
    function kt_theme_after_main(){
        $overlap = rwmb_meta('_kt_overlap_content');
        if($overlap == 'yes'){
            echo "</div>";
        }
    }
    add_action('theme_after_main', 'kt_theme_after_main');
endif;


remove_filter ('the_content', 'fbcommentbox', 100);


/**
 * Add Category by Search form 
 **/
function advanced_search_query($query) {

    if($query->is_search()) {
        // category terms search.
        if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
            $query->set('tax_query', array(array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array($_GET['product_cat']))
            ));
        }
        return $query;
    }
}
add_action('pre_get_posts', 'advanced_search_query', 1000);

/**
 * Add Category by Search form Product
 **/
function kt_get_categories_product(){
    global $post;
    $categories = get_terms( 'product_cat' );

    
    if( count($categories) > 0 ){
        echo '<div class="wrap_product_cat"><select id="product_cat" class="postform" name="product_cat">';
            echo '<option value="-1">'.__('All Categories', THEME_LANG).'</option>';
            foreach ($categories as $key => $value) {
                echo '<option value="'.$value->slug.'">'.$value->name.'</option>';
            }
        echo '</select></div>';
    }
}

/**
 * Add popup 
 *
 * @since 1.0
 */
add_action( 'theme_after_footer', 'theme_after_footer_add_popup', 20 );
function theme_after_footer_add_popup(){
    $enable_popup = kt_option( 'enable_popup' );
    $disable_popup_mobile = kt_option( 'disable_popup_mobile' );
    $content_popup = kt_option( 'content_popup' );
    $time_show = kt_option( 'time_show', 0 );
    
    if( $enable_popup == 1 ){ 
        if(!isset($_COOKIE['kt_popup'])){ ?>
            <div id="popup-wrap" class="mfp-hide" data-mobile="<?php echo esc_attr( $disable_popup_mobile ); ?>" data-timeshow="<?php echo esc_attr($time_show); ?>">     
                <div class="white-popup-block">
                    <?php echo do_shortcode($content_popup); ?>
                </div>
                <form class="dont-show" name="dont-show">
                    <input id="dont-showagain" type="checkbox" value="" /><label for="dont-showagain"><?php _e( "Don’t Show Again.", THEME_LANG ); ?></label>
                </form>
            </div>
        <?php }
    }
}
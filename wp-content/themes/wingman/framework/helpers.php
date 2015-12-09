<?php

/**
 * All helpers for theme
 *
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


/**
 * Function check if WC Plugin installed
 */
function kt_is_wc(){
    return function_exists('is_woocommerce');
}

/**
 *  @true  if WPML installed.
 */
function  kt_is_wpml(){
    return class_exists('SitePress');
}


/**
 *
 * Detect plugin.
 *
 * @param $plugin example: 'plugin-directory/plugin-file.php'
 */

function kt_is_active_plugin(   $plugin ){
    if(  !function_exists( 'is_plugin_active' ) ){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    // check for plugin using plugin name
    return is_plugin_active( $plugin ) ;
}


if (!function_exists('kt_sidebars')){
    /**
     * Get sidebars
     *
     * @return array
     */

    function kt_sidebars( ){
        $sidebars = array();
        foreach ( $GLOBALS['wp_registered_sidebars'] as $item ) {
            $sidebars[$item['id']] = $item['name'];
        }
        return $sidebars;
    }
}



if (!function_exists('kt_get_image_sizes')){
    /**
     * Get image sizes
     *
     * @return array
     */
    function kt_get_image_sizes( $full = true, $custom = false ) {

        global $_wp_additional_image_sizes;
        $get_intermediate_image_sizes = get_intermediate_image_sizes();
        $sizes = array();
        // Create the full array with sizes and crop info
        foreach( $get_intermediate_image_sizes as $_size ) {

            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

                    $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
                    $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                    $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );

            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

                    $sizes[ $_size ] = array(
                            'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                            'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                            'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
                    );

            }

            $option_text = array();
            $option_text[] = ucfirst(str_replace('_', ' ', $_size));
            if( isset($sizes[ $_size ]) ){
                $option_text[] = '('.$sizes[ $_size ]['width'].' x '.$sizes[ $_size ]['height'].')';
                if($sizes[ $_size ]['crop']){
                    $option_text[] = __('Crop', THEME_LANG);
                }
                $sizes[ $_size ] = implode(' - ', $option_text);
            }
        }

        if($full){
            $sizes[ 'full' ] = __('Full', THEME_LANG);
        }
        if($custom){
            $sizes[ 'custom' ] = __('Custom size', THEME_LANG);
        }


        return $sizes;
    }
}


if (!function_exists('kt_get_woo_sidebar')) {
    /**
     * Get woo sidebar
     *
     * @param null $post_id
     * @return array
     */
    function kt_get_woo_sidebar( $post_id = null )
    {
        if(is_product() || $post_id || is_shop()){
            if(is_shop() && !$post_id){
                $post_id = get_option( 'woocommerce_shop_page_id' );
            }
            global $post;
            if(!$post_id) $post_id = $post->ID;

            $sidebar = array(
                'sidebar' => rwmb_meta('_kt_sidebar', array(), $post_id),
                'sidebar_area' => '',
            );

            if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' ){
                $sidebar['sidebar'] = kt_option('product_sidebar', 'right');
                if($sidebar['sidebar'] == 'left' ){
                    $sidebar['sidebar_area'] = kt_option('product_sidebar_left', 'shop-widget-area');
                }elseif($sidebar['sidebar'] == 'right'){
                    $sidebar['sidebar_area'] = kt_option('product_sidebar_right', 'shop-widget-area');
                }
            }elseif($sidebar['sidebar'] == 'left'){
                $sidebar['sidebar_area'] = rwmb_meta('_kt_left_sidebar', array(), $post_id);
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = rwmb_meta('_kt_right_sidebar', array(), $post_id);
            }
        }elseif( is_product_taxonomy() || is_product_tag()){
            $sidebar = array(
                'sidebar' => kt_option('shop_sidebar', 'right'),
                'sidebar_area' => '',
            );
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = kt_option('shop_sidebar_left', 'shop-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = kt_option('shop_sidebar_right', 'shop-widget-area');
            }
        }elseif(is_cart()){
            $sidebar = array(
                'sidebar' => 'full',
                'sidebar_area' => '',
            );
        }
        return apply_filters('woo_sidebar', $sidebar);
    }
}



if (!function_exists('kt_get_page_sidebar')) {
    /**
     * Get page sidebar
     *
     * @param null $post_id
     * @return mixed|void
     */
    function kt_get_page_sidebar( $post_id = null )
    {

        global $post;
        if(!$post_id) $post_id = $post->ID;

        if(kt_is_wc()){
            $cart_id = wc_get_page_id('cart');
            $checkout_id = wc_get_page_id('checkout');
            if($post_id == $cart_id || $post_id == $checkout_id || is_cart() || is_checkout()){
                return array('sidebar' => 'full', 'sidebar_area' => '');
            }
        }


        $sidebar = array(
            'sidebar' => rwmb_meta('_kt_sidebar', array(), $post_id),
            'sidebar_area' => '',
        );
        if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' ){
            $sidebar['sidebar'] = kt_option('sidebar', 'full');
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = kt_option('sidebar_left', 'primary-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = kt_option('sidebar_right', 'primary-widget-area');
            }
        }elseif($sidebar['sidebar'] == 'left'){
            $sidebar['sidebar_area'] = rwmb_meta('_kt_left_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = rwmb_meta('_kt_right_sidebar', array(), $post_id);
        }



        return apply_filters('page_sidebar', $sidebar);

    }
}

if (!function_exists('kt_get_single_sidebar')) {
    /**
     * Get Single post sidebar
     *
     * @param null $post_id
     * @return array
     */
    function kt_get_single_sidebar( $post_id = null )
    {
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $sidebar = array(
            'sidebar' => rwmb_meta('_kt_sidebar', array(), $post_id),
            'sidebar_area' => '',
        );
        if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' ){
            $sidebar['sidebar'] = kt_option('blog_sidebar', 'right');
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = kt_option('blog_sidebar_left', 'blog-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = kt_option('blog_sidebar_right', 'blog-widget-area');
            }
        }elseif($sidebar['sidebar'] == 'left'){
            $sidebar['sidebar_area'] = rwmb_meta('_kt_left_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = rwmb_meta('_kt_right_sidebar', array(), $post_id);
        }

        return apply_filters('single_sidebar', $sidebar);
    }

}


if (!function_exists('kt_get_archive_sidebar')) {
    /**
     * Get Archive sidebar
     *
     * @return array
     */
    function kt_get_archive_sidebar()
    {
        $sidebar = array( 'sidebar' => '', 'sidebar_area' => '' );


        if(is_front_page()){
            $post_id = get_option( 'page_for_posts' );
            $sidebar = array(
                'sidebar' => rwmb_meta('_kt_sidebar', array(), $post_id),
                'sidebar_area' => '',
            );
        }

        if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default'){
            $sidebar = array(
                'sidebar' => kt_option('archive_sidebar', 'right'),
                'sidebar_area' => '',
            );
        }

        if($sidebar['sidebar'] == 'left' ){
            $sidebar['sidebar_area'] = kt_option('archive_sidebar_left', 'primary-widget-area');
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = kt_option('archive_sidebar_right', 'primary-widget-area');
        }

        return apply_filters('archive_sidebar', $sidebar);
    }
}


if (!function_exists('kt_get_search_sidebar')) {
    /**
     * Get Search sidebar
     *
     * @return array
     */
    function kt_get_search_sidebar()
    {
        $sidebar = array(
            'sidebar' => kt_option('search_sidebar', 'right'),
            'sidebar_area' => '',
        );
        if($sidebar['sidebar'] == 'left' ){
            $sidebar['sidebar_area'] = kt_option('search_sidebar_left', 'blog-widget-area');
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = kt_option('search_sidebar_right', 'blog-widget-area');
        }
        return apply_filters('search_sidebar', $sidebar);
    }
}



if (!function_exists('kt_option')){
    /**
     * Function to get options in front-end
     * @param int $option The option we need from the DB
     * @param string $default If $option doesn't exist in DB return $default value
     * @return string
     */

    function kt_option( $option = false, $default = false ){
        if($option === FALSE){
            return FALSE;
        }
        $kt_options = wp_cache_get( THEME_OPTIONS );
        if(  !$kt_options ){
            $kt_options = get_option( THEME_OPTIONS );
            wp_cache_delete( THEME_OPTIONS );
            wp_cache_add( THEME_OPTIONS, $kt_options );
        }

        if(isset($kt_options[$option]) && $kt_options[$option] !== ''){
            return $kt_options[$option];
        }else{
            return $default;
        }
    }
}


if (!function_exists('kt_get_logo')){
    /**
     * Get logo of current page
     *
     * @return string
     *
     */
    function kt_get_logo(){
        $logo = array('default' => '', 'retina' => '');
        $logo_default = kt_option( 'logo' );
        $logo_retina = kt_option( 'logo_retina' );


        if(is_array($logo_default) && $logo_default['url'] != '' ){
            $logo['default'] = $logo_default['url'];
        }

        if(is_array($logo_retina ) && $logo_retina['url'] != '' ){
            $logo['retina'] = $logo_retina['url'];
        }

        if(!$logo['default']){
            $logo['default'] = THEME_IMG.'logo.png';
            $logo['retina'] = THEME_IMG.'logo-2x.png';
        }

        return $logo;
    }
}

if (!function_exists('kt_getlayout')) {
    /**
     * Get Layout of post
     *
     * @param number $post_id Optional. ID of article or page.
     * @return string
     *
     */
    function kt_getlayout($post_id = null){
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $layout = rwmb_meta('_kt_layout', array(),  $post_id);
        if($layout == 'default' || !$layout){
            $layout = kt_option('layout', 'full');
        }

        return $layout;
    }
}

if (!function_exists('kt_show_slideshow')) {
    /**
     * Show slideshow of page or singular
     *
     * @param $post_id
     *
     */
    function kt_show_slideshow($post_id = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $slideshow = rwmb_meta('_kt_slideshow_source', array(), $post_id);

        $output = '';

        if ($slideshow == 'revslider') {
            $revslider = rwmb_meta('_kt_rev_slider', array(), $post_id);
            if ($revslider && class_exists('RevSlider')) {
                ob_start();
                putRevSlider($revslider);
                $revslider_html = ob_get_contents();
                ob_end_clean();

                $output .= $revslider_html;

            }
        } elseif ($slideshow == 'layerslider') {
            $layerslider = rwmb_meta('_kt_layerslider', array(), $post_id);
            if ($layerslider && is_plugin_active('LayerSlider/layerslider.php')) {
                $layerslider_html = do_shortcode('[layerslider id="' . $layerslider . '"]');
                if($layerslider_html){
                    $output .= $layerslider_html;
                }
            }
        }

        if($output != ''){
            echo '<div id="main-content-sideshow">'.$output.'</div>';
        }


    }
}


if (!function_exists('kt_get_header')) {
    /**
     * Get Header
     *
     * @return string
     *
     */
    function kt_get_header(){
        $header = 'default';
        $header_position = '';

        if(is_page() || is_singular()){
            $header_position = rwmb_meta('_kt_header_position');
        }elseif(is_archive()){
            if(kt_is_wc()){
                if(is_shop()){
                    $page_id = get_option( 'woocommerce_shop_page_id' );
                    $header_position = rwmb_meta('_kt_header_position', array(), $page_id);
                }
            }
        }

        if($header_position){
            $header = $header_position;
        }
        return $header;
    }
}

if (!function_exists('kt_get_header_layout')) {
    /**
     * Get Header Layout
     *
     * @return string
     *
     */
    function kt_get_header_layout(){
        $layout = kt_option('header', 'layout1');
        return $layout;
    }
}



if (!function_exists('get_thumbnail_attachment')){
    /**
     * Get link attach from thumbnail_id.
     *
     * @param number $thumbnail_id ID of thumbnail.
     * @param string|array $size Optional. Image size. Defaults to 'post-thumbnail'
     * @return array
     */

    function get_thumbnail_attachment($thumbnail_id ,$size = 'post-thumbnail'){
        if(!$thumbnail_id) return false;
        
        $attachment = get_post( $thumbnail_id );
        if(!$attachment) return false;
        
        $image = wp_get_attachment_image_src($thumbnail_id, $size);
    	return array(
    		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
    		'caption' => $attachment->post_excerpt,
    		'description' => $attachment->post_content,
            'src' => $attachment->guid,
    		'url' => $image[0],
    		'title' => $attachment->post_title
    	);
    }
}


if (!function_exists('kt_custom_wpml')){
    /**
     * Custom wpml
     *
     */

    function kt_custom_wpml($before = '', $after = '', $title = ''){

        if(kt_is_wpml()){

            $output = $language_html = '';

            if($title){
                $output .= '<h4>'.$title.'</h4>';
            }

            $languages = icl_get_languages();

            if(!empty($languages)) {
                foreach ($languages as $l) {
                    $active = ($l['active']) ? 'current' : '';

                    $language_html .= '<li class="'.$active.'">';
                    $language_html .= '<a href="' . $l['url'] . '">';
                    if ($l['country_flag_url']) {
                        $language_html .= '<img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" />';
                    }
                    $language_html .= "<span>" . $l['native_name'] . "</span>";
                    $language_html .= '</a>';

                    $language_html .= '</li>';
                }

                if ($language_html != '') {
                    $language_html = '<ul>' . $language_html . '</ul>';
                }

                $output = $language_html;

            }

            echo $before.$output.$after;

        }

    }
}
if (!function_exists('get_link_image_post')) {
    /**
     * Get image form meta.
     *
     * @param string $meta . meta id of article.
     * @param string|array $size Optional. Image size. Defaults to 'screen'.
     * @param number $post_id Optional. ID of article.
     * @return array
     */

    function get_link_image_post($meta, $post_id = null, $size = 'screen')
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $media_image = rwmb_meta($meta, 'type=image&size=' . $size, $post_id);

        if (!$media_image) return;

        foreach ($media_image as $item) {
            return $item;
            break;
        }
    }
}


if (!function_exists('get_galleries_post')) {
    /**
     * Get all image form meta box.
     *
     * @param string $meta . meta id of article.
     * @param string|array $size Optional. Image size. Defaults to 'screen'.
     * @param array $post_id Optional. ID of article.
     * @return array
     */
    function get_galleries_post($meta, $size = 'screen', $post_id = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $media_image = rwmb_meta($meta, 'type=image&size=' . $size, $post_id);

        return (count($media_image) ) ? $media_image : false;
    }
}
if (!function_exists('kt_get_single_file')) {
    /**
     * Get Single file form meta box.
     *
     * @param string $meta . meta id of article.
     * @param string|array $size Optional. Image size. Defaults to 'screen'.
     * @param array $post_id Optional. ID of article.
     * @return array
     */
    function kt_get_single_file($meta, $post_id = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;
        $medias = rwmb_meta($meta, 'type=file', $post_id);
        if (count($medias)) {
            foreach ($medias as $media) {
                return $media['url'];
            }
        }
        return false;
    }
}

/**
 * Render Carousel
 *
 * @param $data array, All option for carousel
 * @param $class string, Default class for carousel
 *
 * @return void
 */

function kt_render_carousel($data, $extra = '', $class = 'owl-carousel kt-owl-carousel'){
    $data = shortcode_atts( array(
        'gutters' => true,
        'autoheight' => true,
        'autoplay' => false,
        'mousedrag' => true,
        'autoplayspeed' => 5000,
        'slidespeed' => 200,
        'desktop' => 4,
        'desktopsmall' => '',
        'tablet' => 2,
        'mobile' => 1,

        'navigation' => true,
        'navigation_always_on' => false,
        'navigation_position' => 'center_outside',
        'navigation_style' => 'circle_border',
        'navigation_icon' => 'fa fa-angle-left|fa fa-angle-right',

        'pagination' => false,
        'pagination_position' => 'outside',

        'carousel_skin' => 'dark',
        'callback' => ''

    ), $data );

    if(!$data['desktopsmall']){
        $data['desktopsmall'] = $data['desktop'];
    }

    extract( $data );


    $autoheight = apply_filters('sanitize_boolean', $autoheight);
    $autoplay = apply_filters('sanitize_boolean', $autoplay);
    $mousedrag = apply_filters('sanitize_boolean', $mousedrag);
    $navigation = apply_filters('sanitize_boolean', $navigation);
    $navigation_always_on = apply_filters('sanitize_boolean', $navigation_always_on);
    $pagination = apply_filters('sanitize_boolean', $pagination);

    $output = $custom_css = '';
    $uniqid = 'owl-carousel-'.uniqid();

    $owl_carousel_class = array(
        'owl-carousel-kt',
        'carousel-navigation-'.$navigation_position,
        'carousel-'.$carousel_skin,
        $extra
    );

    if($gutters){
        $owl_carousel_class[] = 'carousel-gutters';
    }

    if(!$navigation_always_on && $navigation_position != 'bottom'){
        $owl_carousel_class[] = 'visiable-navigation';
    }
    if($navigation_style){
        $owl_carousel_class[] = 'carousel-navigation-'.$navigation_style;
        $owl_carousel_class[] = 'carousel-navigation-hasstyle';
        if(strpos($navigation_style, 'border') !== false){
            $owl_carousel_class[] = 'carousel-navigation-border';
        }elseif(strpos($navigation_style, 'background') !== false){
            $owl_carousel_class[] = 'carousel-navigation-background';
        }
    }
    if($pagination){
        $owl_carousel_class[] = 'carousel-pagination-dots';
    }


    $autoplay = ($autoplay) ? $autoplayspeed : $autoplay;

    $data_carousel = array(
        'mousedrag' => $mousedrag,
        "autoheight" => $autoheight,
        "autoplay" => $autoplay,
        'navigation_icon' => $navigation_icon,
        "navigation" => $navigation,
        'navigation_pos' => $navigation_position,
        "slidespeed" => $slidespeed,
        'desktop' => $desktop,
        'desktopsmall' => $desktopsmall,
        'tablet' => $tablet,
        'mobile' => $mobile,
        'pagination' => $pagination,
        'callback' => $callback

    );


    $output .= '<div id="'.esc_attr($uniqid).'" class="'.esc_attr(implode(' ', $owl_carousel_class)).'">';
    $output .= '<div class=" '.$class.'" '.render_data_carousel($data_carousel).'>%carousel_html%</div>';
    $output .= '</div>';

    if($custom_css){
        $output .= '<div class="kt_custom_css" data-css="'.$custom_css.'"></div>';
    }

    return $output;
}


if (!function_exists('render_data_carousel')) {

    /*
     * Render data option for carousel
     * @param $data
     * @return string
     */
    function render_data_carousel($data)
    {
        $output = "";
        $array = array();
        foreach ($data as $key => $val) {
            if (is_bool($val) === true) {
                $val = ($val) ? 'true': 'false';
                $array[$key]= '"'.$key.'": '.$val;
            }else{
                $array[$key]= '"'.$key.'": "'.$val.'"';
            }
        }

        if(count($array)){
            $output = " data-options='{".implode(',', $array)."}'";
        }

        return $output;
    }
}



if (!function_exists('kt_post_option')) {
    /**
     * Check option for in article
     *
     * @param number $post_id Optional. ID of article.
     * @param string $meta Optional. meta oftion in article
     * @param string $option Optional. if meta is Global, Check option in theme option.
     * @param string $default Optional. Default vaule if theme option don't have data
     * @return boolean
     */
    function kt_post_option($post_id = null, $meta = '', $option = '', $default = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $meta_v = get_post_meta($post_id, $meta, true);

        if ($meta_v == -1 || $meta_v == '') {
            $meta_v = kt_option($option, $default);
        }
        return $meta_v;
    }
}

if (!function_exists('kt_get_template_part')) {
    /**
     * Check option for in article
     *
     * @param number $post_id Optional. ID of article.
     * @param string $meta Optional. meta oftion in article
     * @param string $option Optional. if meta is Global, Check option in theme option.
     * @param string $default Optional. Default vaule if theme option don't have data
     * @return boolean
     */
    function kt_get_template_part($slug, $name = null, $blog_atts = array())
    {
        /**
         * Fires before the specified template part file is loaded.
         *
         * The dynamic portion of the hook name, `$slug`, refers to the slug name
         * for the generic template part.
         *
         * @since 3.0.0
         *
         * @param string $slug The slug name for the generic template.
         * @param string $name The name of the specialized template.
         */
        do_action( "get_template_part_{$slug}", $slug, $name );

        $templates = array();
        $name = (string) $name;
        if ( '' !== $name )
            $templates[] = "{$slug}-{$name}.php";

        $templates[] = "{$slug}.php";

        include(locate_template($templates));

    }
}

if (!function_exists('kt_render_custom_css')) {
    /**
     * Render custom css
     *
     * @param $meta
     * @param $selector
     * @param null $post_id
     */

    function kt_render_custom_css($meta , $selector, $post_id = null)
    {
        if(!$post_id){
            global $post;
            $post_id = $post->ID;
        }
        $page_bg = rwmb_meta($meta, array(), $post_id);
        if(is_array($page_bg)){
            $page_arr = array();

            $page_color = $page_bg['color'];
            if( $page_color != '' && $page_color != '#'){
                $page_arr[] = 'background-color: '.$page_color;
            }
            if($page_url = $page_bg['url']){
                $page_arr[] = 'background-image: url('.$page_url.')';
            }
            if($page_repeat = $page_bg['repeat']){
                $page_arr[] = 'background-repeat: '.$page_repeat;
            }
            if($page_size = $page_bg['size']){
                $page_arr[] = 'background-size: '.$page_size;
            }
            if($page_attachment = $page_bg['attachment']){
                $page_arr[] = 'background-attachment: '.$page_attachment;
            }
            if($page_position = $page_bg['position']){
                $page_arr[] = 'background-position: '.$page_position;
            }
            if(count($page_arr)){
                echo $selector.'{'.implode(';', $page_arr).'}';
            }
        }
    }
}


if(!function_exists('kt_colour_brightness')){
    /**
     * Convert hexdec color string to darken or lighten
     *
     * http://lab.clearpixel.com.au/2008/06/darken-or-lighten-colours-dynamically-using-php/
     *
     * $brightness = 0.5; // 50% brighter
     * $brightness = -0.5; // 50% darker
     *
     */

    function kt_colour_brightness($hex, $percent) {
        // Work out if hash given
        $hash = '';
        if (stristr($hex,'#')) {
            $hex = str_replace('#','',$hex);
            $hash = '#';
        }
        /// HEX TO RGB
        $rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
        //// CALCULATE
        for ($i=0; $i<3; $i++) {
            // See if brighter or darker
            if ($percent > 0) {
                // Lighter
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
            } else {
                // Darker
                $positivePercent = $percent - ($percent*2);
                $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
            }
            // In case rounding up causes us to go to 256
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        //// RBG to Hex
        $hex = '';
        for($i=0; $i < 3; $i++) {
            // Convert the decimal digit to hex
            $hexDigit = dechex($rgb[$i]);
            // Add a leading zero if necessary
            if(strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            // Append to the hex string
            $hex .= $hexDigit;
        }
        return $hash.$hex;
    }
}

if(!function_exists('kt_color2hecxa')){
    /**
     * Convert color to hex
     *
     * @param $color
     * @return string
     */
    function kt_color2Hex($color){
        switch ($color) {
            case 'mulled_wine': $color = '#50485b'; break;
            case 'vista_blue': $color = '#75d69c'; break;
            case 'juicy_pink': $color = '#f4524d'; break;
            case 'sandy_brown': $color = '#f79468'; break;
            case 'purple': $color = '#b97ebb'; break;
            case 'pink': $color = '#fe6c61'; break;
            case 'violet': $color = '#8d6dc4'; break;
            case 'peacoc': $color = '#4cadc9'; break;
            case 'chino': $color = '#cec2ab'; break;
            case 'grey': $color = '#ebebeb'; break;
            case 'orange': $color = '#f7be68'; break;
            case 'sky': $color = '#5aa1e3'; break;
            case 'green': $color = '#6dab3c'; break;
            case 'accent': $color = kt_option('styling_accent', '#d0a852'); break;

        }
        return $color;
    }
}

if(!function_exists('video_youtube')) {
    /**
     * Video youtube Embed
     *
     * @param $video_id
     * @return string
     */
    function video_youtube($video_id)
    {
        return '<iframe src="http://www.youtube.com/embed/' . $video_id . '?wmode=transparent" ></iframe>';
    }
}


if(!function_exists('video_vimeo')) {
    /**
     * Video Vimeo Embed
     *
     * @param $video_id
     * @return string
     */
    function video_vimeo($video_id, $args = 'title=0&amp;byline=0&amp;portrait=0?wmode=transparent')
    {
        return '<iframe src="http://player.vimeo.com/video/' . $video_id . '?'.$args.'"></iframe>';
    }
}


if(!function_exists('video_embed')) {
    /**
     * Video Embed
     *
     * @param $video_id
     * @return string
     */
    function video_embed($video_id)
    {
        if (rwmb_meta('_kt_choose_video') == 'youtube') {
            return video_youtube($video_id);
        } else {
            return video_vimeo($video_id);
        }
    }
}
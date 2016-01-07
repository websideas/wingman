<?php


// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/*
 * Set up the content width value based on the theme's design.
 *
 * @see kt_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 1170;

add_action( 'after_setup_theme', 'kt_theme_setup' );
if ( ! function_exists( 'kt_theme_setup' ) ):
    function kt_theme_setup() {
        /**
         * Editor style.
         */
        add_editor_style();

        /**
         * Add default posts and comments RSS feed links to head
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * Enable support for Post Formats
         */
        add_theme_support( 'post-formats', array('gallery', 'link', 'image', 'quote', 'video', 'audio') );

        /*
        * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /**
         * Allow shortcodes in widgets.
         *
         */
        add_filter( 'widget_text', 'do_shortcode' );


        /**
         * Enable support for Post Thumbnails
         */
        add_theme_support( 'post-thumbnails' );


        if (function_exists( 'add_image_size' ) ) {
            add_image_size( 'kt_gird', 570, 410, true);
            add_image_size( 'kt_masonry', 570 );
            add_image_size( 'kt_small', 170, 170, true );
            add_image_size( 'kt_list', 1140, 610, true );
        }

        load_theme_textdomain( 'wingman', KT_THEME_DIR . '/languages' );

        /**
         * This theme uses wp_nav_menu() in one location.
         */
        register_nav_menus(array(
            'primary'   => esc_html__('Main menu', 'wingman'),
            'bottom'    => esc_html__( 'Bottom Menu', 'wingman' ),
        ));

    }
endif;



/**
 * Add stylesheet and script for frontend
 *
 * @since       1.0
 * @return      void
 * @access      public
 */

function kt_add_scripts() {

    wp_enqueue_style( 'mediaelement-style', get_stylesheet_uri(), array('mediaelement', 'wp-mediaelement') );
    wp_enqueue_style( 'bootstrap', KT_THEME_LIBS . 'bootstrap/css/bootstrap.min.css', array());
    wp_enqueue_style( 'kt-font-poppins', KT_THEME_FONTS . 'Poppins/stylesheet.min.css', array());
    wp_enqueue_style( 'font-awesome', KT_THEME_FONTS . 'font-awesome/css/font-awesome.min.css', array());
    wp_enqueue_style( 'kt-font-icomoon', KT_THEME_FONTS . 'Lineicons/style.min.css', array());
    wp_enqueue_style( 'kt-plugins', KT_THEME_CSS . 'plugins.css', array());

	// Load our main stylesheet.
    wp_enqueue_style( 'kt-style', KT_THEME_CSS . 'style.css', array( 'mediaelement-style' ) );
    wp_enqueue_style( 'kt-queries', KT_THEME_CSS . 'queries.css', array('kt-style') );
    
	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'kt-ie', KT_THEME_CSS . 'ie.css', array( 'kt-style' ) );
	wp_style_add_data( 'kt-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

    wp_register_script('google-maps-api','http://maps.googleapis.com/maps/api/js?sensor=false', array( 'jquery' ), null, false);
    wp_enqueue_script( 'bootstrap', KT_THEME_LIBS . 'bootstrap/js/bootstrap.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'kt-plugins', KT_THEME_JS . 'plugins.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'kt-main', KT_THEME_JS . 'functions.js', array( 'jquery', 'mediaelement', 'wp-mediaelement', 'jquery-ui-tabs' ), null, true );

    global $wp_query;
    wp_localize_script( 'kt-main', 'ajax_frontend', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'ajax_frontend' ),
        'current_date' => date_i18n('Y-m-d H:i:s'),
        'query_vars' => json_encode( $wp_query->query ),
        'days' => esc_html__('Days', 'wingman'),
        'hours' => esc_html__('Hours', 'wingman'),
        'minutes' => esc_html__('Minutes', 'wingman'),
        'seconds' => esc_html__('Seconds', 'wingman'),
    ));
    
}
add_action( 'wp_enqueue_scripts', 'kt_add_scripts' );


/**
 * Theme Custom CSS
 *
 * @since       1.0
 * @return      void
 * @access      public
 */
function kt_setting_script() {

    $css = '';
    $advanced_css = kt_option('advanced_editor_css');
    $accent = kt_option('styling_accent', '#82c14f');
    $styling_link = kt_option('styling_link');

    $css .= $advanced_css;
    if($styling_link['active']){
        $css .= 'a:focus{color: '.$styling_link['active'].';}';
    }

    if( $accent !='#82c14f' ){
        $css .= '::-moz-selection{color:#fff;background:'.$accent.'}';
        $css .= '::selection{color:#fff;background:'.$accent.'}';
        $selections_color = array(
            '.readmore-link',
            '.readmore-link:hover',
            '.testimonial-rate span:after',
            '.testimonial-carousel-skin-light .testimonial-item .testimonial-rate span::after',
            '.blog-posts .entry-title a:hover',
            '.pagination .page-numbers:hover',
            '.pagination .page-numbers:focus',
            '.pagination .page-numbers.current',
            '.post-single .tags-links a:hover',
            '.post-single .tags-links a:focus',
            '.widget_pages ul li a:hover',
            '.widget_pages ul li a:focus',
            '.widget_nav_menu ul li a:hover',
            '.widget_nav_menu ul li a:focus',
            '.widget_meta ul li a:hover',
            '.widget_meta ul li a:focus',
            '.widget_archive ul li a:hover',
            '.widget_archive ul li a:focus',
            '.widget_product_categories ul li a:hover',
            '.widget_product_categories ul li a:focus',
            '.widget_categories ul li a:hover',
            '.widget_categories ul li a:focus',
            '.widget_recent_comments ul li:hover a',
            '.widget_recent_entries ul li:hover a',
            '.uranus.tparrows:hover:before',
            '.uranus.tparrows:hover:after',
            '.team .team-attr .agency',
            '.wrapper-comingsoon.style2 .coming-soon .wrap .value-time',
            '.wrapper-comingsoon.style3 .coming-soon .wrap .value-time',
            '.widget_kt_twitter ul li .kt-twitter-tool',
            '.owl-carousel-kt.carousel-dark .owl-buttons > div:hover',
            '.owl-carousel-kt.carousel-light .owl-buttons > div:hover',
            '.comment-actions a:hover',
            '.comment-actions a:focus',
            '.kt-aboutwidget-title',
            '.kt-aboutwidget-socials a:hover',
            '.menu-bars-outer .menu-bars-items ul li a:hover',
            '.page-header .breadcrumbs a:hover',
            '#main-nav-tool li > a:hover',
            '.menu-bars-outer > a:hover',
            '.entry-share-box a:hover',
        );
        $css .= implode($selections_color, ',').'{color: '.$accent.';}';


        $selections_colors_important = array('.social-background-empty.social-style-accent a', '.social-background-outline.social-style-accent a');
        $css .= implode($selections_colors_important, ',').'{color: '.$accent.'!important;}';

        $selections_bg = array(
            '.btn-accent',
            '.pagination .page-numbers.current:before',
            '.pagination .page-numbers.current:after',
            '.btn-default:hover',
            '.btn-default:focus',
            '.btn-default:active',
            '.widget_rss ul li:hover:after',
            '.widget_recent_comments ul li:hover:after',
            '.widget_recent_entries ul li:hover:after',
            '.kt_flickr a:after',
            '.owl-carousel-kt .owl-pagination .owl-page.active',
            '.owl-carousel-kt .owl-pagination .owl-page:hover',
            '#cancel-comment-reply-link:hover',
            '#back-to-top',
            '#calendar_wrap table tbody td#today',
            '#calendar_wrap table thead td#today',
            '.widget_nav_menu ul li a:hover:after',
            '.widget_pages ul li a:hover:after',
            '.widget_product_categories ul li a:hover:after',
            '.widget_categories ul li a:hover:after',
            '.widget_archive ul li a:hover:after',
            '.widget_meta ul li a:hover::after',
            '.kt-skill-wrapper .kt-skill-item-wrapper .kt-skill-bg-accent .kt-skill-bar',
            '#main-nav-tool li.mini-cart > a span',
            '#footer-area h3.widget-title:after',
            '#search-fullwidth .searchform .postform',
            '#footer-area h3.widget-title:before',
            '.readmore-link:before',
            '.readmore-link:after'
        );
        $css .= implode($selections_bg, ',').'{background: '.$accent.';}';

        $selections_bg_important = array('.social-background-fill.social-style-accent a');
        $css .= implode($selections_bg_important, ',').'{background: '.$accent.'!important;}';


        $selections_border = array(
            'blockquote.blockquote-reverse',
            '.blockquote.blockquote-reverse',
            '.social-background-empty.social-style-accent a',
            '.social-background-outline.social-style-accent a',
            '.owl-carousel-kt.carousel-dark .owl-buttons > div:hover',
            '.owl-carousel-kt.carousel-light .owl-buttons > div:hover',
            '.btn-accent',
            '.post-single .tags-links a:hover',
            '.post-single .tags-links a:focus',
            'blockquote, .blockquote',
            '.comment-actions a:hover',
            '.comment-actions a:focus',
            '.btn-default:hover',
            '.btn-default:focus',
            '.btn-default:active',
            '.menu-bars-outer .menu-bars-items',
        );
        $css .= implode($selections_border, ',').'{border-color: '.$accent.';}';
    }

    $color_first_loader = kt_option('color_first_loader', $accent);
    $css .= '.kt_page_loader.style-1 .page_loader_inner{border-color: '.$color_first_loader.';}';
    $css .= '.kt_page_loader.style-1 .kt_spinner{background-color: '.$color_first_loader.';}';

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
            $css .=  '#content{padding-top: '.$pageh_spacing.';}';
        }
        $pageh_spacing = rwmb_meta('_kt_page_bottom_spacing', array(), $post_id);
        if($pageh_spacing != ''){
            $css .= '#content{padding-bottom:'.$pageh_spacing.';}';
        }

        $pageh_top = rwmb_meta('_kt_page_header_top', array(), $post_id);
        if($pageh_top != ''){
            $css .=  'div.page-header{padding-top: '.$pageh_top.';}';
        }

        $pageh_bottom = rwmb_meta('_kt_page_header_bottom', array(), $post_id);
        if($pageh_bottom != ''){
            $css .=  'div.page-header{padding-bottom: '.$pageh_bottom.';}';
        }

        $pageh_title_color = rwmb_meta('_kt_page_header_title_color', array(), $post_id);
        if($pageh_title_color != ''){
            $css .= 'div.page-header h1.page-header-title{color:'.$pageh_title_color.';}';
            $css .= '.page-header h1.page-header-title::before, .page-header h1.page-header-title::after{background:'.$pageh_title_color.';}';
        }

        $pageh_subtitle_color = rwmb_meta('_kt_page_header_subtitle_color', array(), $post_id);
        if($pageh_subtitle_color != ''){
            $css .= 'div.page-header .page-header-subtitle{color:'.$pageh_subtitle_color.';}';
        }

        $pageh_breadcrumbs_color = rwmb_meta('_kt_page_header_breadcrumbs_color', array(), $post_id);
        if($pageh_breadcrumbs_color != ''){
            $css .= 'div.page-header .breadcrumbs,div.page-header .breadcrumbs a{color:'.$pageh_breadcrumbs_color.';}';
        }
    }


    if($navigation_space = kt_option('navigation_space', 30)){
        $css .= '#main-navigation > li{margin-left: '.$navigation_space.'px;}#main-navigation > li:first-child {margin-left: 0;}#main-navigation > li:last-child {margin-right: 0;}';
    }
    if($navigation_color_hover = kt_option('navigation_color_hover')){
        $css .= '#main-navigation > li > a:before, #main-navigation > li > a:after{background: '.$navigation_color_hover.';}';
    }
    if($mega_title_color = kt_option('mega_title_color')){
        $css .= '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:after, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > span:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > span:after, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > .widget-title:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > .widget-title:after{background-color: '.$mega_title_color.';}';
    }
    if($mega_title_color_hover = kt_option('mega_title_color_hover')){
        $css .= '#main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:hover:before, #main-navigation > li > .kt-megamenu-wrapper > .kt-megamenu-ul > li > a:hover:after{background-color: '.$mega_title_color_hover.';}';
    }

    $navigation_height = kt_option('navigation_height');
    if(!$navigation_height['height'] || $navigation_height['height'] == 'px'){
        $navigation_height['height'] = 60;
    }
    $css .= '#main-navigation > li{line-height: '.intval($navigation_height['height']).'px;}';
    $css .= '.header-container.is-sticky.sticky-header-down .nav-container .nav-container-inner,.header-container.header-layout2.is-sticky.sticky-header-down #header-content{top: -'.intval($navigation_height['height']).'px;}';

    $header_sticky_opacity = kt_option('header_sticky_opacity', 0.8);
    $css .= '.header-sticky-background{opacity:'.$header_sticky_opacity.';}';

    $navigation_height_fixed = kt_option('navigation_height_fixed');

    if(!$navigation_height_fixed['height'] || $navigation_height_fixed['height'] == 'px'){
        $navigation_height_fixed['height'] = 60;
    }
    $css .= '.header-container.is-sticky #main-navigation > li{line-height: '.intval($navigation_height_fixed['height']).'px;}';

    wp_add_inline_style( 'kt-style', $css );

}
add_action('wp_enqueue_scripts', 'kt_setting_script');


/**
 * Add scroll to top
 *
 */
add_action( 'theme_before_footer_top', 'theme_after_footer_top_addscroll' );
function theme_after_footer_top_addscroll(){
    echo "<a href='#top' id='backtotop'></a>";
}



function kt_excerpt_length( ) {
    return kt_option('archive_excerpt_length', 30);
}
add_filter( 'excerpt_length', 'kt_excerpt_length', 99 );



if ( ! function_exists( 'kt_comment_nav' ) ) :
    /**
     * Display navigation to next/previous comments when applicable.
     *
     */
    function kt_comment_nav() {
        // Are there comments to navigate through?
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            ?>
            <nav class="navigation comment-navigation clearfix">
                <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wingman' ); ?></h2>
                <div class="nav-links">
                    <?php

                    if ( $prev_link = get_previous_comments_link( '<i class="fa fa-angle-double-left"></i> '.esc_html__( 'Older Comments', 'wingman' ) ) ) :
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    endif;

                    if ( $next_link = get_next_comments_link( '<i class="fa fa-angle-double-right"></i> '.esc_html__( 'Newer Comments',  'wingman' ) ) ) :
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    endif;

                    ?>
                </div><!-- .nav-links -->
            </nav><!-- .comment-navigation -->
        <?php
        endif;
    }
endif;

function kt_get_post_thumbnail_url($size = 'post-thumbnail', $post_id = null){
    global $post;
        if(!$post_id) $post_id = $post->ID;

    $image_url = false;
    if(has_post_thumbnail($post_id)){
        $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
        if($attachment_image){
            $image_url = $attachment_image[0];
        }
    }else{
        $image_url = apply_filters( 'kt_placeholder', $size );
    }
    return $image_url;
}


if ( ! function_exists( 'kt_post_thumbnail_image' ) ) :
    /**
     * Display an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     *
     */
    function kt_post_thumbnail_image($size = 'post-thumbnail', $class_img = '', $link = true, $placeholder = true, $echo = true) {
        if ( post_password_required() || is_attachment()) {
            return;
        }
        $class = 'entry-thumb';

        if(!has_post_thumbnail() && $placeholder){
            $class .= ' no-image';
        }

        if(!$echo){
            ob_start();
        }

        $attrs = array(
            'class' =>  'class="'.esc_attr($class).'"'
        );

        if( $link ){
            $tag = 'a';
            $attrs['href'] = 'href="'.get_the_permalink().'"';
        } else{
            $tag = 'div';
        }

        if(has_post_thumbnail() || $placeholder){ ?>
            <<?php echo esc_attr($tag) ?> <?php echo implode($attrs, ' ') ?>>
            <?php if(has_post_thumbnail()){ ?>
                <?php the_post_thumbnail( $size, array( 'alt' => get_the_title(), 'class' => $class_img ) ); ?>
            <?php }elseif($placeholder){ ?>
                <?php
                    $image = apply_filters( 'kt_placeholder', $size );
                    printf(
                        '<img src="%s" alt="%s" class="%s"/>',
                        esc_url($image),
                        esc_html__('No image', 'wingman'),
                        esc_attr($class_img.' no-image')
                    )
                ?>
            <?php } ?>
            </<?php echo esc_attr($tag) ?>><!-- .entry-thumb -->
        <?php }

        if(!$echo){
            return ob_get_clean();
        }
    }
endif;


if ( ! function_exists( 'kt_post_thumbnail' ) ) :
    /**
     * Display an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     *
     */
    function kt_post_thumbnail($size = 'post-thumbnail', $class_img = '', $link = true, $placeholder = true) {
        if ( post_password_required() || is_attachment()) {
            return;
        }
        $format = get_post_format();

        if(has_post_thumbnail() && ($format == '' || $format == 'image')){ ?>

            <?php if ( $link ){ ?>
                <a href="<?php the_permalink(); ?>" aria-hidden="true" class="entry-thumb">
            <?php }else{ ?>
                <div class="entry-thumb">
            <?php } ?>
                <?php the_post_thumbnail( $size, array( 'alt' => get_the_title(), 'class' => $class_img ) ); ?>
            <?php if ( $link ){ ?>
                </a>
            <?php }else{ ?>
                </div><!-- .entry-thumb -->
            <?php } ?>

        <?php }elseif(!has_post_thumbnail() && ($format == '' || $format == 'image') && $placeholder){ ?>
            <?php $image = apply_filters( 'kt_placeholder', $size ); ?>
            <?php if ( $link ){ ?>
                <a href="<?php the_permalink(); ?>" aria-hidden="true" class="entry-thumb no-image">
            <?php }else{ ?>
                <div class="entry-thumb no-image">
            <?php } ?>

            <?php
                printf(
                    '<img src="%s" alt="%s" class="%s"/>',
                    $image,
                    esc_html__('No image', 'wingman'),
                    $class_img
                );
            ?>
            <?php if ( $link ){ ?>
                </a>
            <?php }else{ ?>
                </div><!-- .entry-thumb -->
            <?php } ?>
        <?php }elseif($format == 'gallery'){
            $type = rwmb_meta('_kt_gallery_type');
            if($type == 'rev' && class_exists( 'RevSlider' )){
                if ($rev = rwmb_meta('_kt_gallery_rev_slider')) {
                    echo '<div class="entry-thumb">';
                    putRevSlider($rev);
                    echo '</div><!-- .entry-thumb -->';
                }
            }elseif($type == 'layer' && is_plugin_active( 'LayerSlider/layerslider.php' ) ) {
                if($layerslider = rwmb_meta('_kt_gallery_layerslider')){
                    echo '<div class="entry-thumb">';
                    echo do_shortcode('[layerslider id="'.rwmb_meta('_kt_gallery_layerslider').'"]');
                    echo '</div><!-- .entry-thumb -->';
                }
            }elseif($type == ''){
                $images = get_galleries_post('_kt_gallery_images', $size);
                if($images){
                    echo '<div class="entry-thumb">';
                    $galleries_html = '';
                    foreach($images as $image){
                        $galleries_html .= '<div class="recent-posts-item"><img src="'.$image['url'].'" alt="" /></div>';
                    }
                    $atts = array( "pagination"=> false, "navigation"=> false, "desktop"=> 1, "desktopsmall" => 1, "tablet" => 1, "mobile" => 1);
                    $carousel_ouput = kt_render_carousel($atts);
                    echo str_replace('%carousel_html%', $galleries_html, $carousel_ouput);

                    echo '</div><!-- .entry-thumb -->';
                }
            }
        }elseif($format == 'video'){
            $type = rwmb_meta('_kt_video_type');
            if($type == 'upload'){
                $mp4 = kt_get_single_file('_kt_video_file_mp4');
                $webm = kt_get_single_file('_kt_video_file_webm');
                if($mp4 || $webm){
                    $video_shortcode = "[video ";
                    if($mp4) $video_shortcode .= 'mp4="'.$mp4.'" ';
                    if($webm) $video_shortcode .= 'webm="'.$webm.'" ';
                    $video_shortcode .= "]";
                    echo '<div class="entry-thumb">'.do_shortcode($video_shortcode).'</div><!-- .entry-thumb -->';
                }

            }elseif($type == 'external'){
                if($video_link = rwmb_meta('_kt_video_id')){
                    $embed = video_embed( $video_link );
                    echo '<div class="entry-thumb"><div class="embed-responsive embed-responsive-16by9">'.do_shortcode($embed).'</div></div><!-- .entry-thumb -->';
                }
            }
        }elseif($format == 'audio'){
            $type = rwmb_meta('_kt_audio_type');
            if($type == 'upload'){
                if($audios = rwmb_meta('_kt_audio_mp3', 'type=file')){
                    foreach($audios as $audio) {
                        echo '<div class="entry-thumb">';
                            if(has_post_thumbnail()){
                                the_post_thumbnail( $size, array( 'alt' => get_the_title(), 'class' => $class_img ) );
                            }
                            echo '<div class="entry-thumb-audio">';
                            echo do_shortcode('[audio src="'.$audio['url'].'"][/audio]');
                            echo '</div><!-- .entry-thumb-audio -->';
                        echo '</div><!-- .entry-thumb -->';
                    }
                }
            } elseif( $type == 'soundcloud' ){
                if($soundcloud = rwmb_meta('_kt_audio_soundcloud')){
                    echo '<div class="entry-thumb">';
                    echo do_shortcode($soundcloud);
                    echo '</div><!-- .entry-thumb -->';
                }
            }
        } elseif ( $format == 'link' ){ ?>
            <div class="entry-thumb post-link-wrapper">
                <div class="entry-thumb-content post-link-outer">
                    <div class="post-link-content">
                        <div class="post-link-title"><?php the_title(); ?></div>
                        <div class="post-link-url">
                            <a href="<?php echo rwmb_meta('_kt_external_url'); ?>"><?php echo rwmb_meta('_kt_external_url'); ?></a>
                        </div>
                    </div>
                </div><!-- .entry-thumb-content -->
                <?php if ( $link ){ ?>
                    <a href="<?php the_permalink(); ?>" aria-hidden="true" class="post-link-link"><?php the_title(); ?></a>
                <?php } ?>
            </div><!-- .post-link-wrapper -->

        <?php } elseif ( $format == 'quote' ){ ?>
            <div class="entry-thumb post-quote-wrapper">
                <blockquote class="classic">
                    <div class="blockquote-content">
                        <p><?php echo rwmb_meta('_kt_quote_content'); ?></p>
                        <footer><?php echo rwmb_meta('_kt_quote_author'); ?></footer>
                    </div>
                </blockquote>
                <?php if ( $link ){ ?>
                    <a href="<?php the_permalink(); ?>" aria-hidden="true" class="post-quote-link"><?php the_title(); ?></a>
                <?php } ?>
            </div><!-- .post-quote-wrapper -->
        <?php }
    }
endif;

/**
 *
 * Custom call back function for default post type
 *
 * @param $comment
 * @param $args
 * @param $depth
 */
function kt_comments($comment, $args, $depth) {
?>

<li <?php comment_class('comment'); ?> id="li-comment-<?php comment_ID() ?>">
    <div  id="comment-<?php comment_ID(); ?>" class="comment-item clearfix">

        <div class="comment-avatar">
            <?php echo get_avatar($comment->comment_author_email, $size='100',$default='' ); ?>
        </div>
        <div class="comment-content">
            <div class="comment-meta">
                <span class="comment-date"><?php printf( '%1$s' , get_comment_date( 'M d, Y ')); ?></span>
                <h5 class="author_name">
                    <?php comment_author_link(); ?>
                </h5>
            </div>
            <div class="comment-entry entry-content">
                <?php comment_text() ?>
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php esc_html_e('Your comment is awaiting moderation.', 'wingman') ?></em>
                <?php endif; ?>
            </div>
            <div class="comment-actions clear">
                <?php edit_comment_link( '<span class="icon-pencil"></span> '.esc_html__('Edit', 'wingman'),'  ',' ') ?>
                <?php comment_reply_link( array_merge( $args,
                    array('depth' => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text' =>'<span class="icon-action-undo"></span> '.esc_html__('Reply', 'wingman')
                    ))) ?>
            </div>
        </div>
    </div>
<?php
}


if ( ! function_exists( 'kt_post_nav' ) ) :
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function kt_post_nav( ) {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
        $next     = get_adjacent_post( false, '', false );

        if ( ! $next && ! $previous ) return;
        ?>
        <nav class="navigation post-navigation clearfix">
            <div class="nav-links">
                <?php

                    if(!get_previous_post_link('&laquo; %link', '', true)){
                        printf('<div class="nav-previous meta-nav"><span>%s</span></div>', sprintf('<span>%s</span>', esc_html__( 'Previous Article', 'wingman' ) ) );
                    }else{
                        previous_post_link('<div class="nav-previous meta-nav">%link</div>', sprintf('<span>%s</span>', esc_html__( 'Previous Article', 'wingman' ) ), TRUE);
                    }

                    if(!get_next_post_link('&laquo; %link', '', true)){
                        printf('<div class="nav-next meta-nav"><span>%s</span></div>', sprintf('<span>%s</span>', esc_html__( 'Next Article', 'wingman' ) ) );
                    }else{
                        next_post_link('<div class="nav-next meta-nav">%link</div>', sprintf('<span>%s</span>', esc_html__( 'Next Article', 'wingman' )), TRUE);
                    }
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
    <?php
}
endif;



if ( ! function_exists( 'kt_paging_nav' ) ) :
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function kt_paging_nav( $type = 'classic' ) {

        global $wp_query;

        // Don't print empty markup if there's only one page.
        if ( $wp_query->max_num_pages < 2 ) {
            return;
        }

        if($type == 'none'){
            return ;
        }elseif($type == 'loadmore'){
            printf(
                '<div class="blog-posts-loadmore"><a href="#" class="blog-loadmore-button btn btn-default">%s %s</a></div>',
                '<span class="fa fa-refresh button-icon-left"></span>',
                esc_html__('Load more', 'wingman')
            );
        }elseif($type == 'normal'){ ?>

            <nav class="navigation paging-navigation clearfix">
                <h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'wingman' ); ?></h1>
                <div class="nav-links">

                    <?php if ( get_next_posts_link() ) : ?>
                        <div class="nav-previous"><?php next_posts_link( '<i class="fa fa-long-arrow-left"></i> '.esc_html__( 'Older posts', 'wingman' ) ); ?></div>
                    <?php endif; ?>

                    <?php if ( get_previous_posts_link() ) : ?>
                        <div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'wingman' ).' <i class="fa fa-long-arrow-right"></i>' ); ?></div>
                    <?php endif; ?>

                </div><!-- .nav-links -->
            </nav><!-- .navigation -->

        <?php }else{
            the_posts_pagination(array(
                'prev_text' => sprintf('<span class="screen-reader-text">%s</span>%s', esc_html__('Previous', 'wingman'), '<i class="fa fa-long-arrow-left"></i>'),
                'next_text' => sprintf('<span class="screen-reader-text">%s</span>%s', esc_html__('Next', 'wingman'), '<i class="fa fa-long-arrow-right"></i>'),
                'before_page_number' => '',
            ));
        }
    }
endif;



if ( ! function_exists( 'kt_entry_meta_author' ) ) :
    /**
     * Prints HTML with meta information for author.
     *
     */
    function kt_entry_meta_author() {
        printf( '<span class="author vcard">%4$s <span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span>',
            _x( 'Author', 'Used before post author name.', 'wingman' ),
            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
            get_the_author(),
            esc_html__('By:', 'wingman' )
        );
    }
endif;

if ( ! function_exists( 'kt_entry_meta_categories' ) ) :
    /**
     * Prints HTML with meta information for categories.
     *
     */
    function kt_entry_meta_categories( $separator = ', ', $echo = true ) {
        if ( 'post' == get_post_type() ) {
            $categories_list = get_the_category_list( $separator );
            if ( $categories_list ) {
                if($echo){
                    printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s %3$s</span>',
                        _x( 'Categories', 'Used before category names.', 'wingman' ),
                        esc_html__('in', 'wingman'),
                        $categories_list
                    );
                }else{
                    return sprintf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s %3$s</span>',
                        _x( 'Categories', 'Used before category names.', 'wingman' ),
                        esc_html__('in', 'wingman'),
                        $categories_list
                    );
                }
            }
        }
    }
endif;

if ( ! function_exists( 'kt_entry_meta_tags' ) ) :
    /**
     * Prints HTML with meta information for tags.
     *
     */
    function kt_entry_meta_tags($before = '', $after = '') {
        if ( 'post' == get_post_type() ) {
            $tags_list = get_the_tag_list( '', ', ');
            if ( $tags_list ) {
                printf( '%3$s<span class="tags-links"><span class="tags-links-text">%1$s</span> %2$s</span>%4$s',
                    _x( 'Tags: ', 'Used before tag names.', 'wingman' ),
                    $tags_list,
                    $before,
                    $after
                );
            }
        }
    }
endif;



if ( ! function_exists( 'kt_entry_meta_comments' ) ) :
    /**
     * Prints HTML with meta information for comments.
     *
     */
    function kt_entry_meta_comments() {
        if (! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link( esc_html__( 'No Comments', 'wingman' ), esc_html__( '1 Comment', 'wingman' ), esc_html__( '% Comments', 'wingman' ) );
            echo '</span>';
        }
    }
endif;

if ( ! function_exists( 'kt_entry_meta_time' ) ) :
    /**
     * Prints HTML with meta information for time.
     *
     */
    function kt_entry_meta_time($format = 'd F Y', $echo = true) {
        if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
            $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

            if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
                $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
            }

            $time_show = ($format == 'time') ? human_time_diff( get_the_time('U'), current_time('timestamp') ) . esc_html__(' ago', 'wingman') : get_the_date($format);

            $time_string = sprintf( $time_string,
                esc_attr( get_the_date( 'c' ) ),
                $time_show,
                esc_attr( get_the_modified_date( 'c' ) ),
                get_the_modified_date()
            );
            if($echo){
                printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                    _x( 'Posted on', 'Used before publish date.', 'wingman' ),
                    $time_string
                );
            }else{
                return sprintf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                    _x( 'Posted on', 'Used before publish date.', 'wingman' ),
                    $time_string
                );
            }

        }
    }
endif;

/* ---------------------------------------------------------------------------
 * Posts Views Number
 * --------------------------------------------------------------------------- */

if ( ! function_exists( 'kt_get_post_views' ) ){
    function kt_get_post_views($postID){
        $count_key = 'kt_post_views_count';
        $count = get_post_meta($postID, $count_key, true);

        if( $count == '' || $count == 0 ){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            $count = 0;
        }
        $text = ($count == 0 || $count == 1) ? esc_html__('View','wingman') : esc_html__('Views','wingman');

        printf('<span class="post-view"><i class="fa fa-eye"></i> %s %s </span>', $count, $text);
    }
}


if ( ! function_exists( 'kt_like_post' ) ){
    function kt_like_post( $before = '', $after = '', $post_id = null ) {
        global $post;
        if(!$post_id){ $post_id = $post->ID; }

        $like_count = get_post_meta($post_id, '_like_post', true);

        if( !$like_count ){
            $like_count = 0;
            add_post_meta($post_id, '_like_post', $like_count, true);
        }

        $text = ($like_count == 0 || $like_count == 1) ? esc_html__('like','wingman') : esc_html__('likes','wingman');

        $class = 'kt_likepost';

        if( isset($_COOKIE['like_post_'. $post_id]) ){
            $class .= ' liked';
        }

        printf(
            '%s<a data-id="%s" href="%s" class="%s">%s</a>%s',
            $before,
            $post_id,
            get_the_permalink($post_id)."#".$post_id,
            $class,
            $text,
            $after
        );
    }
}



/* ---------------------------------------------------------------------------
 * Entry author [entry_author]
 * --------------------------------------------------------------------------- */
if ( ! function_exists( 'kt_author_box' ) ) :
    /**
     * Prints HTML with information for author box.
     *
     */
    function kt_author_box() {
        ?>

        <div class="author-info">
            <div class="author-avatar">
                <?php
                    $author_bio_avatar_size = apply_filters( 'kt_author_bio_avatar_size', 165 );
                    echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
                ?>
            </div><!-- .author-avatar -->
            <div class="author-description">
                <h2 class="author-title">
                    <a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php echo esc_attr(sprintf( __( 'View all posts by %s', 'wingman' ), get_the_author() ) ); ?>">
                        <?php printf( esc_html__( 'About %s', 'wingman' ), get_the_author() ); ?>
                    </a>
                </h2>
                <?php
                    $googleplus = get_the_author_meta('googleplus');
                    $url = get_the_author_meta('url');
                    $twitter = get_the_author_meta('twitter');
                    $facebook = get_the_author_meta('facebook');
                    $pinterest = get_the_author_meta('pinterest');
                    $instagram = get_the_author_meta('instagram');
                    $tumblr = get_the_author_meta('tumblr');
                ?>
                <?php if($facebook || $twitter || $pinterest || $googleplus || $instagram || $tumblr || $url){ ?>
                <p class="author-social">
                    <?php if($facebook){ ?>
                        <a href="<?php echo esc_url($facebook); ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                    <?php } ?>
                    <?php if($twitter){ ?>
                        <a href="http://www.twitter.com/<?php echo esc_attr($twitter); ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                    <?php } ?>
                    <?php if($pinterest){ ?>
                        <a href="http://www.pinterest.com/<?php echo esc_attr($pinterest); ?>" target="_blank"><i class="fa fa-pinterest"></i></a>
                    <?php } ?>
                    <?php if($googleplus){ ?>
                        <a href="<?php echo esc_url($googleplus); ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
                    <?php } ?>
                    <?php if($instagram){ ?>
                        <a href="http://instagram.com/<?php echo esc_attr($instagram); ?>" target="_blank"><i class="fa fa-instagram"></i></a>
                    <?php } ?>
                    <?php if($tumblr){ ?>
                        <a href="http://<?php echo esc_attr($instagram); ?>.tumblr.com/" target="_blank"><i class="fa fa-tumblr"></i></a>
                    <?php } ?>
                    <?php if($url){ ?>
                        <a href="<?php echo esc_url($url); ?>" target="_blank"><i class="fa fa-globe"></i></a>
                    <?php } ?>
                </p>
                <?php } ?>
                <div class="author-bio">
                    <?php
                    if($description = get_the_author_meta('description')){
                        printf('<p class="author-description-content">%s</p>', $description);
                    }
                    ?>
                </div>
            </div><!-- .author-description -->
        </div><!-- .author-info -->

    <?php }
endif;




/* ---------------------------------------------------------------------------
 * Share Box [share_box]
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'kt_share_box' ) ){
    function kt_share_box($post_id = null, $style = "", $class = ''){
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
                    $html .= '<a class="'.esc_attr($style).'" href="#" onclick="popUp=window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $title . '&amp;p[url]=' . $link.'\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');popUp.focus();return false;">';
                    $html .= '<i class="fa fa-facebook"></i>';
                    $html .= '</a>';
                }elseif($key == 'twitter'){
                    // Twitter
                    $html .= '<a class="'.esc_attr($style).'" href="#" onclick="popUp=window.open(\'http://twitter.com/home?status=' . $link . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                    $html .= '<i class="fa fa-twitter"></i>';
                    $html .= '</a>';
                }elseif($key == 'google_plus'){
                    // Google plus
                    $html .= '<a class="'.esc_attr($style).'" href="#" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . $link . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-google-plus"></i>';
                    $html .= "</a>";
                }elseif($key == 'pinterest'){
                    // Pinterest
                    $html .= '<a class="'.esc_attr($style).'" href="#" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . $link . '&amp;description=' . $title . '&amp;media=' . urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-pinterest"></i>';
                    $html .= "</a>";
                }elseif($key == 'linkedin'){
                    // linkedin
                    $html .= '<a class="'.esc_attr($style).'" href="#" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . $link . '&amp;title=' . $title. '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-linkedin"></i>';
                    $html .= "</a>";
                }elseif($key == 'tumblr'){
                    // Tumblr
                    $html .= '<a class="'.esc_attr($style).'" href="#" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . $link . '&amp;name=' . $title . '&amp;description=' . $excerpt . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    $html .= '<i class="fa fa-tumblr"></i>';
                    $html .= "</a>";
                }elseif($key == 'email'){
                    // Email
                    $html .= '<a class="'.esc_attr($style).'" href="mailto:?subject='.$title.'&amp;body='.$link.'">';
                    $html .= '<i class="fa fa-envelope-o"></i>';
                    $html .= "</a>";
                }
            }
        }
        if($html){
            printf(
                '<div class="entry-share-box %s">%s</div>',
                $class,
                $html
            );
        }
    }
}


/* ---------------------------------------------------------------------------
 * Related Article [related_article]
 * --------------------------------------------------------------------------- */
if ( ! function_exists( 'kt_related_article' ) ) :
    function kt_related_article($post_id = null, $type = 'categories'){
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $blog_columns = 3;
        $posts_per_page = kt_option('blog_related_sidebar', 3);

        $args = array(
            'posts_per_page' => $posts_per_page,
            'post__not_in' => array($post_id)
        );
        if($type == 'tags'){
            $tags = wp_get_post_tags($post_id);
            if(!$tags) return false;
            $tag_ids = array();
            foreach($tags as $tag)
                $tag_ids[] = $tag->term_id;
            $args['tag__in'] = $tag_ids;
        }elseif($type == 'author'){
            $args['author'] = get_the_author();
        }else{
            $categories = get_the_category($post_id);
            if(!$categories) return false;
            $category_ids = array();
            foreach($categories as $category)
                $category_ids[] = $category->term_id;
            $args['category__in'] = $category_ids;
        }
        $query = new WP_Query( $args );
        ?>
        <?php if($query->have_posts()){ ?>
            <div id="related-article">
                <h3 class="title-article"><?php esc_html_e('Related Article', 'wingman'); ?></h3>
                <div class="row">
                    <?php

                    $blog_atts_posts = array(
                        'image_size' => 'recent_posts',
                        'readmore' => false,
                        "show_author" => false,
                        "show_category" => true,
                        "show_comment" => false,
                        "show_date" => true,
                        'show_meta' => true,
                        "show_like_post" => false,
                        "date_format" => 'M d Y',
                        'thumbnail_type' => 'image',
                        "class" => '',
                        'show_view_number' => false,
                        'show_excerpt' => false,
                        'type' => ''
                    );

                    $i = 1;
                    $bootstrapColumn = round( 12 / $blog_columns );
                    $classes = 'col-xs-12 col-sm-6 col-md-' . $bootstrapColumn;
                    ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <?php
                            $blog_atts = $blog_atts_posts;
                        ?>
                        <div class="article-post-item <?php echo esc_attr($classes) ?>">
                            <?php kt_get_template_part( 'templates/blog/layout/content', get_post_format(), $blog_atts); ?>
                        </div><!-- .article-post-item -->
                    <?php $i++; endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div><!-- #related-article -->
        <?php } ?>
    <?php }
endif;



if(!function_exists('kt_setting_script_footer')){
    /**
     * Add advanced js to footer
     *
     */
    function kt_setting_script_footer() {
        $advanced_js = kt_option('advanced_editor_js');
        if($advanced_js){
            printf('<script type="text/javascript">%s</script>', $advanced_js);
        }
    }
    add_action('wp_footer', 'kt_setting_script_footer', 100);
}


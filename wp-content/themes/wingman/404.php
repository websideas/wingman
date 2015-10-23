<?php
/**
 * The template for displaying error 404
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */


$type = kt_option('notfound_page_type', 'default');
/* Redirect Home */
if( $type == 'home'){
    wp_redirect( home_url() ); exit;
}

get_header(); ?>
    <div class="container">
        <?php
        /**
         * @hooked
         */
        do_action( 'theme_before_main' ); ?>
        <div id="main">
            <?php
                if($type == 'page'){
                    if($page_id = kt_option('notfound_page_id')){
                        $page = get_post($page_id);
                        echo apply_filters( "the_content", $page->post_content );
                    }else{
                        get_template_part( 'content',  '404');
                    }
                }else{
                    get_template_part( 'content',  '404');
                }
            ?>
        </div><!-- #main -->
        <?php
        /**
         * @hooked
         */
        do_action( 'theme_after_main' ); ?>
    </div><!-- .container -->
<?php get_footer(); ?>
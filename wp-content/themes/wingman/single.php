<?php
/**
 * The template for displaying single post
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */

$sidebar = kt_get_single_sidebar();
get_header();
?>
    <div class="container">
        <?php do_action( 'kt_before_main' ); ?>
        <?php
            $show_post_format = kt_post_option(null, '_kt_post_format', 'blog_post_format', 1);
            $post_layout = kt_post_option(null, '_kt_blog_post_layout', 'blog_layout', 1);
        ?>
        <?php if( ! post_password_required( ) && $show_post_format && $post_layout == 2 ){ ?>
            <?php $imagesize = kt_post_option(null, '_kt_blog_image_size', 'blog_image_size', 'blog_post'); ?>
            <div class="entry-thumb-fullwidth"><?php kt_post_thumbnail($imagesize, 'img-responsive', false, false); ?></div>
        <?php } ?>

        <div class="row">
            <?php $main_class = apply_filters('kt_main_class', 'main-class', $sidebar['sidebar']); ?>
            <div id="main" class="<?php echo esc_attr($main_class) ; ?>">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'content', 'single' );  ?>
                <?php endwhile; // end of the loop. ?>
            </div>
            <?php if($sidebar['sidebar'] != 'full'){ ?>
                <?php $sidebar_class = apply_filters('kt_sidebar_class', 'sidebar', $sidebar['sidebar']); ?>
                <div class="<?php echo esc_attr($sidebar_class); ?>">
                    <?php dynamic_sidebar($sidebar['sidebar_area']); ?>
                </div><!-- .sidebar -->
            <?php } ?>
        </div><!-- .row -->
        <?php do_action( 'kt_after_main' ); ?>
    </div><!-- .container -->
<?php get_footer(); ?>
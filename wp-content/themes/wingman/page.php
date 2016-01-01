<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */



$sidebar = kt_get_page_sidebar();

get_header(); ?>
    <div class="container">
        <?php do_action( 'kt_before_main' ); ?>
        <div class="row">
            <?php $main_class = apply_filters('kt_main_class', 'main-class', $sidebar['sidebar']); ?>
            <div id="main" class="<?php echo esc_attr($main_class) ; ?>">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'content', 'page' ); ?>
                <?php endwhile; ?>
            </div><!-- #main -->
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
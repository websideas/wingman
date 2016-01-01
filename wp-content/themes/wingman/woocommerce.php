<?php
/**
 * The template for displaying Shop
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */

$sidebar = kt_get_woo_sidebar();

get_header(); ?>
    <?php
		do_action( 'woocommerce_before_main_content' );
        do_action( 'kt_before_main' );
	?>
    <div class="row">
        <?php $main_class = apply_filters('kt_main_class', 'main-class', $sidebar['sidebar']); ?>
        <div id="main" class="<?php echo esc_attr($main_class) ; ?>">
            <?php woocommerce_content(); ?>
        </div>
        <?php if($sidebar['sidebar'] != 'full'){ ?>
            <?php $sidebar_class = apply_filters('kt_sidebar_class', 'sidebar', $sidebar['sidebar']); ?>
            <div class="<?php echo esc_attr($sidebar_class); ?>">
                <?php dynamic_sidebar($sidebar['sidebar_area']); ?>
            </div><!-- .sidebar -->
        <?php } ?>
    </div><!-- .row -->
    <?php
        do_action( 'kt_after_main' );
        do_action( 'woocommerce_after_main_content' );
    ?>
<?php get_footer(); ?>
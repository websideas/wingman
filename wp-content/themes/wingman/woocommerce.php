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
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 (removed)
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
    <?php
    /**
     * @hooked
     */
    do_action( 'theme_before_main' ); ?>
        
    <div class="row">
        <div id="main" class="<?php echo apply_filters('kt_main_class', 'main-class', $sidebar['sidebar']); ?>">
            <?php woocommerce_content(); ?>
        </div>
        <?php if($sidebar['sidebar'] != 'full'){ ?>
            <div class="<?php echo apply_filters('kt_sidebar_class', 'sidebar', $sidebar['sidebar']); ?>">
                <?php dynamic_sidebar($sidebar['sidebar_area']); ?>
            </div><!-- .sidebar -->
        <?php } ?>
    </div><!-- .row -->
    <?php
    /**
     * @hooked
     */
    do_action( 'theme_after_main' ); ?>
    <?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
<?php get_footer(); ?>
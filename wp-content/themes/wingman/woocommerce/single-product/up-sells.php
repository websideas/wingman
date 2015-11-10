<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$posts_per_page = apply_filters('woocommerce_single_product_upsells', 12);

$upsells = $product->get_upsells();

if ( sizeof( $upsells ) == 0 ) {
	return;
}

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $upsells,
	'post__not_in'        => array( $product->id ),
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns_addons', 4 );
$woocommerce_loop['columns_tablet'] = apply_filters( 'loop_shop_columns_addons_tablet', 4 );

if ( $products->have_posts() ) : ?>

	<div class="upsells-products clearfix">
        <h3><?php _e( 'You may also like&hellip;', 'woocommerce' ) ?></h3>
        <div class="woocommerce-carousel-wrapper" data-theme="style-navigation-top" data-itemscustom="<?php echo apply_filters( 'woocommerce_single_product_carousel', '[[992,4], [768, 3], [480, 1]]'); ?>">
    		<?php woocommerce_product_loop_start(); ?>
    			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
    				<?php wc_get_template_part( 'content', 'product' ); ?>
    			<?php endwhile; // end of the loop. ?>
    		<?php woocommerce_product_loop_end(); ?>
        </div><!-- .woocommerce-carousel-wrapper -->
	</div><!-- .upsells-products -->

<?php endif;

wp_reset_postdata();

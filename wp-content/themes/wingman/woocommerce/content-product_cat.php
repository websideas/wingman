<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Increase loop count
$woocommerce_loop['loop'] ++;


// Extra post classes
$classes = array( 'clearfix' );
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
	$classes[] = 'first col-clearfix-lg col-clearfix-md';
}
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}

if( isset($woocommerce_loop['columns_tablet']) ){
	if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns_tablet'] || 1 == $woocommerce_loop['columns_tablet'] ){
		$classes[] = 'first-tablet col-clearfix-sm col-clearfix-xs';
	}
}
if( isset($woocommerce_loop['columns_tablet']) ){
	if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns_tablet'] ){
		$classes[] = 'last-tablet';
	}
}

// Bootstrap Column
$bootstrapColumn = round( 12 / $woocommerce_loop['columns'] );
if( isset($woocommerce_loop['columns_tablet']) ){
	$bootstrapTabletColumn = round( 12 / $woocommerce_loop['columns_tablet'] );
}else{
	$bootstrapTabletColumn = '12';
}
$classes[] = 'col-xs-'.$bootstrapTabletColumn.' col-sm-'. $bootstrapTabletColumn .' col-md-' . $bootstrapColumn;


?>
<li <?php wc_product_cat_class( $classes ); ?>>
	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
	<div class="product-image-container">
		<?php
		/**
		 * woocommerce_before_subcategory_title hook
		 *
		 * @hooked woocommerce_subcategory_thumbnail - 10
		 */
		do_action( 'woocommerce_before_subcategory_title', $category );
		?>

	</div>
	<h3>
		<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
			<?php
				echo $category->name;

				if ( $category->count > 0 )
					echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
			?>
			<?php
				/**
				 * woocommerce_after_subcategory_title hook
				 */
				do_action( 'woocommerce_after_subcategory_title', $category );
			?>
		</a>
	</h3>

	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>
</li>

<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
    $woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns_tablet'] ) )
    $woocommerce_loop['columns_tablet'] = apply_filters( 'loop_shop_columns_tablet', 2 );


// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
    return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array( 'product wow fadeInUp' );
if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
    $classes[] = 'first';
}
if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
    $classes[] = 'last';
}

// Bootstrap Column
$bootstrapColumn = round( 12 / $woocommerce_loop['columns'] );
$bootstrapTabletColumn = round( 12 / $woocommerce_loop['columns_tablet'] );
$classes[] = 'col-xs-'. $bootstrapTabletColumn .' col-sm-'. $bootstrapTabletColumn .' col-md-' . $bootstrapColumn.' col-lg-' . $bootstrapColumn;


?>
<li <?php post_class( $classes ); ?>>
    <div class="product-item-container">
        <div class="product-image-container">
            <div class="product-image-content">
                <?php
                /**
                 * woocommerce_before_shop_loop_item hook.
                 *
                 * @hooked woocommerce_template_loop_product_link_open - 10
                 */
                do_action( 'woocommerce_before_shop_loop_item' );

                /**
                 * woocommerce_before_shop_loop_item_title hook.
                 *
                 * @hooked kt_template_loop_product_thumbnail - 10
                 */
                do_action( 'woocommerce_before_shop_loop_item_title' );

                /**
                 * woocommerce_after_shop_loop_item hook.
                 *
                 * @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item' );
                ?>
            </div>
        </div>
        <div class="product-attr-container">
            <?php
            /**
             * woocommerce_shop_loop_item_title hook.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             */
            do_action( 'woocommerce_shop_loop_item_title' );

            /**
             * woocommerce_after_shop_loop_item_title hook.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );

            ?>
        </div>
    </div>
</li>

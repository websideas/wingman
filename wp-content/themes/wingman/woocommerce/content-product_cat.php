<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $woocommerce_loop;

// Store loop count we're currently on.
if ( empty( $woocommerce_loop['loop'] ) ) {
    $woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid.
if ( empty( $woocommerce_loop['columns'] ) ) {
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns_tablet'] ) )
    $woocommerce_loop['columns_tablet'] = apply_filters( 'loop_shop_columns_tablet', 2 );


// Extra post classes
$classes = array( 'clearfix' );

// Bootstrap Column
$bootstrapColumn = round( 12 / $woocommerce_loop['columns'] );
$bootstrapTabletColumn = round( 12 / $woocommerce_loop['columns_tablet'] );
$classes[] = 'col-xs-'. $bootstrapTabletColumn .' col-sm-'. $bootstrapTabletColumn .' col-md-' . $bootstrapColumn.' col-lg-' . $bootstrapColumn;


// Increase loop count.
$woocommerce_loop['loop']++;
?>
<li <?php wc_product_cat_class( $classes, $category ); ?>>

    <div class="product-image-container">
        <div class="product-image-content">
            <?php
            /**
             * woocommerce_before_subcategory_title hook.
             *
             * @hooked woocommerce_subcategory_thumbnail - 10
             */
            do_action( 'woocommerce_before_subcategory_title', $category );
            ?>
        </div>
    </div>
    <div class="product-attr-container">
        <?php
        /**
         * woocommerce_before_subcategory hook.
         *
         * @hooked woocommerce_template_loop_category_link_open - 10
         */
        do_action( 'woocommerce_before_subcategory', $category );

        /**
         * woocommerce_shop_loop_subcategory_title hook.
         *
         * @hooked woocommerce_template_loop_category_title - 10
         */
        do_action( 'woocommerce_shop_loop_subcategory_title', $category );

        /**
         * woocommerce_after_subcategory_title hook.
         */
        do_action( 'woocommerce_after_subcategory_title', $category );

        /**
         * woocommerce_after_subcategory hook.
         *
         * @hooked woocommerce_template_loop_category_link_close - 10
         */
        do_action( 'woocommerce_after_subcategory', $category ); ?>
    </div>

</li>
<?php
/**
 * The template for displaying product search form
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form method="get" class="woocommerce-product-search searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<div class="wrap_product_cat">
		<?php
			$args = array(
				'taxonomy' => 'product_cat',
                'id' => 'cat_'.rand(),
				'hierarchical' => 1,
				'show_option_all' => __('All Categories', 'wingman')
			);
			$args = apply_filters( 'kt_categories_product', $args );
			wp_dropdown_categories($args); 
		?>
	</div>
    <label class="screen-reader-text"><?php _e( 'Search for:', 'woocommerce' ); ?></label>
    <input type="text" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'woocommerce' ); ?>" />
    <input type="hidden" name="post_type" value="product" />
    <button><i class="fa fa-search"></i></button>
</form>


<?php
/**
 * Single Product tabs
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-accordions wc-accordions-wrapper">
		<?php foreach ( $tabs as $key => $tab ) : ?>
            <div class="woocommerce-accordions-item">
    			<h3 class="<?php echo esc_attr( $key ); ?>_tab accordions-title">
    				<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
    			</h3>
                <div class="entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
    				<?php call_user_func( $tab['callback'], $key, $tab ); ?>
    			</div>
            </div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>

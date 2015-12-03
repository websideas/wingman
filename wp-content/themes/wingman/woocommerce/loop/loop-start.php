<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
?>
<?php
    $page_animation = kt_option( 'page_animation' );
    $class_animation = ( $page_animation == 1 ) ? 'animation-effect' : '';
    $data_animation = ( $page_animation == 1 ) ? 'data-animation="fadeInUp" data-timeeffect="200"' : '';
?>
<div class="row multi-columns-row woocommerce-row">
    <ul <?php echo $data_animation; ?> class="<?php echo apply_filters('woocommerce_product_loop_start', 'shop-products clearfix '.$class_animation ); ?>">
<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
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
$classes = array( 'product' );
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
    $classes[] = 'first col-clearfix-lg col-clearfix-md';
}
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
    $classes[] = 'last';
}

if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns_tablet'] || 1 == $woocommerce_loop['columns_tablet'] )
    $classes[] = 'first-tablet col-clearfix-sm col-clearfix-xs';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns_tablet'] )
    $classes[] = 'last-tablet';

// Bootstrap Column
$bootstrapColumn = round( 12 / $woocommerce_loop['columns'] );
$bootstrapTabletColumn = round( 12 / $woocommerce_loop['columns_tablet'] );
$classes[] = 'col-xs-'.$bootstrapTabletColumn.' col-sm-'. $bootstrapTabletColumn .' col-md-' . $bootstrapColumn;


?>
<li <?php post_class( $classes ); ?>>
    <div class="row equal_height">
        <div class="col-md-7">
            <div class="sale-countdown-content">
                <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                
                <?php do_action( 'woocommerce_sale_sountdown_item', $product, $post ); ?>
                <?php
                    if($blog_atts['link']){
                        $a_href = $blog_atts['link']['url'];
                        $a_title = $blog_atts['link']['title'];
                        if(!$a_title){
                            $a_title = __( 'Shop All', THEME_LANG );
                        }
                        $a_target = $blog_atts['link']['target'];
                        $button_link = array('href="'.esc_attr( $a_href ).'"', 'title="'.esc_attr( $a_title ).'"', 'target="'.esc_attr( $a_target ).'"' );
                        
                        $class = '';
                        if( $blog_atts['button_style'] != '' ){
                            $class = ( $blog_atts['button_style'] == 'link' ) ? 'readmore-link' : 'btn '.$blog_atts['button_style'];
                        }
                        
                        echo '<a class="'.$class.'" '.implode(' ', $button_link).'>'.$a_title.'</a>';
                    }
                ?>
            </div>
        </div>
        <div class="col-md-5">
            <div class="image-sale-countdown">
                <div class="product-image-content">
                    <?php
                        if ( has_post_thumbnail() ) {
                            $image_thumb =  get_the_post_thumbnail( $post->ID, 'shop_catalog', array('class'=>"first-img product-img"));
                        } elseif ( wc_placeholder_img_src() ) {
                            $image_thumb = wc_placeholder_img( 'shop_catalog' );
                        }
                    ?>
                    <a href="<?php the_permalink(); ?>" class="product-thumbnail <?php if($attachment) echo "product-thumbnail-effect"; ?>">
                        <?php echo $image_thumb; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</li>

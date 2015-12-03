<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post, $woocommerce, $product, $sidebar;

?>
<div class="images">
    <div class="single-product-main-images <?php if( $sidebar['sidebar'] != 'full' ){ echo 'owl-carousel'; } ?>" id="sync1">
        <?php
        if ( has_post_thumbnail() ) {

            $image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
            $image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
            $image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
            $image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
                'title'	=> $image_title,
                'alt'	=> $image_title,
                'class' => 'img-responsive'
            ) );

            $attachment_count = count( $product->get_gallery_attachment_ids() );

            if ( $attachment_count > 0 ) {
                $gallery = '[product-gallery]';
            } else {
                $gallery = '';
            }

            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom easyzoom--overlay"><a href="%s" itemprop="image" class="woocommerce-main-image" title="%s">%s</a></div>', $image_link, $image_caption, $image ), $post->ID );

        } else {

            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );

        }

        //Get attachment IDS
        $attachment_ids = $product->get_gallery_attachment_ids();
        $attachment_count   = count( $attachment_ids );

        // Display Attachment Images as well
        if( $attachment_count > 0 ) :

            // Loop in attachment
            foreach ( $attachment_ids as $attachment_id ) {

                // Get attachment image URL
                $image_link = wp_get_attachment_url( $attachment_id );

                $image_title = esc_attr( get_the_title( $attachment_id ) );

                // If isn't a URL we go to next attachment
                if ( !$image_link )
                    continue;

                $image = wp_get_attachment_image( $attachment_id, 'shop_single', array(
                    'data-zoom-image' => $image_link,
                    'class' => 'img-responsive'
                ) );

                // Display other items
                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom easyzoom--overlay"><a href="%s" itemprop="image" class="woocommerce-attachment-image" title="%s">%s</a></div>', $image_link, $image_title, $image ), $post->ID );
            }

        endif;


        ?>
    </div><!-- #sync1.single-product-main-images.owl-carousel -->
    <?php do_action( 'woocommerce_product_thumbnails' ); ?>
</div>

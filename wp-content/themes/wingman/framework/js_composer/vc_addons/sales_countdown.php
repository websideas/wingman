<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Sales_Countdown extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'source' => '',
            'products' => '',
            'per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            
            'button_style' => 'btn-dark-b',
            'link' => '',
            
            'css_animation' => '',
            'el_class' => '',
            'columns' => 1,
            'css' => '',   
        ), $atts );
        extract($atts);
        $output = '';
        
        global $woocommerce_loop;
        
        $elementClass = array(
        	'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'sales-countdown-wrapper ', $this->settings['base'], $atts ),
        	'extra' => $this->getExtraClass( $el_class ),
        	'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );
        
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        $output .= '<div class="'.esc_attr( $elementClass ).'">';
        
        // Get products on sale
		$product_ids_on_sale = wc_get_product_ids_on_sale();

		$meta_query = WC()->query->get_meta_query();
        $posts_arr = '';
        if( $source == 'products' ){
            if( $products ){
                $posts_arr = array_filter(explode( ',', $products));
            }
        }else{
            $posts_arr = array_merge( array( 0 ), $product_ids_on_sale );
        }
        
		$args = array(
			'posts_per_page'	=> $atts['per_page'],
			'orderby' 			=> $atts['orderby'],
			'order' 			=> $atts['order'],
			'no_found_rows' 	=> 1,
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product',
			'meta_query' 		=> $meta_query,
			'post__in'			=> $posts_arr
		);
        
        $link = ( $link == '||' ) ? '' : $link;
        
        $array = array(
            'button_style' => $button_style,
            'link' => vc_build_link( $link )
        );
        
        ob_start();
		$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
		$woocommerce_loop['columns'] = $atts['columns'];
        if ( $products->have_posts() ) :
			woocommerce_product_loop_start();
				while ( $products->have_posts() ) : $products->the_post();
					kt_get_template_part( 'woocommerce/content', 'product-sale', $array );
				endwhile; // end of the loop.
			woocommerce_product_loop_end();
		endif;
		wp_reset_postdata();
                    
            $output .= '<div class="woocommerce columns-1">' . ob_get_clean() . '</div>';
        $output .= '</div>';
        
    	return $output;
    }
}

vc_map( array(
    "name" => __( "Sales Countdown", THEME_LANG),
    "base" => "sales_countdown",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "", THEME_LANG),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => __("Choose Products", THEME_LANG),
            "param_name" => "source",
            "value" => array(
                __('All', THEME_LANG) => '',
                __('Specific Products', THEME_LANG) => 'products',
            ),
            "admin_label" => true,
            'std' => '',
            "description" => __("Select content type for your posts.", THEME_LANG),
        ),
        array(
            "type" => "kt_posts",
            'args' => array('post_type' => 'product', 'posts_per_page' => -1, 'post__in' => array_merge( array( 0 ), wc_get_product_ids_on_sale() )),
            'heading' => __( 'Specific Products', 'js_composer' ),
            'param_name' => 'products',
            'size' => '5',
            'placeholder' => __( 'Select your products', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('products')),
            'multiple' => true,
        ),
        array(
			'type' => 'textfield',
			'heading' => __( 'Per page', 'js_composer' ),
			'value' => 1,
			'param_name' => 'per_page',
            'admin_label' => true,
			'description' => __( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
		),
        array(
			'type' => 'dropdown',
			'heading' => __( 'Order by', 'js_composer' ),
			'param_name' => 'orderby',
			'value' => array(
                __( 'Date', 'js_composer' ) => 'date',
    			__( 'ID', 'js_composer' ) => 'ID',
    			__( 'Author', 'js_composer' ) => 'author',
    			__( 'Title', 'js_composer' ) => 'title',
    			__( 'Modified', 'js_composer' ) => 'modified',
    			__( 'Random', 'js_composer' ) => 'rand',
    			__( 'Comment count', 'js_composer' ) => 'comment_count',
    			__( 'Menu order', 'js_composer' ) => 'menu_order',
            ),
            'admin_label' => true,
			'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order way', 'js_composer' ),
			'param_name' => 'order',
			'value' => array(
                __( 'Descending', 'js_composer' ) => 'DESC',
                __( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            'admin_label' => true,
			'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
        array(
            'type' => 'vc_link',
            'heading' => __( 'Button Link', 'js_composer' ),
            'param_name' => 'link',
            'description' => __( 'Enter button link.', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Button style', THEME_LANG ),
            'param_name' => 'button_style',
            'value' => array(
                __('None', THEME_LANG) => '',
                __( 'Link', 'js_composer' ) => 'link',
                __( 'Button Accent', 'js_composer' ) => 'btn-default',
                __( 'Button Light', 'js_composer' ) => 'btn-light',
                __( 'Button Dark', 'js_composer' ) => 'btn-dark',
                __( 'Button Gray', 'js_composer' ) => 'btn-gray',
                __( 'Button Accent Border', 'js_composer' ) => 'btn-default-b',
                __( 'Button Light Border', 'js_composer' ) => 'btn-light-b',
                __( 'Button Dark Border', 'js_composer' ) => 'btn-dark-b',
            ),
            "description" => __("Show or hide the readmore button.", THEME_LANG),
        ),
        array(
        	'type' => 'dropdown',
        	'heading' => __( 'CSS Animation', 'js_composer' ),
        	'param_name' => 'css_animation',
        	'admin_label' => true,
        	'value' => array(
        		__( 'No', 'js_composer' ) => '',
        		__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
        		__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
        		__( 'Left to right', 'js_composer' ) => 'left-to-right',
        		__( 'Right to left', 'js_composer' ) => 'right-to-left',
        		__( 'Appear from center', 'js_composer' ) => "appear"
        	),
        	'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
        ),
        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        array(
			'type' => 'css_editor',
			'heading' => __( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => __( 'Design options', 'js_composer' )
		),
        
    ),
));

